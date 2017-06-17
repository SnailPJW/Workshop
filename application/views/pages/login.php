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
                        <a class="header">學習從喜歡學習開始</a>
                    </div>
                </div>            

                <form class="ts eight wide column form" id="login_form">
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
                <br><br>
                <div class="ts fluid vertical buttons">
                <button type="button" class="ts primary basic labeled icon fluid button" id="btnLogin">
                    <i class="hand pointer icon"></i>
                    登入
                </button>
                <!-- <button class="ts primary basic labeled icon fluid button" scope="public_profile,email" onlogin="checkLoginState();">
                    <i class="facebook official icon"></i>
                    FB 登入
                </button> -->
                </div>
               <!--  <fb:login-button scope="public_profile,email" onlogin="checkLoginState();">
                </fb:login-button> -->
                <br><br>
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
// FB-Login-SKD
// This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      testAPI();
    } else {
      // The person is not logged into your app or we are unable to tell.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
  FB.init({
    appId      : '673084749544580',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.9' // use graph api version 2.8
  });

  // Now that we've initialized the JavaScript SDK, we call 
  // FB.getLoginStatus().  This function gets the state of the
  // person visiting this page and can return one of three states to
  // the callback you provide.  They can be:
  //
  // 1. Logged into your app ('connected')
  // 2. Logged into Facebook, but not your app ('not_authorized')
  // 3. Not logged into Facebook and can't tell if they are logged into
  //    your app or not.
  //
  // These three cases are handled in the callback function.

  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });

  };

  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
      console.log('Successful login for: ' + response.name);
      document.getElementById('status').innerHTML =
        'Thanks for logging in, ' + response.name + '!';
    });
  }
//end- FB-Login-SKD
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