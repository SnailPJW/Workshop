 <!-- 主要容器 -->
    <div class="ts very narrow container">
        <!-- 主要信件卡片 -->
        <div class="ts card">
            <!-- 頂部內容與標題 -->
            <div class="center aligned padded content">
                <div class="ts large header">
                    <?php echo $tutorial_data['TITLE'];?>
                    <div class="smaller sub header">
                        
                    </div>
                </div>
            </div>
            <!-- / 頂部內容與標題 -->

            <!-- 特色圖片 -->
            <div class="image">
                <img class="ts large centered image" src="<?php echo $tutorial_data['TUTORIAL_IMAGE_URL'];?>">
            </div>
            <!-- / 特色圖片 -->

            <!-- 主要推銷內容 -->
            <div class="center aligned padded content">
                <p><?php echo $tutorial_data['SHORT_INTRO'];?></p>
                <br>

                <!-- 區段分隔線 -->
                <div class="ts section divider"></div>
                <!-- / 區段分隔線 -->
                <div class="ts inverted segment">
                    <div class="ts inverted primary statistic">
                        <div class="value" id="numEnrolled"><?php echo $tutorial_data['STUDENT_COUNT'];?></div>
                        <div class="label">已選課人數</div>
                    </div>
                    <div class="ts inverted warning statistic">
                        <div class="value"><?php echo $tutorial_data['REQ_STUDENT_COUNT'];?></div>
                        <div class="label">選課人數上限</div>
                    </div>
                </div>
                <!-- 區段分隔線 -->
                <div class="ts section divider"></div>
                <!-- / 區段分隔線 -->

                <!-- 特色項目群組 -->
                <div class="ts relaxed items">
                    <!-- 單個項目 -->
                    <div class="item">
                        <div class="left aligned content">
                            <a class="header">老師資訊</a>
                            <div class="description">
                            <div class="ts secondary primary structured message">
                                <div class="avatar">
                                    <img src="<?php echo $tutorial_data['TEACHER_DATA']['PICTURE_URL']; ?>">
                                </div>
                                <div class="content">
                                    <div class="description">
                                        <div class="header"><?php echo $tutorial_data['TEACHER_DATA']['NAME'];?></div>
                                        <p><?php echo $tutorial_data['TEACHER_DATA']['SHORT_INTRO'];?></p>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <br>
                            <a class="header">課前資訊</a>
                            <div class="description">
                                <div class="ts secondary message">
                                    <div class="header">課程程度</div>
                                    <p><?php echo $tutorial_data['TUTORIAL_LEVEL'];?></p>
                                </div>
                                <div class="ts secondary message">
                                    <div class="header">上課地點</div>
                                    <p><?php echo $tutorial_data['PREDICTED_COURSE_LOCATION'];?></p>
                                </div>
                                <div class="ts secondary message">
                                    <div class="header">預計開課日期</div>
                                    <p><?php echo $tutorial_data['PREPARE_DAYS'];?></p>
                                </div>
                                <div class="ts secondary primary message">
                                    <div class="header">上課前必須準備的物品</div>
                                    <p><?php echo $tutorial_data['NEEDED_ITEMS'];?></p>
                                </div>
                                <div class="ts secondary primary message">
                                    <div class="header">上課前需要的基本知識</div>
                                    <p><?php echo $tutorial_data['REQ_KNOWLEDGE'];?></p>
                                </div>
                                <div class="ts secondary primary message">
                                    <div class="header">上完課以後學生能夠做出(到)</div>
                                    <p><?php echo $tutorial_data['COURSE_OUTPUT'];?></p>
                                </div>                      
                            </div>
                        </div>
                    </div>
                    <!-- / 單個項目 -->

                    <!-- 單個項目 -->
                    <div class="item">
                        <div class="left aligned content">
                            <a class="header">詳細課程介紹</a>
                            <div class="description">
                                <div class="ts icon message">
                                    <i class="gift icon"></i>
                                    <div class="content">
                                        <div class="header"><?php echo $tutorial_data['TITLE'];?></div>
                                        <p><?php echo $tutorial_data['INTRODUCTION'];?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / 單個項目 -->
                </div>
                <!-- / 特色項目群組 -->
                <h4>預計單元</h4>
                <hr>
                <div class="ts selection segmented list">
                    <a href="#!" class="selected item">全程大約<?php echo $tutorial_data['PREDICTED_COURSE_LENGTH'];?>小時</a>
                    <?php 
                        $sessions = $tutorial_data['SESSIONS'];
                        foreach($sessions as $s){
                            echo '<a href="#!" class="item">單元'.$s['TUTORIAL_INDEX'].'：'.$s['TITLE'].'</a>';
                        }
                    ?>
                </div>
                
                
                <h3></h3>
                

                <!-- 區段分隔線 -->
                <div class="ts section divider"></div>
                <!-- / 區段分隔線 -->
                <!-- CTA：矚目動作按鈕 -->
                <?php
                    if(isset($tutorial_bought)){
                        echo '<button class="ts primary basic button" id="buy_button" onclick="buyCourse();"disabled>已經選課</button>';
                    }else if(!isset($is_teacher)){
                        echo '<button class="ts primary basic button" id="buy_button" onclick="buyCourse();">馬上選課</button>';
                    }
                    if(isset($is_teacher)&&$tutorial_data['STUDENT_COUNT']>=$tutorial_data['REQ_STUDENT_COUNT']){
                        echo '<button class="ts primary basic button" id="buy_button" onclick="startPrepare();">停止選課</button>';
                    }
                ?>
                <!-- / CTA：矚目動作按鈕 -->
            </div>
            <!-- / 主要推銷內容 -->
        </div>
        <!-- / 主要信件卡片 -->

        <!-- 底部相關連結片段 -->
        <div class="ts center aligned basic segment">
            <a href="https://www.facebook.com/%E8%87%BA%E5%8C%97%E7%A7%91%E5%A4%A7%E5%85%89%E5%A4%A7%E5%89%B5%E5%89%B5%E5%AD%B8%E9%99%A2-302519096751949/" target="_blank"><i class="facebook circular large icon"></i></a>
            <a href="http://rnd.ntut.edu.tw/bin/home.php" target="_blank"><i class="sun circular large icon"></i></a>
            <a href="http://www.ntut.edu.tw/bin/home.php" target="_blank"><i class="student circular large icon"></i></a>
            <a href="https://www.slideshare.net/university2025/ss-66534774" target="_blank"><i class="slideshare circular large icon"></i></a>
        </div>
        <!-- / 底部相關連結片段 -->

        <div id="disqus_thread"></div>
        <script>

        /**
        *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
        *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/
        /*
        var disqus_config = function () {
        this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
        this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
        };
        */
        (function() { // DON'T EDIT BELOW THIS LINE
        var d = document, s = d.createElement('script');
        s.src = 'https://http-130-211-173-219-ntutv01.disqus.com/embed.js';
        s.setAttribute('data-timestamp', +new Date());
        (d.head || d.body).appendChild(s);
        })();
        </script>
        <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
        
    </div>
    <!-- / 主要容器 -->
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
            // var result = resp.date.toString();
            // if(result =='選課成功'){
                $('#buy_button').attr('disabled', true);
                $('#buy_button').text('已經選課');

                var tut_num = <?php echo $tutorial_data['STUDENT_COUNT'];?>;
                ++tut_num;
                $('#numEnrolled').html(tut_num);
            // }
        });
    }
</script>
