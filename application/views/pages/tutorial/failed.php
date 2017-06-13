<div class="container">
    <h1><?php echo $tutorial_data['TITLE'];?></h1>
    <div class="row">
        
        <div class="col-sm-8" id="video_container">
            <!--影片移除<video id="preview_video" style='max-width:100%;' src="<?php echo $tutorial_data['SESSIONS'][0]['VIDEO_URL'];?>" controls/>-->
        </div>
        <div class="col-sm-4" id="course_info" style="text-align: center;">
            <h1>狀態：開課失敗</h1>
            <h3>原因: <?php echo $tutorial_data['FAILED_REASON'];?></h3>
            
            <br>
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
