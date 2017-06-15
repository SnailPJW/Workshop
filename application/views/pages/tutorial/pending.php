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

                <!-- 特色項目群組 -->
                <div class="ts relaxed items">
                    <!-- 單個項目 -->
                    <div class="item">
                        <div class="left aligned content">
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
                <button class="ts primary basic button" onclick="authorizePendingTutorial();">核准開課</button>
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
    function authorizePendingTutorial(){
        var tut_id = <?php echo $tutorial_data['TUTORIAL_ID'];?>;
        SimpleMsgHandler.handleRequest(true, TutorialController.authorizePendingTutorial(tut_id), function(){
            window.location.href = "<?php echo base_url().'index.php/pages/view/authorize-pending-tutorials';?>";
        });
    }
</script>
<script id="dsq-count-scr" src="//http-130-211-173-219-ntutv01.disqus.com/count.js" async></script>