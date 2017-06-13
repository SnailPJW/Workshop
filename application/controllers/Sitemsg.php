<?php
class Sitemsg extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserModel', 'userModel');
        $this->load->model('TutorialModel', 'tutorialModel');
        $this->load->model('TicketModel', 'ticketModel');
        $this->load->model('SiteMsgModel', 'siteMsgModel');
    }
    public function checkForUnreadMsg(){
        //returns the new msg count
        $user = $this->session->userdata('ACCOUNT');
        if(!$user){
            JSON_Util::SendErrorResponse('你尚未登入!');
            return;
        }
        $num = $this->siteMsgModel->getUnreadMsgCount($user);
        JSON_Util::SendSuccessResponse($num);
    }
    public function markAsRead(){
        $user = $this->session->userdata('ACCOUNT');
        if(!$user){
            JSON_Util::SendErrorResponse('你尚未登入!');
            return;
        }
        $msg_id = $this->input->post('MSG_ID');
        //see if the msg id really belongs to the user
        if(!$this->siteMsgModel->msgBelongsToUser($msg_id, $user)){
            JSON_Util::SendErrorResponse('這個訊息不屬於你!');
            return;
        }
        if($this->siteMsgModel->markAsRead($msg_id)){
            JSON_Util::SendSuccessResponse('標記已讀!');
        }else{
            JSON_Util::SendErrorResponse('資料庫錯誤!');
            return;
        }
    }
}