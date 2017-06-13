<?php
class VideoModel extends CI_Model
{
    public static $VideoTableName = 'VIDEO';
       public static $RawVideoStorePath = '/OnlineTeachVideos/raw/';
    public static $OnlineVideoStorePath = '/OnlineTeachVideos/online/';
    public static $OptimizationShellScriptPath = '/OnlineTeachVideos/process.sh';
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ImageModel','imageModel');
        $this->load->model('TutorialModel','tutorialModel');
    }
    public function updateMultipleVideos($ids, $data){
        if(!$ids) return false;
        $final_data = array();
        foreach($ids as $id){
            $row = array('VIDEO_ID'=>$id);
            $row = array_merge($row, $data);
            $final_data[]=$row;
        }
        return $this->db->update_batch(VideoModel::$VideoTableName,$final_data,'VIDEO_ID');
    }
    public function deleteVideoFile($id, $ext){
        //delete the online file if exists
        $online_path = $this->getOnlineVideoPath($id);
        if(file_exists($online_path)){
            unlink($online_path);
        }
        //delete the raw file if exists
        $raw_path = $this->getRawVideoPath($id, $ext);
        if(file_exists($raw_path)){
            unlink($raw_path);
        }
    }
    public function deleteMultipleVideos($ids){
        if(!$ids) return false;
        $this->db->where_in('VIDEO_ID',$ids);
        $q = $this->db->get(VideoModel::$VideoTableName);
        $r = $q->result_array();
        foreach($r as $row){
            $id = $row['VIDEO_ID'];
            $this->deleteVideoFile($id, $row['RAW_EXTENSION']);
        }
        //delete the rows
        $this->db->where_in('VIDEO_ID',$ids);
        return $this->db->delete(VideoModel::$VideoTableName);
    }
    public function getInvalidIds($ids, &$valid_ids){
        //see if they exist in any tutorial session records
        //returns the invalid ids and sets the $valid_ids
        $exist_map = $this->tutorialModel->getVideoIdsThatExistInSessionTable($ids);
        $valid_ids = array();
        $invalid_ids = array();
        foreach($ids as $id){
            if(array_key_exists($id, $exist_map)){
                $valid_ids[]=$id;
            }else{
                $invalid_ids[]=$id;
            }
        }
        return $invalid_ids;
    }
    public function getIdsFromRows($rows){
        $output = array();
        foreach($rows as $row){
            $output[]=$row['VIDEO_ID'];
        }
        return $output;
    }
    public function getNotValidatedVideoRowsHoursAgo($hours, $limit, $offset){
        $limit_time = date('Y-m-d H:i:s', strtotime('-'.$hours.' hour'));
        $this->db->where('CREATE_TIME<=', $limit_time);
        $where = array('VALIDATED'=>false);
        return $this->getVideoRows($where, $limit, $offset);
    }
    public function getVideoRows($where, $limit, $offset){
        $q = $this->db->get_where(VideoModel::$VideoTableName, $where, $limit, $offset);
        return $q->result_array();
    }
    public function getVideoIdToDataMap($id_array){
        if(count($id_array)<=0) return false;
        
        $idx = 0;
        foreach($id_array as $id){
            if($idx==0){
                $this->db->where('VIDEO_ID', $id_array[$idx]);
            }else{
                $this->db->or_where('VIDEO_ID', $id_array[$idx]);
            }
            ++$idx;
        }
        $q = $this->db->get(VideoModel::$VideoTableName);
        $r = $q->result_array();
        $output = array();
        foreach($r as $row){
            $row['VIDEO_URL'] = $this->getVideoUrl($row['VIDEO_ID']);
            $output[$row['VIDEO_ID']] = $row;
        }
        return $output;
    }
    public function getVideoUrl($id){
        return base_url().'index.php/videos/getVideoData/'.$id;
    }
    public function userCanUseAsTutorialSessionVideo($user, $vid_id){
        $row = $this->getVideoRowWithId(($vid_id));
        if(!$row) return false;
        if($row['OWNER']!=$user) return false;
        if($row['STATE']!='online') return false;
        return true;
    }
    public function canViewVideo($video_row, $user){//user can be false, the user might not have logged in
        $vid_id = $video_row['VIDEO_ID'];
        //see if it is a part of a tutorial and if the current user has:
        //(1) bought the tutorial, or
        //(2) the tutorial is a preview tutorial (INDEX=0)
        if($user!==false){
            if($video_row['OWNER']==$user) return true;
        }
        $row = $this->tutorialModel->getTutorialSessionRowWithVideoId($vid_id);
        if($row===false) return false;
        if($row['TUTORIAL_INDEX']==0){
            return true;//this is the preview video, anyone can watch
        }
        if($user!==false){
            return $this->tutorialModel->tutorialHasStudent($row['TUTORIAL_ID'], $user);
        }
        return false;
    }
    /*
     * creates a video placeholder, there is no file yet, but the front-end will
     * send the data in chunks later, using the videos id
     */
    public function createVideoPlaceholder($user, $ext, $file_size){
        //$video_id = $this->getLegitId($user);
        $datetime = date("Y-m-d H:i:s");
        if($this->db->insert(VideoModel::$VideoTableName, array('RAW_EXTENSION'=>$ext, 'CREATE_TIME'=>$datetime, 'OWNER'=>$user, 'RAW_FILE_SIZE'=>$file_size))){
            $video_id = $this->db->insert_id();
            $path = VideoModel::$RawVideoStorePath.$video_id.'.'.$ext;
            touch($path);
            return $video_id;
        }else return false;
    }
    private function getLegitId($user){
        $hash = '';
        while(true){
            $hash = uniqid();
            $hash = $user.$hash;
            $hash = hash('ripemd160', $hash);
            if(!$this->IdExists(($hash))){
                break;
            }
        }
        return $hash;
    }
    public function IdExists($id){
        $query = $this->db->get_where(VideoModel::$VideoTableName, array('VIDEO_ID'=> $id));
        if($query->num_rows()>0) return true;
        else return false;
    }
    public function userOwnsVideo($user,$chunk_upload_id){
        $q = $this->db->get_where(VideoModel::$VideoTableName, array('OWNER'=>$user,'VIDEO_ID'=>$chunk_upload_id));
        if($q->num_rows()>0) return true;
        else return false;
    }
    public function getVideoRow($where){
        $q = $this->db->get_where(VideoModel::$VideoTableName, $where);
        $r = $q->result_array();
        if($r){
            return $r[0];
        }else return false;
    }
    public function getVideoRowWithId($video_id){
        $row = $this->getVideoRow(array('VIDEO_ID'=>$video_id));
        return $row;
    }
    public function uploadVideoChunk($chunk, $id, $ext){
        $file_path = $this->getRawVideoPath($id, $ext);
        $r = file_put_contents($file_path, $chunk, FILE_APPEND);
        if($r===false) return -1;
        clearstatcache();
        return filesize($file_path);
    }
    public function getRawFileSizeFromFileSystem($id, $ext){
        $path = $this->getRawVideoPath($id, $ext);
        clearstatcache();
        return filesize($path);
    }
    public function getOnlineFileSizeFromFileSystem($id){
        clearstatcache();
        return filesize($this->getOnlineVideoPath($id));
    }
    public function getRawVideoPath($id, $ext){
        $path = VideoModel::$RawVideoStorePath.$id.'.'.$ext;
        return $path;
    }
    public function updateVideoRowWithId($data, $id){
        return $this->db->update(VideoModel::$VideoTableName, $data, array('VIDEO_ID'=>$id));
    }
    public function changeVideoState($id, $state){
        return $this->videoModel->updateVideoRowWithId(array('STATE'=>$state), $id);
    }
    public function getVideoLength($vid_id){
        if($vid_id<=0)  return 0;
        
        $this->db->select('VIDEO_LENGTH');
        $row = $this->getVideoRowWithId($vid_id);
        if($row===false){ 
            return 0;
        }
        return $row['VIDEO_LENGTH'];
    }
    public function optimizeVideo($video_row){
        $id = $video_row['VIDEO_ID'];
        $ext = $video_row['RAW_EXTENSION'];
        $this->changeVideoState($id, 'processing');
        $raw_filename = $id.'.'.$ext;
        $output_filename = $id.'.mp4';
        exec("php /var/www/html/OnlineTeach/index.php videos runOptScript $raw_filename $output_filename $id > /dev/null &");
        //$file_path = $this->getRawVideoPath($id, $ext);
        //$output_filepath = $this->getOnlineVideoPath($id);
        //exec('sh '.VideoModel::$OptimizationShellScriptPath.' '.$file_path.' '.$output_filepath.' '.$id.' > /dev/null &');
    }
    public function runOptScript($raw_filename, $output_filename, $id){
        $output_lines = array();
        $file_path = VideoModel::$RawVideoStorePath.$raw_filename;
        $output_filepath = VideoModel::$OnlineVideoStorePath.$output_filename;
        exec("ffmpeg -i $file_path -vf scale=-1:720 -c:v libx264 -crf 23 -preset medium -c:a copy $output_filepath 2>&1", $output_lines);
        foreach($output_lines as $line){
            $d_pos = strpos($line, 'Duration');
            if($d_pos!==false){
                $preg_output = array();
                preg_match("/(\d+):(\d{2}):(\d{2}).\d{2}/", $line, $preg_output);
                $hours = intval($preg_output[1]);
                $mins = intval($preg_output[2]);
                $secs= intval($preg_output[3]);
                $total_secs = $hours*3600 + $mins*60 + $secs;
                echo 'vid length: '. $total_secs.PHP_EOL;
                unlink($file_path);
                //update the state
                $this->updateVideoRowWithId(array('STATE'=>'online','VIDEO_LENGTH'=>$total_secs), $id);
                return true;
            }
        }
        return false;
    }
    
    public function getOnlineVideoPath($id){
        $output_filepath = VideoModel::$OnlineVideoStorePath.$id.'.mp4';
        return $output_filepath;
    }
    
}