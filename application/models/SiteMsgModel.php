<?php
class SiteMsgModel extends CI_Model
{
    public static $SiteMsgTableName = 'ON_SITE_MSG';
    //user presses load more
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserModel', 'userModel');
    }
    public function getMessageMapForUser($user){
        $this->db->where('ACCOUNT', $user);
        $q = $this->db->get(SiteMsgModel::$SiteMsgTableName);
        $r = $q->result_array();
        if($r){
            $output = array();
            foreach($r as $row){
                $msg_id = $row['MSG_ID'];
                $output[$msg_id] = $row;
            }
            return $output;
        }else{
            return array();
        }
    }
    public function markAsRead($msg_id){
        return $this->db->update(SiteMsgModel::$SiteMsgTableName, array('READ_BY_USER'=>true), array('MSG_ID'=>$msg_id));
    }
    public function msgBelongsToUser($msg_id, $user){
        $this->db->where('MSG_ID', $msg_id);
        $this->db->where('ACCOUNT', $user);
        $count = $this->db->count_all_results(SiteMsgModel::$SiteMsgTableName);
        return ($count>0);
    }
    public function getUnreadMsgCount($user){
        $this->db->where('ACCOUNT', $user);
        $this->db->where('READ_BY_USER', false);
        $count = $this->db->count_all_results(SiteMsgModel::$SiteMsgTableName);
        return $count;
    }
    public function sendMsgToAccount($user, $title, $msg, $send_email = false){
        $user_row = $this->userModel->getUserRow($user);
        return $this->sendMsgToUser($user_row, $title, $msg, $send_email);
    }
    //寄發email
    public function sendMsgToUser($user_row, $title, $msg, $send_email = false){
        $data = array(
            'ACCOUNT'=>$user_row['ACCOUNT'],
            'TITLE'=>$title,
            'MSG'=>$msg,
            'CREATE_TIME'=>date('Y-m-d H:i:s')
        );
        $insert_result = $this->db->insert(SiteMsgModel::$SiteMsgTableName, $data);
        if(!$insert_result) return false;
        if($send_email){
            $this->load->helper('email');
            $email_result = OT_Emailer::SendMail($user_row['EMAIL'], $title, $msg);
            if(!$email_result)  return false;
        }
        return true;
    }
}