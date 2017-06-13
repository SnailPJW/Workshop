<?php
class Images extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserModel', 'userModel');
        $this->load->model('TutorialModel', 'tutorialModel');
    }
    public function uploadImage(){
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
        JSON_Util::SendSuccessResponse($row);
        
    }

}