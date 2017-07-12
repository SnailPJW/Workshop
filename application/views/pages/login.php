<div style="height: 100%;">
        <div class="ts narrow container">
            <br><br>
            <h1 class="ts center aligned header">
                歡迎回來
                <div class="sub header">Keep Going</div>
            </h1> 

            <div class="ts centered borderless segment" id="login_panel" style="max-width: 600px;">
               
                <div class="ts stackable relaxed grid">
   
                <div class="ts eight wide column raised info card">
                    <div class="image">
                        <img src="https://tocas-ui.com/assets/img/15d7510.png">
                    </div>
                    <div class="content">
                        <a class="header">從喜歡學習的感覺開始</a>
                    </div>
                </div>            

                <form class="ts eight wide column form" id="login_form" method="get" action="LOGIN_TYPE">
                <div class="field">
                    <label>帳號</label>
                    <div class="ts left icon fluid input"> 
                        <input type="text" name="ACCOUNT">
                        <i class="user icon"></i>
                    </div>
                </div> 
                <div class="left icon field">
                    <label>密碼</label> 
                    <div class="ts left icon fluid input">
                        <input type="password" class="password" name="PASSWORD">
                        <i class="lock icon"></i>
                    </div> 
                </div> 
                <!-- <div class="ts toggle checkbox">
                    <input type="checkbox" id="checkPW">
                    <label>查看密碼</label>
                </div> -->
                <div class="ts fluid vertical buttons">
                <button type="button" class="ts positive basic labeled icon fluid button" id="btnLogin">
                    <i class="leaf icon"></i>
                    一般登入
                </button>
                <!-- <a class="ts primary basic labeled icon fluid button" href="" id='btnLoginFB'>
                    <i class="facebook f icon"></i>
                    ＦＢ登入
                </a> -->
                <?php
                if(!empty($authUrl)) {
                    echo '<a class="ts primary basic labeled icon fluid button" href="'.$authUrl.'">
                            <i class="facebook f icon"></i>
                            ＦＢ登入
                          </a>';
                }
                ?>
                </div>
                <div class="ts breadcrumb">
                    <a class="section" href="<?php echo base_url();?>">回到首頁</a>
                    <div class="divider"> / </div>
                    <!-- <a class="section">忘記密碼</a>
                    <div class="divider"> / </div> -->
                    <a class="section" id='aRegister'>快速註冊</a>
                </div>
                </form>
                </div>
            </div>

            <div class="ts centered borderless segment" id="register_panel" style="max-width: 600px;display:none">
                <form id="register_account_form">
                <div class="field">
                    <label>帳號</label>
                    <div class="ts left icon fluid input"> 
                        <input type="text" name="ACCOUNT">
                        <i class="user icon"></i>
                    </div>
                </div>
                <div class="field">
                    <label>姓名</label>
                    <div class="ts left icon fluid input"> 
                        <input type="text" name="NAME">
                        <i class="smile icon"></i>
                    </div>
                </div>
                <div class="field">
                    <label>信箱</label>
                    <div class="ts left icon fluid input"> 
                        <input type="text" name="EMAIL">
                        <i class="mail icon"></i>
                    </div>
                </div>
                <div class="left icon field">
                    <label>密碼</label> 
                    <div class="ts left icon fluid input">
                        <input type="password" class="password" name="PASSWORD">
                        <i class="lock icon"></i>
                    </div> 
                </div>
                <div class="left icon field">
                    <label>確認密碼</label> 
                    <div class="ts left icon fluid input">
                        <input type="password" class="password" name="CONFIRM_PASSWORD">
                        <i class="lock icon"></i>
                    </div> 
                </div> 
                <br>
                <button type="button" class="ts positive basic labeled icon fluid button" id="btnRegiter">
                    <i class="hand pointer icon"></i>
                    註冊
                </button>
                <br>
                <div class="ts breadcrumb">
                    <a class="section" href="<?php echo base_url();?>">回到首頁</a>
                    <div class="divider"> / </div>
                    <a class="section" id='aLogin'>回到登入</a>
                </div>
                </form>
            </div>
        </div>
    </div>
<script>
$('#btnLogin').click(function(){

});
$('#btnLogin').click(function(){
    AnimationUtil.startWaitingAnimation();
        $.post('<?php echo base_url();?>index.php/users/login', $('#login_form').serializeArray())
            .done(function(data){
                if(data.status == 'success'){
                    //redirect
                    //window.location.replace('<?php echo base_url();?>index.php/pages/view/search-tutorial')
                    window.location.replace(data.data);
                }else{
                    alert(data.data);
                }
            })
            .fail(function(data){
                alert("伺服器錯誤!login");
            })
            .always(function(){AnimationUtil.endWaitingAnimation();})
        return false;
});
$('#aRegister').click(function(){
    switchToRegister();
});
$('#aLogin').click(function(){
    switchToLogin();
});
$('#btnRegiter').click(function(){
    AnimationUtil.startWaitingAnimation();
        $.post('<?php echo base_url();?>index.php/users/register', $('#register_account_form').serializeArray())
            .done(function(data){
                if(data.status == 'success'){
                    //redirect
                    alert("帳號註冊成功!請記得驗證信箱!您可以先登入系統.");
                    switchToLogin();
                }else{
                    alert(data.data);
                }
            })
            .fail(function(data){
                alert("伺服器錯誤!submit");
            })
            .always(function(){AnimationUtil.endWaitingAnimation();})
        return false;
});
ts('.ts.checkbox').checkbox(function(){

     alert();
});
$(document).ready(function() {
    $('#checkPW').click(function(e) {  
      alert(1);
    });
});
$('#checkPW').click(function(){
    alert();
});
    
    function switchToRegister(){
        $('#login_panel').hide();
        $('#register_panel').show();
    }
    function switchToLogin(){
        $('#login_panel').show();
        $('#register_panel').hide();
    }
    
</script>