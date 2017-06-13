<?php
class TicketModel extends CI_Model
{
    public static $TicketTableName = 'TICKET';
    //user presses load more
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserModel', 'userModel');
        $this->load->model('SiteMsgModel', 'siteMsgModel');
    }
    public function getUnansweredTickets(){
        $q = $this->db->get_where(TicketModel::$TicketTableName, array('ANSWERED'=>false));
        return $q->result_array();
    }
    public function notAnsweredTicketExists($ticket_id){
        $q = $this->db->get_where(TicketModel::$TicketTableName, array('TICKET_ID'=>$ticket_id, 'ANSWERED'=>false));
        if($q->result_array()) return true;
        else return false;
    }
    public function getTicketWithId($ticket_id){
        $q = $this->db->get_where(TicketModel::$TicketTableName, array('TICKET_ID'=>$ticket_id));
        $r = $q->result_array();
        if($r) return $r[0];
        else return false;
    }
    public function insertTicket($user, $description){
        return $this->db->insert(TicketModel::$TicketTableName, array('ACCOUNT'=>$user, 'DESCRIPTION'=>$description));
    }
    public function answerTicket($ticket_row, $ans){
        /*
        $ticket_id = $ticket_row['TICKET_ID'];
        $customer = $ticket_row['ACCOUNT'];
        
        
        $this->load->helper('email');
        $email = $this->userModel->getUserEmail($customer);
        $msg = '先前接收到您的申訴:'.PHP_EOL.$ticket_row['DESCRIPTION'].PHP_EOL.PHP_EOL.'回覆如下:'.PHP_EOL.$ans;
        OT_Emailer::SendMail($email, '您申訴的問題已經得到回覆', $msg);
        return $this->db->update(TicketModel::$TicketTableName, array('ANSWER_CONTENT'=>$ans,'ANSWERED'=>true),array('TICKET_ID'=>$ticket_id));
        */
        $ticket_id = $ticket_row['TICKET_ID'];
        $customer = $ticket_row['ACCOUNT'];
        $msg = '先前接收到您的申訴:'.PHP_EOL.$ticket_row['DESCRIPTION'].PHP_EOL.PHP_EOL.'回覆如下:'.PHP_EOL.$ans;
        $r = $this->siteMsgModel->sendMsgToAccount($customer, '您申訴的問題已經得到回覆', $msg, true);
        if(!$r) return false;
        return $this->db->update(TicketModel::$TicketTableName, array('ANSWER_CONTENT'=>$ans,'ANSWERED'=>true),array('TICKET_ID'=>$ticket_id));
    }
}