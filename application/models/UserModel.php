<?php
class UserModel extends CI_Model
{
    public static $UserTableName = 'USER';
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ImageModel','imageModel');
    }
    //note outputs an IMAGE_ID=>true map
    public function getImageIdsThatExistAsUserPicture($ids){
        $this->db->select('PICTURE_ID');
        $this->db->where_in('PICTURE_ID', $ids);
        $q = $this->db->get(UserModel::$UserTableName);
        $r = $q->result_array();
        if($r){
            $output = array();
            foreach($r as $row){
                $img_id = $row['PICTURE_ID'];
                $output[$img_id] = true;
            }
            return $output;
        }else{
            return array();
        }
                
    }
    public function getMultipleEMails($accounts){
        $idx = 0;
        foreach($accounts as $acc)
        {
            if($idx==0){
                $this->db->where('ACCOUNT',$acc);
            }else{
                $this->db->or_where('ACCOUNT',$acc);
            }
            ++$idx;
        }
        $q = $this->db->get(UserModel::$UserTableName);
        $r = $q->result_array();
        $output = array();
        foreach($r as $row){
            $email = $row['EMAIL'];
            $output []= $email;
        }
        return $output;
    }
    public function getUserRealName($account){
        $q = $this->db->get_where('USER', array('ACCOUNT'=>$account));
        $r = $q->result_array();
        if(count($r)>0){
            return $r[0]['NAME'];
        }
        return false;
    }
    public function getUserEmail($account){
        $this->db->select('EMAIL');
        $q = $this->db->get_where('USER', array('ACCOUNT'=>$account));
        $r = $q->result_array();
        if(count($r)>0){
            return $r[0]['EMAIL'];
        }
        return false;
    }
    public function getAccountList(){
        $rows = $this->getAllAccounts(array('ACCOUNT'), false);
        $output = array();
        foreach($rows as $row){
            $account = $row['ACCOUNT'];
            $output[]=$account;
        }
        return $output;
    }
    //basically the same as getUserRow, except it will get the user picture's url
    public function getUserData($user){
        $row = $this->getUserRow($user);
        if($row===false) return false;
        $picture_url = $this->imageModel->getImageURL($row['PICTURE_ID']);
        if($picture_url===false) $picture_url = base_url().'asset/img/avatar.svg';
        $row['PICTURE_URL'] = $picture_url;
        return $row;
    }
    public function getUserRow($user){
        $q = $this->db->get_where(UserModel::$UserTableName, array('ACCOUNT'=>$user));
        $r = $q->result_array();
        if($r){
            $data = $r[0];
            return $data;
        }else return false;
    }
    public function updateUserData($user, $data_array){
        return $this->db->update(UserModel::$UserTableName, $data_array, array('ACCOUNT'=>$user));
    }
    public function updateUserPicture($user, $img_id){
        $user_data = $this->getUserRow($user);
        if(!$user_data) return false;
        if(strlen($user_data['PICTURE_ID'])>0){
            //delete the old picture
            $this->imageModel->deleteImage($user_data['PICTURE_ID']);
            //add the new picture to user data
            $this->updateUserData($user, array('PICTURE_ID'=>$img_id));
        }
    }
}