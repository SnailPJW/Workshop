<?php
class ImageModel extends CI_Model
{
    public static $ImageTableName = 'IMAGE';
    public static $ImageDirectoryPath = "/var/www/html/ntutv01/user_upload/image/";
    public function __construct()
    {
        parent::__construct();
        $this->load->model('TutorialModel','tutorialModel');
        $this->load->model('UserModel','userModel');
    }
    
    public function userCanUseAsTutorialImage($user, $img_id){
        $row = $this->getImageRowWithId(($img_id));
        if(!$row) return false;
        if($row['OWNER']!=$user) return false;
        return true;
    }
    public function getImageRowWithId($img_id){
        $q = $this->db->get_where(ImageModel::$ImageTableName, array('IMAGE_ID'=>$img_id));
        $r = $q->result_array();
        if($r){
            return $r[0];
        }else return false;
    }
    public function getImageURL($image_id){
        $q = $this->db->get_where(ImageModel::$ImageTableName, array('IMAGE_ID'=>$image_id));
        $r = $q->result_array();
        if($r){
            return $r[0]['URL'];
        }else return false;
    }
    public function getImageUrlOrPlaceholder($image_id){
        $url = $this->getImageURL($image_id);
        if($url === false) return base_url ().'asset/img/question.jpg';
        else return $url;
    }
    public function updateMultipleImages($ids, $data){
        if(!$ids) return false;
        $final_data = array();
        foreach($ids as $id){
            $row = array('IMAGE_ID'=>$id);
            $row = array_merge($row, $data);
            $final_data[]=$row;
        }
        return $this->db->update_batch(ImageModel::$ImageTableName,$final_data,'IMAGE_ID');
    }
    public function deleteMultipleImages($ids){
        if(!$ids) return false;
        $this->db->select('URL');
        $this->db->where_in('IMAGE_ID',$ids);
        
        $q = $this->db->get(ImageModel::$ImageTableName);
        $r = $q->result_array();
        if($r){
            foreach($r as $row){
                $url = $row['URL'];
                if($url){
                    $path = $this->URLToSystemPath($url);
                    if(file_exists($path)){
                        unlink($path);
                    }
                }
            }
            $this->db->where_in('IMAGE_ID',$ids);
            return $this->db->delete(ImageModel::$ImageTableName);
        }else{
            return false;
        }
    }
    
    public function deleteImage($image_id){
        $url=$this->getImageURL($image_id);
        if(!$url) return false;
        $path = $this->URLToSystemPath($url);
        if(file_exists($path)){
            unlink($path);
        }
        return $this->db->delete(ImageModel::$ImageTableName, array('IMAGE_ID'=>$image_id));
    }
    public function getNotValidatedImagesCreatedHoursAgo($hours, $limit, $offset){
        $limit_time = date('Y-m-d H:i:s', strtotime('-'.$hours.' hour'));
        $this->db->where('CREATE_TIME<=', $limit_time);
        $this->db->where('VALIDATED',false);
        $q = $this->db->get(ImageModel::$ImageTableName,$limit,$offset);
        return $q->result_array();
    }
    /*
    input format
    array(
        'VIDEO_ID'=>,
        'URL',...
    )
    output formats: id array
    */
    
    public function getInvalidImageIds($img_rows, &$valid_ids){
        $ids = $this->getIdsFromRows($img_rows);
        $id_map = $this->userModel->getImageIdsThatExistAsUserPicture($ids);
        //remove ids that exist as key in id_map
        $processed_rows = array();
        foreach($img_rows as $row){
            $img_id = $row['IMAGE_ID'];
            if(!array_key_exists($img_id, $id_map)){
                $processed_rows[]=$row;
            }
        }
        $id_map2 = $this->tutorialModel->getImageIdsThatExistInTutorial($processed_rows);
        $invalid_ids = array();
        $valid_ids = array();
        foreach($ids as $id){
            if(array_key_exists($id, $id_map)||array_key_exists($id, $id_map2)){
                $valid_ids[]=$id;
            }else{
                $invalid_ids[]=$id;
            }
        }
        return $invalid_ids;
    }
    public function getIdsFromRows($rows){
        $output = array();
        if(!$rows) return $output;
        foreach($rows as $row){
            $output[]=$row['IMAGE_ID'];
        }
        return $output;
    }
    public function createImageFile($image_data, $extension, $owner){//returns the inserted row
        $file_name = $this->getLegitFileName($extension);
        if(file_put_contents($file_name, $image_data)){
            $url = $this->systemPathToURL($file_name);
            $this->db->insert(ImageModel::$ImageTableName, array('URL'=>$url,'OWNER'=>$owner,'CREATE_TIME'=>date('Y-m-d H:i:s')));
            $q = $this->db->get_where(ImageModel::$ImageTableName, array('URL'=>$url));
            $r = $q->result_array();
            if($r){
                return $r[0];
            }else return false;
        }else return false;
        
    }
    public function systemPathToURL($path){
        $url = str_replace(ImageModel::$ImageDirectoryPath, base_url().'user_upload/image/', $path);
        return $url;
    }
    public function URLToSystemPath($url){
        $path = str_replace(base_url().'user_upload/image/', ImageModel::$ImageDirectoryPath, $url);
        return $path;
    }
    public function getLegitFileName($extension){
        $full_path = '';
        while(true){
            $hash = uniqid();
            $full_name = $hash.'.'.$extension;
            $full_path = ImageModel::$ImageDirectoryPath.$full_name;
            if(!file_exists($full_path)) break;
        }
        return $full_path;
    }

}