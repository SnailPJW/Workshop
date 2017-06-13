<script>
ts('.ts.dropdown:not(.basic)').dropdown();
// $("#alogout").click(function(){
//     logout();
// });
// $("#btnLogin").click(function(){
//     redirectToLogin();
// });
function redirectToLogin(){
    window.location.href= "<?php echo base_url().'index.php/pages/view/login'; ?>";
}
function logout(){
    window.location.href="<?php echo base_url().'index.php/users/logout'; ?>";
}
function updateOnsiteMsgCount(count){
    $('.on-site-msg-icon').empty();
    $('.on-site-msg-icon').text(count);
    if(count==0){
        $('.on-site-msg-icon').hide();
    }else{
        $('.on-site-msg-icon').show();
    }
}
function checkForMsg(){
    GenericController.get('sitemsg','checkForUnreadMsg')
            .done(function(resp){
                if(resp.status=='success'){
                    var count = resp.data;
                    updateOnsiteMsgCount(count);
                }
            });
}
$(document).ready(function(){
    <?php if(isset($page))   echo "$('#".$page."_li').addClass('active');"; ?>
    <?php if(isset($user_data)) echo 'setInterval(function(){ checkForMsg(); }, 30000);';?>
    checkForMsg();
});
</script> 