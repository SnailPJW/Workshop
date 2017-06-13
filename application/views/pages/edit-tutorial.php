<div class="container">
    <h1><?php echo $tutorial_data['TITLE'];?></h1>
    <div class="row">
        <div class="col-sm-8" id="video_container">
            <!--<video id="preview_video" style='max-width:100%;' src="<?php echo $tutorial_data['SESSIONS'][0]['VIDEO_URL'];?>" controls/>-->
        </div>
        <div class="col-sm-4" id="course_info" style="text-align: center;">
            <h1>狀態：備課中</h1>
            <h3>已經募得 <?php echo $tutorial_data['STUDENT_COUNT'].'/'.$tutorial_data['REQ_STUDENT_COUNT'];?> 人</h3>
            <br>
            <small>預計開課日期：<?php echo $tutorial_data['COURSE_PRESUMED_DATE'];?></small>
        </div>
    </div>
    <hr>
    <h1>上傳課程 <small>規劃課程長度<?php echo $tutorial_data['PREDICTED_COURSE_LENGTH'];?>小時-目前總長<span id="course_length_span"></span></small></h1>
    <div class="row" style="text-align: center;">
    
    <?php
        $sessions = $tutorial_data['SESSIONS'];
        $idx = 0;
        foreach($sessions as $s){
            if($idx==0) {
                echo '<div class="col-sm-12 session_container clearfix" session_id="'.$s['TUTORIAL_SESSION_ID'].'"><span class="state_icon glyphicon pull-left"></span><span class="unit_text">單元'.$idx.':'.$sessions[$idx]['TITLE'].'</span>'
                    .'<span class="pull-right"><button class="play-btn btn btn-default btn-lg glyphicon glyphicon-play-circle" data-toggle="tooltip" title="播放"/></span></div>';
            }else{
                echo '<div class="col-sm-12 session_container clearfix" session_id="'.$s['TUTORIAL_SESSION_ID'].'"><span class="state_icon glyphicon pull-left"></span><span class="unit_text">單元'.$idx.':'.$sessions[$idx]['TITLE'].'</span>'
                    .'<span class="pull-right"><button class="upload-btn btn btn-file btn-default btn-lg glyphicon glyphicon-upload" data-toggle="tooltip" title="上傳影片"><input type="file" class="video_file_upload" hidden></button><button class="play-btn btn btn-default btn-lg glyphicon glyphicon-play-circle" data-toggle="tooltip" title="播放"/></span></div>';
            }
            ++$idx;
        }
    ?>
    </div>
    <div class="bottom-float-panel" id="video_upload_progress_panel" hidden>
        <span>上傳影片中...</span>
        
        <div class="progress center-block" id="video_upload_progress" style="width: 99%;">
            <div class="progress-bar" role="progressbar" aria-valuenow="1"
            aria-valuemin="0" aria-valuemax="100" style="width:1%">
            </div>
        </div>
    </div>
    <?php if($tutorial_data['STATE']=='prepare')    echo'<center><button id="open_course_button" onclick="openCourse();" class="btn btn-danger btn-lg" disabled>正式開課!</button></center>';?>
    
</div>

<script>
    function openCourse(){
        SimpleMsgHandler.handleRequest(true, TutorialController.openTutorial(<?php echo $tutorial_data['TUTORIAL_ID'];?>), function(resp){
            alert(resp.data);
            window.location.href = "<?php echo base_url().'index.php/pages/view/search-tutorial';?>";
        })
    }
    function toggleFadeIn(){
        if(!faded_in)   $('.bottom-float-panel').fadeIn('slow');
        else $('.bottom-float-panel').fadeOut('slow');
        faded_in = !faded_in;
    }
    var session_data = <?php
        //turn to session_id=>data
        $session_id_to_row_map = array();
        foreach($sessions as $s){
            $session_id_to_row_map[$s['TUTORIAL_SESSION_ID']] = $s;
        }
        echo json_encode($session_id_to_row_map);
    ?>;
    function setStateIcon(selector, state){
        //map the state to a class
        var cls = '';
        var tooltip = '';
        if(state=='online'){
            cls = 'glyphicon-ok';
            tooltip = '影片可以觀看';
        }else if(state=='uploading'){
            cls = 'glyphicon-upload';
            tooltip = '正在上傳';
        }else if(state=='uploaded'||state=='processing'){
            cls = 'glyphicon-refresh';
            tooltip = '進行影片最佳化';
        }else{
            cls = 'glyphicon-remove';
            tooltip = '影片未上傳';
        }
        $(selector).find('.state_icon').each(function(){
            $(this).removeClass('glyphicon-ok glyphicon-upload glyphicon-refresh glyphicon-remove');
            $(this).addClass(cls);
            $(this).attr('title', tooltip);
        });
    }
    function disableUploadButtons(){
        $('.upload-btn').attr('disabled', true);
    }
    function enableUploadButtons(){
        $('.upload-btn').attr('disabled', false);
    }
    function secondsToHRTime(){
        var hours = Math.floor(courseLength/3600.0);
        var mins = Math.floor((courseLength%3600)/60.0);
        var secs = courseLength%60;
        var output = secs+"秒";
        if(mins>0){
            output = mins+"分"+output;
        }
        if(hours>0){
            return hours+"小時"+output;
        }
        return output;
    }
    function updateCourseLength(){
        var total_length = 0;
        for(var id in session_data){
            if(session_data.hasOwnProperty(id)){
                var s = session_data[id];
                if(s.VIDEO_DATA!=null){
                    total_length += s.VIDEO_DATA.VIDEO_LENGTH;
                }
            }
        }
        courseLength = total_length;
        $('#course_length_span').empty();
        $('#course_length_span').text(TimeUtil.secondsToHRTime(courseLength));
    }
    function updateSessionUIStates(){
        var all_rdy = true;
        for(var id in session_data){
            if(session_data.hasOwnProperty(id)){
                var selector = '.session_container[session_id="'+id+'"]';
                var data = session_data[id];
                if(data['TUTORIAL_INDEX']==0){ 
                    setStateIcon(selector , 'online')
                    continue;
                }
                var video_data = data.VIDEO_DATA;
                if(video_data==null){
                    //console.log($(selector).find('.play-btn'));
                    setStateIcon(selector, false);
                    $(selector).find('.play-btn').attr('disabled', true);
                    all_rdy=false;
                    continue;
                }
                
                var vid_state = video_data.STATE;
                setStateIcon(selector, vid_state);
                if(vid_state!='online'){
                    $(selector).find('.play-btn').attr('disabled', true);
                    all_rdy = false;
                }else{
                    $(selector).find('.play-btn').attr('disabled', false);
                    
                }
            }
        }
        //see if all sessions are ready, if so, then enable the open course button
        if(all_rdy){
            $('#open_course_button').attr('disabled', false);
        }else{
            $('#open_course_button').attr('disabled', true);
        }
    }
    function sendRegisterVideoRequest(vid_id, session_id){
        TutorialController.registerVideoToTutorialSession(vid_id, session_id)
            .done(function(){
                //not uploading
                uploading = false;
                enableUploadButtons();
                checkSessionStates();
            })
            .fail(function(){
                setTimeout(function(){ sendRegisterVideoRequest(); }, 3000);
            });
    }
    var courseLength = 0;
    function loadSessionData(sessions){
        for(var i=0;i<sessions.length;++i){
            var s = sessions[i];
            session_data[s.TUTORIAL_SESSION_ID] = s;
        }
    }
    function checkSessionStates(){
        if(window_focused){
            TutorialController.checkSessionStates(<?php echo $tutorial_data['TUTORIAL_ID'];?>)
                .done(function(resp){
                    if(resp.status=='success'){
                        var sessions = resp.data;
                        loadSessionData(sessions);
                        updateCourseLength();
                        updateSessionUIStates();
                    }

                });
        }
        
    }
    var uploading = false;
    var processing_video_ids = [];
    var window_focused = false;
    function updateProgressBar(ratio){
        var percentage = ratio*100;
        $('.progress-bar').css('width', percentage+'%').attr('aria-valuenow', percentage);
        $('#video_upload_progress_panel span').empty();
        $('#video_upload_progress_panel span').text('已上傳 '+percentage.toFixed(2)+" %");
    }
    $(function(){
        $('[data-toggle="tooltip"]').tooltip();
        $('state_icon').tooltip();
        updateSessionUIStates();
        $('.play-btn').on('click',function(){
            var session_id = $(this).closest('div').attr('session_id');
            var vid_url = session_data[session_id]['VIDEO_DATA']['VIDEO_URL'];
            $('#preview_video').attr('src', vid_url);
            var vid = document.getElementById('preview_video');
            vid.play();
        });
        $('.video_file_upload').on('change', function(){
            var files = this.files;
            if(files.length>0){
                var session_id = $(this).closest('div').attr('session_id');
                console.log(session_id);
                var file = files[0];
                disableUploadButtons();
                $('#open_course_button').attr('disabled', true);
                $('.bottom-float-panel').fadeIn('slow');
                uploading = true;
                var cntl = new VideoController();
                cntl.run_check_video_state_loop = false;
                cntl.complete_callback = function(){
                    var vid_id = cntl.videoId;
                    sendRegisterVideoRequest(vid_id, session_id);
                    $('.bottom-float-panel').fadeOut('slow');
                };
                cntl.fail_callback = function(resp){
                    alert(resp.data);
                    uploading = false;
                    enableUploadButtons();
                    $('.bottom-float-panel').fadeOut('slow');
                }
                cntl.progress_callback = function(ratio){
                    updateProgressBar(ratio);
                }
                cntl.uploadWholeVideo(file);
            }
        });
        $(window).focus(function() {
            window_focused = true;
        });

        $(window).blur(function() {
            window_focused = false;
        });
        window_focused = true;
        updateCourseLength();
        setInterval(function(){ checkSessionStates(); }, 10000);
    });
</script>