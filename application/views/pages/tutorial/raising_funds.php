<div class="container">
    <h1><?php echo $tutorial_data['TITLE'];?></h1>
    <div class="row">
        
        <!-- <div class="col-sm-8" id="video_container">
            影片移除<video id="preview_video" style='max-width:100%;' src="<?php echo $tutorial_data['SESSIONS'][0]['VIDEO_URL'];?>" controls/>
        </div> -->
        <div class="col-sm-8" id="img_container">
           <!-- <img src="http://130.211.173.219/ntutv01/user_upload/image/5923efd18768f.jpg" style='max-width:100%;'/>  -->
           <img src="<?php echo $tutorial_data['TUTORIAL_IMAGE_URL'];?>" style='max-width:100%;'/>
       </div>
        <div class="col-sm-4" id="course_info" style="text-align: center;">
            <h1>狀態：募資中</h1>
            <h3>已經募得 <?php echo $tutorial_data['STUDENT_COUNT'].'/'.$tutorial_data['REQ_STUDENT_COUNT'];?> 人</h3>
            <!-- <h2>募資還剩 <?php echo $tutorial_data['RAISE_FUND_DAYS_REMAINING'];?> 天</h2> -->
            <!-- <h2>預購價 $<?php echo $tutorial_data['DISCOUNT_REQ_PRICE'];?></h2> -->
            <?php
                if(isset($tutorial_bought)){
                    echo '<button class="btn btn-lg btn-primary" id="buy_button" onclick="buyCourse();" disabled>已經購買</button>';
                }else if(!isset($is_teacher)){
                    echo '<button class="btn btn-lg btn-primary" id="buy_button" onclick="buyCourse();">馬上購買!</button>';
                }
                if(isset($is_teacher)&&$tutorial_data['STUDENT_COUNT']>=$tutorial_data['REQ_STUDENT_COUNT']){
                    echo '<button class="btn btn-lg btn-danger" onclick="startPrepare();">停止募資，進入備課階段</button>';
                }
            ?>
            <br>
            <h3>預計開課日期：<?php echo $tutorial_data['COURSE_PRESUMED_DATE'];?></h3>
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
            
            <h1><b>預計單元</b></h1>
            <hr>
            <h3>全程大約<?php echo $tutorial_data['PREDICTED_COURSE_LENGTH'];?>小時</h3>
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
    function startPrepare(){
        if(confirm('你已經達到開課目標.按下確定代表你想停止募資,進入課程開課前的準備階段.在此期間其他人無法繼續預購你的課程,當你完成備課其他學生才能購買課程.您不用擔心先進入備課階段會造成你備課完成的時間提前,因為課程開課的時間仍然是以您課程核准日起算+1個月+您設定的備課天數算.')){
            SimpleMsgHandler.handleRequest(true, TutorialController.enterPrepareStage(<?php echo $tutorial_data['TUTORIAL_ID'];?>), function(resp){
                alert(resp.data);
                window.location.href = "<?php echo base_url().'index.php/pages/editTutorial/'.$tutorial_data['TUTORIAL_ID'];?>";
            });
        }
    }
    function buyCourse(){
        var tut_id = <?php echo $tutorial_data['TUTORIAL_ID'];?>;
        SimpleMsgHandler.handleRequest(true, TutorialController.buyTutorial(tut_id), function(resp){
            alert(resp.data);
            //remove the button
            $('#buy_button').attr('disabled', true);
            $('#buy_button').text('已經購買');
        });
    }
</script>
