<?php
class Tickets extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('TicketModel', 'ticketModel');
        $this->load->model('UserModel', 'userModel');
    }
    public function submitTicket(){
        $user = $this->session->userdata('ACCOUNT');
        if(!$user){
            JSON_Util::SendErrorResponse('你尚未登入!');
            return;
        }
        $description = $this->input->post('DESCRIPTION');
        if($this->ticketModel->insertTicket($user, $description)){
            JSON_Util::SendSuccessResponse('已經收到您的申訴,待站方人員回覆後將會回覆到您設定的信箱!');
            return;
        }else{
            JSON_Util::SendErrorResponse('資料庫錯誤!');
            return;
        }
    }
    public function answerTicket(){
        $user = $this->session->userdata('ACCOUNT');
        if(!$user){
            JSON_Util::SendErrorResponse('你尚未登入!');
            return;
        }
        $user_data = $this->userModel->getUserRow($user);
        if(!$user_data['ADMIN']){
            JSON_Util::SendErrorResponse('你沒有站方人員權限!');
            return;
        }
        $ans = $this->input->post('ANSWER_CONTENT');
        $ticket_id = $this->input->post('TICKET_ID');
        $ticket_row = $this->ticketModel->getTicketWithId($ticket_id);
        if(!$ticket_row){
            JSON_Util::SendSuccessResponse('該客訴不存在!');
            return;
        }
        if($ticket_row['ANSWERED']){
            JSON_Util::SendSuccessResponse('該客訴已經回應!');
            return;
        }
        if($this->ticketModel->answerTicket($ticket_row, $ans)){
            JSON_Util::SendSuccessResponse('回應成功!');
            return;
        }else{
            JSON_Util::SendErrorResponse('資料庫錯誤!');
            return;
        }
    }
}
