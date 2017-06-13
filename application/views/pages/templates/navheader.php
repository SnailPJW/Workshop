<nav class="ts inverted flatted borderless stackable menu">
    <a class="header item" href="<?php echo base_url();?>">
        <img class="ts tiny circular image" src="<?php echo base_url();?>asset/img/worklogo_sm.jpg" alt="WorkShop">
        <div class="ts inverted vertically fitted basic segment">    
           <div class="ts inverted large dividing header">沃課ＳＨＯＰ</div>
            <div class="collegName ts inverted small sub header">國立臺北科大<br>光大創創學院</div>
        </div>
    </a>
    <div class="ts item dropdown">
        <div class="text">關於沃課</div>
        <i class="dropdown icon"></i>
        <div class="menu">
            <a class="item" href="<?php echo base_url();?>index.php/pages/view/aboutWork">關於</a>
            <a class="item" href="<?php echo base_url();?>index.php/pages/view/teachers-community">人員</a>
            <a class="item" href="<?php echo base_url();?>index.php/pages/view/customer-service">課訴</a>
        </div>
    </div>
    <div class="ts item dropdown">
        <div class="text">沃課 Ｗｏｒｋ</div>
        <i class="dropdown icon"></i>
        <div class="menu">
            <a class="item" href="<?php echo base_url();?>index.php/pages/view/raise-fund-for-tutorial">搜尋</a>
            <a class="item" href="<?php echo base_url();?>index.php/pages/view/start-new-tutorial">播種</a>
            <a class="item" href="<?php echo base_url();?>index.php/pages/view/Wishing-tutorial">祈求</a>
        </div>
    </div>
    <div class="ts item dropdown">
    <div class="text">成果研究能量</div>
        <i class="dropdown icon"></i>
        <div class="menu">
        <a class="item" href="<?php echo base_url();?>index.php/pages/view/paradigm">典範</a>
        <a class="item" href="#">無邊界</a>
        <a class="item" href="#">教卓</a>
        <a class="item" href="#">其他</a>
        </div>
    </div>
    
    <div class="inverted right labeled icon menu">
            <?php 
                if(isset($username)){//已經登入的狀態
                    if($user_data['ADMIN']){
                        echo '<div class="ts item dropdown labeled icon">
                            <i class="big spy icon"></i>
                            <i class="dropdown icon"></i>
                            <div class="menu">';
                        foreach($navbar_links as $link){
                            $nav_title = $page_to_title[$link];
                            if($link=='authorize-pending-tutorials' || $link=='answer-ticket'){
                                echo '<a class="item" href="'.base_url().'index.php/pages/view/'.$link.'">'.$nav_title.'</a></li>';
                            }
                        }
                        echo '</div></div>';
                    }
                    echo '<div class="ts item dropdown labeled icon">
                            <i class="big leaf icon"></i>
                            <i class="dropdown icon"></i>
                            <div class="menu">
                                <a class="item" href="'.base_url().'index.php/pages/view/on-site-msg">
                                <div class="ts label">1</div>訊息</a>
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