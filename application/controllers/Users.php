<?php
class Users extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserModel', 'userModel');
    }
    public function logout()
    {
        $userid = $this->session->userdata('ACCOUNT');
        $this->session->sess_destroy();
        header('Location: '.base_url().'index.php/pages/view/aboutWork');
    }
    public function resetWithHash()
    {
        $hash = $this->input->post('hash');
        $account = $this->input->post('account');
        $password = $this->input->post('password');
        $query = $this->db->get_where('USER', array('ACCOUNT'=>$account));
        $result = $query->result_array();
        $status = 'success';
        $data = '';
        if (sizeof($result) === 0) {
            $status = 'fail';
            $data = '帳號或者hash錯誤';
        } elseif ($password === '') {
            $status = 'fail';
            $data = '密碼不可為空';
        } elseif ((!$password) || (!$account) || (!$hash)) {
            $status = 'fail';
            $data = '資料不可為空';
        } else {
            $toks = explode(":", $result[0]["password"]);
            $h = $toks[2];
            if ($h === $hash) {
                $this->load->helper('hashsalt');
                $dbresult = $this->db->update(
                    'USER',
                    array('PASSWORD'=>create_hash($password)),
                    array('ACCOUNT'=>$account)
                );
                if (!$dbresult) {
                    $status = 'fail';
                    $data = '資料庫錯誤';
                }
            } else {
                $status = 'fail';
                $data = '帳號或者hash錯誤';
            }
        }
        header("Content-type: application/json");
        echo json_encode(array('status'=>$status, 'data'=>$data));
    }
    public function login()
    {
        $this->load->helper('hashsalt');
        $msg;
        $userid;
        $hashinfo;
        $this->session->unset_userdata('ACCOUNT');
        $account = $this->input->post("ACCOUNT");
        $password = $this->input->post("PASSWORD");
	$query = $this->db->get_where(
            'USER',
            array(
                'ACCOUNT'=>$account
            )
        );
        $result = $query->result_array();
        if(sizeof($result)==0){
            JSON_Util::SendErrorResponse('系統中無此帳號!');
            return;
        }
        $userid = $result[0]['ACCOUNT'];
        $hashinfo = $result[0]['PASSWORD'];
        if (validate_password($password, $hashinfo)) {
            $session_data = array('ACCOUNT' => "$userid");
            $last_uri = $this->session->userdata('LAST_URI');
            
            $this->session->set_userdata($session_data);
            if($last_uri)   JSON_Util::SendSuccessResponse($last_uri);
            else                JSON_Util::SendSuccessResponse (base_url().'index.php/pages/view/search-tutorial');
        }else{
            JSON_Util::SendErrorResponse('密碼錯誤');
        }
    }
    
    public function emailConfirm($account,$email_confirm_hash){
        $account = urldecode($account);
        $email_confirm_hash = urldecode($email_confirm_hash);
        /*
        $debug = array();
        $debug['ip'] = $this->input->ip_address();
        $debug['user_agent'] = $this->input->user_agent();
         */
        $q = $this->db->get_where('USER',array('ACCOUNT'=>$account, 'EMAIL_CONFIRM_HASH'=>$email_confirm_hash));
        $r = $q->result_array();
        if($r){
            //$this->db->update('USER', array('EMAIL_CONFIRMED'=>true, 'DEBUG'=>json_encode($debug)), array('ACCOUNT'=>$account));//hotmail will automatically follow the email confirm link once the email is received lol
            //i traced the machine that visited the confirm url and it was from microsoft
            //{"ip":"207.46.13.40","user_agent":"Mozilla\/5.0 (Windows NT 6.1; WOW64) AppleWebKit\/534+ (KHTML, like Gecko) BingPreview\/1.0b"}
            if($r[0]['EMAIL_CONFIRMED']==false){
                $this->db->update('USER', array('EMAIL_CONFIRMED'=>true), array('ACCOUNT'=>$account));
            }
            header('Location: '.base_url().'index.php/pages/view/show-email-confirmed');
        }else{
            header('Location: '.base_url().'index.php/pages/view/email-confirm-failed');
        }
    }
    public function updateShortIntro(){
        $user = $this->session->userdata('ACCOUNT');
        if(!$user){
            JSON_Util::SendErrorResponse('你尚未登入!');
            return;
        }
        $short_intro = $this->input->post('SHORT_INTRO');
        $short_intro = htmlspecialchars($short_intro);
        if($this->userModel->updateUserData($user, array('SHORT_INTRO'=>$short_intro))){
            JSON_Util::SendSuccessResponse($short_intro);
        }else{
            JSON_Util::SendErrorResponse('資料庫錯誤!');
        }
        
    }
    public function uploadPicture(){
        $user = $this->session->userdata('ACCOUNT');
        if(!$user){
            JSON_Util::SendErrorResponse('你尚未登入!');
            return;
        }
        //get the data
        
        $image_data = file_get_contents('php://input');
        $headers = $this->input->request_headers();
        $content_type = $headers['Content-Type'];
        if(strpos($content_type, 'image')===false){
            JSON_Util::SendErrorResponse('所選的檔案不是圖檔!');
            return;
        }
        $content_type_to_ext = array('image/jpeg'=>'jpg','image/gif'=>'gif','image/png'=>'png');
        if(!array_key_exists($content_type, $content_type_to_ext)){
            JSON_Util::SendErrorResponse('無法辨識的檔案類型!請上傳jpg,gif或者png檔案!');
            return;
        }
        if(strlen($image_data)>5000000){
            JSON_Util::SendErrorResponse('檔案不得大於5MB!');
            return;
        }
        $row = $this->imageModel->createImageFile($image_data, $content_type_to_ext[$content_type], $user);
        $url = $row['URL'];
        if(!$url){
            JSON_Util::SendErrorResponse('資料庫或檔案系統錯誤!');
            return;
        }
        $img_id = $row['IMAGE_ID'];
        //link it to the user
        $this->userModel->updateUserPicture($user, $img_id);
        
        JSON_Util::SendSuccessResponse($url);
        
    }
    public function register(){
	    $this->load->helper('hashsalt');
        $account = $this->input->post('ACCOUNT');
        $realname = $this->input->post('NAME');
        $password = $this->input->post('PASSWORD');
        $confirm = $this->input->post('CONFIRM_PASSWORD');
        $email = $this->input->post('EMAIL');
		//check if the account is already in use
        $query = $this->db->query(
            "SELECT * FROM `USER` WHERE  `ACCOUNT` = '$account'"
        );
        $result = $query->result_array();
        $response = array('data'=>'','status'=>'success');
        if (sizeof($result)!=0) {
            $response['data'] = '已經有人使用'.$account.'這個帳號.';
            $response['status'] = 'error';
        }
	    if(!$realname){
            $response['data']='真實姓名不可為空';
            $response['status'] = 'error';
        }
        if(!$email){
            $response['data'] = '信箱不可為空';
            $response['status'] = 'error';
        }
        if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $email)) {
            $response['data'] = '信箱格式錯誤 ';
            $response['status'] = 'error';
        }
        if (!preg_match ("/^[a-z0-9]+$/i", $account)){
            $response['data'] = '帳號請用英數格式';
            $response['status'] = 'error';
        }
        $q = $this->db->get_where('USER', array('EMAIL'=>$email));
        $r = $q->result_array();
        if(sizeof($r)!=0){
            $response['data'] = '已經有人使用'.$email.'這個信箱.';
            $response['status'] = 'error';
        }
        //check if the password matches confirm
        if ($password!=$confirm) {
            $response['data'] .= '密碼與確認密碼不符.';
            $response['status'] = 'error';
        }
        if ($password==''||$confirm=='') {
            $response['data'] .= '密碼/確認密碼不可為空.';
            $response['status'] = 'error';
        }
        if ($account=='') {
            $response['data'] .= '帳號不可為空.';
            $response['status'] = 'error';
        }
        if ($response['status']==='error') {
            header("Content-type: application/json");
            echo json_encode($response);
            return;
        }

	$password = create_hash($password);
        //try to send mail
        
        $this->load->helper('email');
        $email_confirm_hash = hash('ripemd160', $account);
        $confirm_url = base_url().'index.php/users/emailConfirm/'.urlencode($account).'/'.urlencode($email_confirm_hash);
        $msg = '我們已經收到您申請帳號的請求，請點選以下連結來驗證email帳號: '.$confirm_url;
        //$msg = '我們已經收到您申請帳號的請求，請點選以下連結來驗證email帳號:　<a href="'.$confirm_url.'">'.base_url().'index.php/users/emailConfirm/'.urlencode($account).'/'.urlencode($email_confirm_hash).'</a>';
                
        
        $mail_result = OT_Emailer::SendMail($email, '請驗證線上教學系統信箱', $msg);
        if($mail_result === true){
            $this->db->insert('USER', array('ACCOUNT'=>$account, 'NAME'=>$realname,'EMAIL'=>$email, 'PASSWORD'=>$password, 'EMAIL_CONFIRMED'=>false, 'EMAIL_CONFIRM_HASH'=>$email_confirm_hash));
            $query = $this->db->query(
                "SELECT * FROM `USER` WHERE  `ACCOUNT` = '$account'"
            );

            $result = $query->result_array();
            if (sizeof($result)==0) {
                $response['data'] .= '寫入資料庫失敗，請重試\n';
                $response['status'] = 'error';
            }
            if ($response['status']=='success') {
                $response['data'] .= '帳號申請成功，請去登記的信箱收取郵件，以驗證信箱';
                $response['status'] = 'success';
            }
        }else{
            $response['status'] = 'error';
            $response['data'] = '無法寄出郵件至該信箱';
        }
	header("Content-type: application/json");
        echo json_encode($response);
    }
}
