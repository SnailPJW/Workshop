<div class="container">
    <h1><?php echo $tutorial_data['TITLE'];?></h1>
    <div class="row">
        <!-- <div class="col-sm-8" id="video_container">
            <video id="preview_video" style='max-width:100%;' src="<?php echo $tutorial_data['SESSIONS'][0]['VIDEO_URL'];?>" controls/>
        </div> -->
        <div class="col-sm-8" id="img_container">
           <!-- <img src="http://130.211.173.219/ntutv01/user_upload/image/5923efd18768f.jpg" style='max-width:100%;'/>  -->
           <img src="<?php echo $tutorial_data['TUTORIAL_IMAGE_URL'];?>" style='max-width:100%;'/>
       </div>
        <div class="col-sm-4 play-selector" style="overflow-y: scroll;background-color: rgb(230,230,200);">
            <h3 id="course_length_title"></h3>
        <?php
        $sessions = $tutorial_data['SESSIONS'];
        $can_play = isset($is_teacher) || isset($tutorial_bought);
        $idx = 0;
        foreach($sessions as $s){
            if($idx==0) {
                echo '<button session_id="'.$s['TUTORIAL_SESSION_ID'].'" class="online-play-btn btn btn-warning btn-lg glyphicon glyphicon-play-circle" data-toggle="tooltip" title="播放">單元'.$idx.':'.$sessions[$idx]['TITLE'].'</button>';
            }else{
                if($can_play)   echo '<button session_id="'.$s['TUTORIAL_SESSION_ID'].'" class="online-play-btn btn btn-warning btn-lg glyphicon glyphicon-play-circle" data-toggle="tooltip" title="播放">單元'.$idx.':'.$sessions[$idx]['TITLE'].'</button>';
                else echo'<button session_id="'.$s['TUTORIAL_SESSION_ID'].'" class="online-play-btn btn btn-warning btn-lg glyphicon glyphicon-lock" data-toggle="tooltip" title="播放" disabled>單元'.$idx.':'.$sessions[$idx]['TITLE'].'</button>';
            }
            ++$idx;
        }
        ?>
            
        </div>
    </div>
    <div class="row">
        <div class="col-sm-4 pull-right right_side_panel">
            <h1 class="text-center">關於老師 <?php echo $tutorial_data['TEACHER_DATA']['NAME'];?></h1>
            <hr>
            <div class="text-center">
            <img class="user-icon-picture text-center" src="<?php echo $tutorial_data['TEACHER_DATA']['PICTURE_URL']; ?>" />
            </div>
            <hr>
            <h1><small style="text-align: left;"><?php echo $tutorial_data['TEACHER_DATA']['SHORT_INTRO'];?></small></h1>
        </div>
        <div class="col-sm-8" id="course_info">
            <h1>狀態：正式開課</h1>
            <h3>學生 <?php echo $tutorial_data['STUDENT_COUNT'];?> 人</h3>
            <!-- <h2>價位$<?php echo $tutorial_data['REQ_PRICE'];?></h2> -->
            <?php
                if(isset($tutorial_bought)){
                    echo '<button class="btn btn-lg btn-primary" id="buy_button" onclick="buyCourse();" disabled>已經購買</button>';
                }else if(!isset($is_teacher)){
                    echo '<button class="btn btn-lg btn-primary" id="buy_button" onclick="buyCourse();">馬上購買!</button>';
                }
            ?>
        </div>
        <div class="col-sm-8">
            <h1><b>課前資訊</b></h1>
            <hr>
            <h3><strong>課程程度</strong></h3>
            <h3><small><?php echo $tutorial_data['TUTORIAL_LEVEL'];?></small></h3>
            <h3><strong>上課地點</strong></h3>
            <h3><small><?php echo $tutorial_data['PREDICTED_COURSE_LOCATION'];?></small></h3>
            <h3><strong>上課前必須準備的物品</strong></h3>
            <h3><small><?php echo $tutorial_data['NEEDED_ITEMS'];?></small></h3>
            <h3><strong>上課前需要的基本知識</strong></h3>
            <h3><small><?php echo $tutorial_data['REQ_KNOWLEDGE'];?></small></h3>
            <h3><strong>上完課以後學生能夠做出(到)</strong></h3>
            <h3><small><?php echo $tutorial_data['COURSE_OUTPUT'];?></small></h3>
        </div>
        <div class="col-sm-12">
            <h1><b>課程介紹</b></h1>
            <hr>
            <?php echo $tutorial_data['INTRODUCTION'];?>
            
            <h1><b>單元</b></h1>
            <hr>
            <?php 
                $sessions = $tutorial_data['SESSIONS'];
                foreach($sessions as $s){
                    echo '<h3>單元'.$s['TUTORIAL_INDEX'].':    '.$s['TITLE'].'</h3>';
                }
            ?>
        </div>
    </div>
</div>
<script>
    function loadSessionData(sessions){
        for(var i=0;i<sessions.length;++i){
            var s = sessions[i];
            session_data[s.TUTORIAL_SESSION_ID] = s;
        }
    }
    var session_data = {};
    
    function buyCourse(){
        var tut_id = <?php echo $tutorial_data['TUTORIAL_ID'];?>;
        SimpleMsgHandler.handleRequest(true, TutorialController.buyTutorial(tut_id), function(resp){
            alert(resp.data);
            window.location.reload();
        });
    }
    var courseLength = <?php echo $tutorial_data['COURSE_LENGTH'];?>;
    $(function(){
        $('.online-play-btn').on('click',function(){
            var session_id = $(this).attr('session_id');
            var vid_url = session_data[session_id]['VIDEO_URL'];
            $('#preview_video').attr('src', vid_url);
            var vid = document.getElementById('preview_video');
            vid.play();
        });
        loadSessionData(<?php echo json_encode($tutorial_data['SESSIONS']);?>);
        $('#course_length_title').text('課程總長: '+TimeUtil.secondsToHRTime(courseLength));
    });
</script>