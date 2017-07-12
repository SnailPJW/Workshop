<!-- 左側邊欄 -->
<div class="ts inverted overlapped left vertical fluid menu sidebar">
    <!-- 個人資料項目 -->
    <div class="center aligned item">
        <img class="ts tiny circular image" id="user_pic" src="<?php echo $user_data['PICTURE_URL']; ?>" style="background-color: white;">
        <br>
        <br>
        <div><?php echo $user_data['NAME'];?></div>
    </div>
    <!-- / 個人資料項目 -->
    <!-- 綁定FB -->
    <!-- <a href="#!" class="item" id='connectFB'>
        <i class="facebook f icon"></i>
        綁定Facebook
    </a> -->
    <!-- / 綁定FB -->
    <!-- 使用者 -->
    <div class="item">
        <i class="user icon"></i>
        帳戶
        <div class="menu">
            <a class="item"><?php echo $user_data['ACCOUNT'];?></a>
        </div>
    </div>
    <!-- / 使用者 -->
    <!-- 信箱 -->
    <div class="item">
        <i class="mail icon"></i>
        信箱
        <div class="menu">
            <a class="item"><?php echo $user_data['EMAIL'],"<small>"; if($user_data['EMAIL_CONFIRMED']) echo ' (已驗證)';else echo ' (未驗證)';echo "</small>" ?></a>
        </div>
    </div>
    <!-- / 信箱 -->
    <!-- 網站管理 -->
    <div class="item">
        <i class="image icon"></i>
        照片
        <div class="menu">
            <a class="item">上傳
                    <div class="wrapper">
                      <div class="file-upload">
                        <i class="tiny cloud upload icon">
                        <input type="file" id="picture_file_upload" accept="image/*">
                        </i>
                      </div>
                    </div>
            </a>
        </div>
    </div>
    <!-- / 網站管理 -->
</div>
<!-- / 左側邊欄 -->
<div class="ts pusher">
<nav class="ts inverted borderless stackable menu" style="margin-top: 0;">
    <a class="header item" href="<?php echo base_url();?>">
        <img class="ts tiny circular image" src="<?php echo base_url();?>asset/img/worklogo_sm.jpg" alt="WorkShop">
        <div class="ts inverted vertically fitted basic segment">    
           <div class="ts inverted large dividing header">沃課ＳＨＯＰ</div>
            <div class="collegName ts inverted small sub header">國立臺北科大<br>光大創創學院</div>
        </div>
    </a>
    <div class="ts item dropdown horizontally fitted">
        <div class="text">關於沃課</div>
        <i class="dropdown icon"></i>
        <div class="menu">
            <a class="item" href="<?php echo base_url();?>index.php/pages/view/aboutWork">沃課精神</a>
            <a class="item" href="<?php echo base_url();?>index.php/pages/view/teachers-community">沃課隊長</a>
            <a class="item" href="<?php echo base_url();?>index.php/pages/view/workshopBlog">沃課部落</a>
            <!-- <a class="item" href="<?php echo base_url();?>index.php/pages/view/customer-service">課訴</a> -->
        </div>
    </div>
    <div class="ts item dropdown">
        <div class="text">沃課 Ｗｏｒｋ</div>
        <i class="dropdown icon"></i>
        <div class="menu">
            <a class="item" href="<?php echo base_url();?>index.php/pages/view/raise-fund-for-tutorial">秘笈總覽</a>
            <a class="item" href="<?php echo base_url();?>index.php/pages/view/start-new-tutorial">吾時吾課</a>
            <a class="item" href="<?php echo base_url();?>index.php/pages/view/Wishing-tutorial">心想課成</a>
        </div>
    </div>
    <div class="ts item dropdown">
        <div class="text">四次元口袋</div>
        <i class="dropdown icon"></i>
        <div class="menu">
            <a class="item" href="<?php echo base_url();?>index.php/pages/view/messageBoard">如果電話亭</a>
            <a class="item" href="<?php echo base_url();?>index.php/pages/view/portfolioDoor">成果任意門</a>
            <a class="item" href="<?php echo base_url();?>index.php/pages/view/powerSweet">能量銅鑼燒</a>
        </div>
    </div>
    <div class="right labeled icon menu">
            <div class="item">
                <div id="abgne_marquee">
                    <div class="ts list">
                        <a class="item" href="<?php echo base_url();?>index.php/pages/view/messageBoard">
                            <div class="ts basic primary label">
                                <i class="star primary icon"></i> 最新
                            </div>最新.最新訊息.
                        </a>
                        <a class="item" href="<?php echo base_url();?>index.php/pages/view/portfolioDoor">
                            <div class="ts basic label">
                                <i class="thumbs outline up icon"></i> 好評
                            </div>好評.好評訊息.
                        </a>
                        <a class="item" href="<?php echo base_url();?>index.php/pages/view/powerSweet">
                            <div class="ts basic negative label">
                                <i class="fire negative icon"></i> 熱門
                            </div>熱門.熱門訊息.
                        </a>
                    </div>
                </div>
            </div>
            <div class="item horizontally fitted">
                <div class="ts inverted vertical buttons">
                    <button class="marquee_btn ts mini very compact circular icon button" id="marquee_prev_btn">
                        <i class="chevron up icon"></i>
                    </button>
                    <button class="marquee_btn ts mini very compact circular icon button" id="marquee_next_btn">
                        <i class="chevron down icon"></i>
                    </button>
                </div>
            </div>
            <div class="right item">
                <div class="ts borderless right icon input">
                    <input id="menuKeywordBar" type="text" placeholder="搜尋關鍵字..." maxlength="32">
                    <i class="inverted circular search link icon"  id='googleSearchBtn'></i>
                </div>
            </div>
            <?php 
                if(isset($username)){//已經登入的狀態
                    if($user_data['ADMIN']){
                        echo '<div class="ts item dropdown labeled icon">
                            <i class="big spy icon"></i>
                            <i class="dropdown icon"></i>
                            <div class="menu">';
                        foreach($navbar_links as $link){
                            $nav_title = $page_to_title[$link];
                            if($link=='authorize-pending-tutorials'){
                                echo '<a class="item" href="'.base_url().'index.php/pages/view/'.$link.'">'.$nav_title.'</a></li>';
                            }
                        }
                        echo '</div></div>';
                    }
                    echo '<div class="ts item dropdown labeled icon horizontally fitted">
                            <i class="big leaf icon"></i>
                            <i class="dropdown icon"></i>
                            <div class="menu">

                                <!--<a class="item" href="'.base_url().'index.php/pages/view/on-site-msg">
                                <div class="ts label">1</div>訊息</a>-->
                                <a class="item" href="'.base_url().'index.php/pages/view/account-settings">個人資料</a>
                                <a class="item" href="'.base_url().'index.php/users/logout">登出</a>
                            </div></div>';
                }
                else{//尚未登入
                    echo '<a class="item" href="'.base_url().'index.php/pages/view/login"><i class="big sign in icon"></i>登入</a>';
                }
            ?>
    </div>
</nav>
<!-- <div class="ts center aligned attached segment">
    <div class="ts fluid container container">
        <div id="abgne_marquee2">
            <div class="ts list">
                <a class="item" href="<?php echo base_url();?>index.php/pages/view/messageBoard">
                    <div class="ts basic primary label">
                        <i class="star primary icon"></i> 最新
                    </div>最新.最新訊息.
                </a>
                <a class="item" href="<?php echo base_url();?>index.php/pages/view/portfolioDoor">
                    <div class="ts basic label">
                        <i class="thumbs outline up icon"></i> 好評
                    </div>好評.好評訊息.
                </a>
                <a class="item" href="<?php echo base_url();?>index.php/pages/view/powerSweet">
                    <div class="ts basic negative label">
                        <i class="fire negative icon"></i> 熱門
                    </div>熱門.熱門訊息.
                </a>
            </div>
        </div>
    </div>
</div> -->