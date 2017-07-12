<?php
class TutorialModel extends CI_Model
{
    public static $TutorialTableName = 'TUTORIAL';
    public static $WishingTableName = 'TUTORIAL_WISHING';
    public static $WishingSessionTableName = 'WISHING_SESSION';
    public static $TutorialSessionTableName = 'TUTORIAL_SESSION';
    public static $TutorialStudentTableName = 'TUTORIAL_STUDENT';
    public static $TutorialSearchSubtabLimit = 20;//tutorials to load each time the
    //user presses load more
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ImageModel','imageModel');
        $this->load->model('VideoModel','videoModel');
        $this->load->model('SiteMsgModel', 'siteMsgModel');
    }
    public function getTutorialRowsWithState($state){
        return $this->getTutorialRows(array('STATE'=>$state));
    }
    public function getTutorialRows($where){
        $q = $this->db->get_where(TutorialModel::$TutorialTableName, $where);
        return $q->result_array();
    }
    public function updateRaisingFundTutorials(){
        $this->db->select('TUTORIAL_ID, FUNDING_START_TIME, TEACHER_ACCOUNT, PREPARE_DAYS, STUDENT_COUNT, REQ_STUDENT_COUNT');
        $rows = $this->getTutorialRowsWithState('raising_funds');
        
        foreach($rows as $row){
            //see if in can be turned to prepare
            //if so turn to prepare
            //if not, turn to fail and add reason
            $row = $this->addCourseFundRaisingDeadline($row);//'COURSE_FUND_RAISING_DEADLINE
            $now = strtotime("now");
            $deadline = strtotime($row['COURSE_FUND_RAISING_DEADLINE']);
            /*
            echo 'id = '.$row['TUTORIAL_ID'].PHP_EOL;
            echo 'now = '.date('Y-m-d H:i:s',$now).' deadline = '.date('Y-m-d H:i:s',$deadline).PHP_EOL;
             */
            if($now>$deadline){
                //see if the criteria is met
                if($row['STUDENT_COUNT']>=$row['REQ_STUDENT_COUNT']){
                    //turn to prepare
                    $this->enterPrepareStage($row['TUTORIAL_ID']);
                }else{
                    //turn to failed
                    $this->enterFailedStage($row['TUTORIAL_ID'], '募資未達目標人數.');
                }
            }
        }
    }
    
    public function updatePrepareTutorials(){
        $this->db->select('TUTORIAL_ID, FUNDING_START_TIME, TEACHER_ACCOUNT, PREPARE_DAYS');
        $rows = $this->getTutorialRowsWithState('prepare');
        foreach($rows as $row){
            //see if in can be turned to started
            //if so turn to started
            //if not, turn to fail and add reason
            $row = $this->addCoursePresumedDateTime($row);//'COURSE_FUND_RAISING_DEADLINE
            $now = strtotime("now");
            $deadline = strtotime($row['COURSE_PRESUMED_DATETIME']);
            $tut_id = $row['TUTORIAL_ID'];
            /*
            echo 'id = '.$row['TUTORIAL_ID'].PHP_EOL;
            echo 'now = '.date('Y-m-d H:i:s',$now).' deadline = '.date('Y-m-d H:i:s',$deadline).PHP_EOL;
             */
            if($now>$deadline){
                //see if the criteria is met
                if($this->canTurnTutorialOnline($tut_id)){
                    //turn to started
                    $this->turnTutorialOnline($tut_id);
                }else{
                    //turn to failed
                    $this->enterFailedStage($tut_id, '未在期限內完成備課!');
                }
            }
        }
    }
    public function getTutorialSessionRowWithId($session_id){
        $q = $this->db->get_where(TutorialModel::$TutorialSessionTableName, array('TUTORIAL_SESSION_ID'=>$session_id));
        $r = $q->result_array();
        if($r){
            return $r[0];
        }else return false;
    }
    public function deleteVideoOfSession($session_id){
        $row = $this->getTutorialSessionRowWithId($session_id);
        if(!$row) return false;
        if($row['VIDEO_ID']<=0) return false;
        return $this->videoModel->deleteMultipleVideos(array($row['VIDEO_ID']));
    }
    //note: outputs an 'IMAGE_ID'=> true map
    public function getImageIdsThatExistInTutorial($img_rows){
        if(!$img_rows) return array();
        $this->db->select('IMAGE_ID');
        $img_ids = $this->imageModel->getIdsFromRows($img_rows);
        $this->db->where_in('IMAGE_ID', $img_ids);
        $q = $this->db->get(TutorialModel::$TutorialTableName);
        $r = $q->result_array();
        $output = array();
        foreach($r as $row){
            $output[$row['IMAGE_ID']] = true;
        }
        
        foreach($img_rows as $row){
            if(array_key_exists($row['IMAGE_ID'], $output)){
                continue;//already exists in the tutorial image
            }else{
                //see if the url exists in the introduction section of a tutorial teached by the owner
                $owner = $row['OWNER'];
                $url = $row['URL'];
                $img_id = $row['IMAGE_ID'];
                $this->db->select('TUTORIAL_ID');
                $this->db->like('INTRODUCTION', $url);
                $q = $this->db->get_where(TutorialModel::$TutorialTableName, array('TEACHER_ACCOUNT'=>$owner), 1, 0);
                if ($q->num_rows() > 0){
                    $output[$img_id] = true;
                }
            }
        }
        return $output;
    }
    //note: outputs an id map
    public function getVideoIdsThatExistInSessionTable($vid_ids){
        $this->db->select('VIDEO_ID');
        $this->db->where_in('VIDEO_ID',$vid_ids);
        $q = $this->db->get(TutorialModel::$TutorialSessionTableName);
        $r = $q->result_array();
        $output = array();
        foreach($r as $row){
            $output[$row['VIDEO_ID']] = true;
        }
        return $output;
    }
    public function canEnterPrepareStage($tut_id){
        $this->db->select('STATE, STUDENT_COUNT, REQ_STUDENT_COUNT');
        $row = $this->getTutorialRow($tut_id);
        $state = $row['STATE'];
        if($state!='raising_funds'){
            return false;
        }
        if($row['STUDENT_COUNT']>=$row['REQ_STUDENT_COUNT']){
            return true;
        }
        return false;
    }
    public function enterPrepareStage($tut_id){
        $this->sendMsgToAllPartiesOfTutorialForCoursePrepare($tut_id);
        return $this->updateTutorialState($tut_id, 'prepare');
    }
    public function enterFailedStage($tut_id, $reason){
        $this->sendMsgToAllPartiesOfTutorialForCourseFailed($tut_id, $reason);
        return $this->updateTutorialData($tut_id, array('STATE'=>'failed','FAILED_REASON'=>$reason));
    }
    public function computeTutorialCourseLength($tut_id){
        $sessions = $this->getTutorialSessionsWithVideoData($tut_id);
        $total_length = 0;
        foreach($sessions as $s){
            $vid_data = $s['VIDEO_DATA'];
            if($vid_data){
                $total_length += $vid_data['VIDEO_LENGTH'];
            }
        }
        return $total_length;
    }
    public function turnTutorialOnline($tut_id){
        //get the total video length
        $course_length = $this->computeTutorialCourseLength($tut_id);
        $result = $this->updateTutorialData($tut_id, array('STATE'=>'started','COURSE_LENGTH'=>$course_length));
        //$result = $this->updateTutorialState($tut_id, 'started');
        //send e-mails to students who pre-ordered
        if($result)
        {
            $this->sendMsgToAllPartiesOfTutorialForCourseOpening($tut_id);
        }
        return $result;
    }
    public function canTurnTutorialOnline($tut_id){
        //see if all videos are online
        $tut_row = $this->getTutorialRow($tut_id);
        if($tut_row===false) return false;
        else if($tut_row['STATE']!='prepare')   return false;
        
        $rows = $this->getTutorialSessionsWithVideoData($tut_id);
        if(count($rows)<=1) return false;
        $all_online = true;
        foreach($rows as $row){
            if($row['VIDEO_DATA']==null){
                $all_online = false;
                break;
            }else if($row['VIDEO_DATA']['STATE']!='online'){
                $all_online = false;
                break;
            }
        }
        if(!$all_online) return false;
        return true;
    }
    public function getSessionTutorialId($session_id){
        $this->db->select('TUTORIAL_ID');
        $q = $this->db->get_where(TutorialModel::$TutorialSessionTableName, array('TUTORIAL_SESSION_ID'=>$session_id));
        $r = $q->result_array();
        if($r){
            return $r[0]['TUTORIAL_ID'];
        }else{
            return false;
        }
    }
    public function updateTutorialSessionVideoId($tutorial_session_id, $vid_id){
        return $this->updateTutorialSessionData($tutorial_session_id, array('VIDEO_ID'=>$vid_id));
    }
    public function updateTutorialSessionData($tutorial_session_id, $data){
        return $this->db->update(TutorialModel::$TutorialSessionTableName, $data, array('TUTORIAL_SESSION_ID'=>$tutorial_session_id));
    }
    public function userCanEditTutorial($user, $tutorial_id){
        $this->db->select('TEACHER_ACCOUNT');
        $q = $this->db->get_where(TutorialModel::$TutorialTableName, array('TUTORIAL_ID'=>$tutorial_id));
        $r = $q->result_array();
        if(!$r) return false;
        $row = $r[0];
        return ($row['TEACHER_ACCOUNT'] == $user);
    }
    public function userCanEditSession($user, $tutorial_session_id){
        $this->db->select('TUTORIAL_ID');
        $q = $this->db->get_where(TutorialModel::$TutorialSessionTableName, array('TUTORIAL_SESSION_ID'=>$tutorial_session_id));
        $r = $q->result_array();
        if(!$r) return false;
        $row = $r[0];
        $tut_id = $row['TUTORIAL_ID'];
        $this->db->select('TEACHER_ACCOUNT');
        $q = $this->db->get_where(TutorialModel::$TutorialTableName, array('TUTORIAL_ID'=>$tut_id));
        $r = $q->result_array();
        if(!$r) return false;
        $row = $r[0];
        if($row['TEACHER_ACCOUNT']==$user) return true;
        else return false;
    }
    public function sendMsgToAllPartiesOfTutorialForCoursePrepare($tut_id){
        $this->db->select('TUTORIAL_ID, TEACHER_ACCOUNT, TITLE, FUNDING_START_TIME, PREPARE_DAYS');
        $tut_data= $this->getTutorialRow($tut_id);
        $tut_data = $this->addCoursePresumedDateTime($tut_data);
        $course_date = $tut_data['COURSE_PRESUMED_DATETIME'];
        $teacher_data = $this->userModel->getUserRow($tut_data['TEACHER_ACCOUNT']);
        $course_url = base_url().'index.php/pages/tutorials/'.$tut_id;
        $msg = '您預購的課程 '.$tut_data['TITLE'].'(由'.$teacher_data['NAME'].'老師主講)募資成功,正式進入老師備課階段,此課程將會在 '.$course_date.' 前開課!';
        $title = '您預購的課程 '.$tut_data['TITLE'].' 進入老師備課階段';
        $this->sendMsgToAllStudentsOfTutorialRow($tut_data, $title, $msg);
        $this->siteMsgModel->sendMsgToUser($teacher_data, '您開的課程 '.$tut_data['TITLE'].' 進入備課階段!', '您開的課程:'.$tut_data['TITLE'].' 募資成功,正式進入備課階段，此課程將會在 '.$course_date.' 前開課.請在此時程之前完成所有單元影片上傳.', true);
    }
    public function sendMsgToAllPartiesOfTutorialForCourseFailed($tut_id, $reason){
        $this->db->select('TUTORIAL_ID, TEACHER_ACCOUNT, TITLE');
        $tut_data= $this->getTutorialRow($tut_id);
        $teacher_data = $this->userModel->getUserRow($tut_data['TEACHER_ACCOUNT']);
        $course_url = base_url().'index.php/pages/tutorials/'.$tut_id;
        $msg = '您預購的課程 '.$tut_data['TITLE'].'(由'.$teacher_data['NAME'].'老師主講)開課失敗，原因為:'.$reason;
        $title = '您預購的課程 '.$tut_data['TITLE'].' 開課失敗';
        $this->sendMsgToAllStudentsOfTutorialRow($tut_data, $title, $msg);
        $this->siteMsgModel->sendMsgToUser($teacher_data, '您開的課程 '.$tut_data['TITLE'].' 已經開課失敗!', '您開的課:'.$tut_data['TITLE'].' 開課失敗，原因為:'.$reason, true);
    }
    public function sendMsgToAllPartiesOfTutorialForCourseOpening($tut_id){
        $this->db->select('TUTORIAL_ID, TEACHER_ACCOUNT, TITLE');
        $tut_data= $this->getTutorialRow($tut_id);
        $teacher_data = $this->userModel->getUserRow($tut_data['TEACHER_ACCOUNT']);
        $course_url = base_url().'index.php/pages/tutorials/'.$tut_id;
        $msg = '您預購的課程 '.$tut_data['TITLE'].'(由'.$teacher_data['NAME'].'老師主講)已經開課，請點選以下連結觀看: '.$course_url;
        $title = '您預購的課程 '.$tut_data['TITLE'].' 已經正式開課';
        $this->sendMsgToAllStudentsOfTutorialRow($tut_data, $title, $msg);
        $this->siteMsgModel->sendMsgToUser($teacher_data, '您開的課程 '.$tut_data['TITLE'].' 已經正式開課!', '您開的課:'.$tut_data['TITLE'].' 已經開課，請點選以下連結觀看: '.$course_url, true);
    }
    public function sendMsgToAllStudentsOfTutorialForCourseOpening($tut_id){
        $this->db->select('TUTORIAL_ID, TEACHER_ACCOUNT, TITLE');
        $tut_data= $this->getTutorialRow($tut_id);
        $teacher_data = $this->userModel->getUserRow($tut_data['TEACHER_ACCOUNT']);
        $course_url = base_url().'index.php/pages/tutorials/'.$tut_id;
        $msg = '您預購的課程 '.$tut_data['TITLE'].'(由'.$teacher_data['NAME'].'老師主講)已經開課，請點選以下連結觀看: '.$course_url;
        $title = '您預購的課程 '.$tut_data['TITLE'].' 已經正式開課';
        $this->sendMsgToAllStudentsOfTutorialRow($tut_data, $title, $msg);
    }
    public function sendMsgToTeacherOfTutorialId($tut_id, $title, $msg){
        $this->db->select('TEACHER_ACCOUNT');
        $tut_data = $this->getTutorialRow($tut_id);
        $this->siteMsgModel->sendMsgToAccount($tut_data['TEACHER_ACCOUNT'], $title, $msg, true);
    }
    public function sendMsgToAllStudentsOfTutorialRow($tut_data, $title, $msg){
        $tut_id = $tut_data['TUTORIAL_ID'];
        $students = $this->getAllStudentsForTutorial($tut_id);
        foreach($students as $s){
            $acc = $s['STUDENT_ACCOUNT'];
            $this->siteMsgModel->sendMsgToAccount($acc, $title, $msg, true);
        }
    }
    
    public function sendMailToAllStudentsOfTutorialForCourseOpening($tut_id){
        $this->db->select('TUTORIAL_ID, TEACHER_ACCOUNT, TITLE');
        $tut_data= $this->getTutorialRow($tut_id);
        $teacher_data = $this->userModel->getUserRow($tut_data['TEACHER_ACCOUNT']);
        $course_url = base_url().'index.php/pages/tutorials/'.$tut_id;
        $msg = '您預購的課程 '.$tut_data['TITLE'].'(由'.$teacher_data['NAME'].'老師主講)已經開課，請點選以下連結觀看: '.$course_url;
        $title = '您預購的課程 '.$tut_data['TITLE'].' 已經正式開課';
        $this->sendMailToAllStudentsOfTutorialRow($tut_data, $title, $msg);
    }
    public function sendMailToAllStudentsOfTutorialId($tut_id, $title, $msg){
        $tut_data= $this->getTutorialRow($tut_id);
        $this->sendMailToAllStudentsOfTutorialRow($tut_data, $title, $msg);
    }
    public function sendMailToAllStudentsOfTutorialRow($tut_data, $title, $msg){
        $tut_id = $tut_data['TUTORIAL_ID'];
        $students = $this->getAllStudentsForTutorial($tut_id);
        $accounts = array();
        foreach($students as $s){
            $acc = $s['STUDENT_ACCOUNT'];
            $accounts[]=$acc;
        }
        $emails = $this->userModel->getMultipleEMails($accounts);
        $this->load->helper('email');
        
        foreach($emails as $email){
            $mail_result = OT_Emailer::SendMail($email, $title, $msg);
        }
        
    }
    public function getAllStudentsForTutorial($tut_id){
        $q = $this->db->get_where(TutorialModel::$TutorialStudentTableName, array('TUTORIAL_ID'=>$tut_id));
        $r = $q->result_array();
        if($r){
            return $r;
        }else return false;
    }
    public function tutorialHasStudent($tutorial_id, $user){
        $q = $this->db->get_where(TutorialModel::$TutorialStudentTableName, array('STUDENT_ACCOUNT'=>$user, 'TUTORIAL_ID'=>$tutorial_id));
        $r = $q->result_array();
        if($r){
            return true;
        }else{
            return false;
        }
    }
    public function updateTutorialStudentCount($tut_id){
        $q = $this->db->get_where(TutorialModel::$TutorialStudentTableName, array('TUTORIAL_ID'=>$tut_id));
        $r = $q->result_array();
        if($r){
            $count = count($r);
            $this->updateTutorialData($tut_id, array('STUDENT_COUNT'=>$count));
            return $count;
        }
        return -1;
    }
    public function addBuyRecord($tut_id, $user, $buy_type){
        $status = $this->db->insert(TutorialModel::$TutorialStudentTableName, array('STUDENT_ACCOUNT'=>$user, 'TUTORIAL_ID'=>$tut_id,'PURCHASE_TYPE'=>$buy_type));
        $this->updateTutorialStudentCount($tut_id);
        return $status;
    }
    public function getTutorialSessionsWithVideoData($tut_id){
        $sessions = $this->getTutorialSessions($tut_id);
        if($sessions===false) return false;
        $sessions = $this->getVideoData($sessions);
        return $sessions;
    }
    public function getTutorialSessions($tut_id){
        $this->db->order_by("TUTORIAL_INDEX", "asc"); 
        $q = $this->db->get_where(TutorialModel::$TutorialSessionTableName, array('TUTORIAL_ID'=>$tut_id));
        $r = $q->result_array();
        if($r){
            return $r;
        }else return false;
    }
    private function fillTutorialSessionsWithVideoUrl($rows){
        $output = array();
        foreach($rows as $row){
            $output []= $this->fillTutorialSessionRowWithVideoUrl($row);
        }
        return $output;
    }
    private function fillTutorialSessionRowWithVideoUrl($row){
        if(array_key_exists('VIDEO_ID', $row)){
            $id = $row['VIDEO_ID'];
            if($id>0){
                $row['VIDEO_URL'] = $this->videoModel->getVideoUrl($id);
            }else{
                $row['VIDEO_URL'] = '';
            }
        }
        return $row;
    }
    
    public function getTutorialDataForFullRender($tut_id, $get_video_data = false){
        $row = $this->getTutorialRow($tut_id);
        //var_dump($row);
        if($row === false) return false;
        $row = $this->addTutorialIconAddFieldsSingle($row);
        //var_dump($row);
        //get the sessions and their video urls(if exist)
        $sessions = $this->getTutorialSessions($tut_id);
        if($sessions === false) return false;
        $sessions = $this->fillTutorialSessionsWithVideoUrl($sessions);
        $row['SESSIONS'] = $sessions;
        //get teacher data
        $teacher = $row['TEACHER_ACCOUNT'];
        $teacher = $this->userModel->getUserData($teacher);
        $row['TEACHER_DATA'] = $teacher;
        //add COURSE_PRESUMED_DATE
        $row = $this->addCoursePresumedDate($row);
        if($get_video_data){
            $row['SESSIONS'] = $this->getVideoData($row['SESSIONS']);            
        }
        return $row;
    }
    
    public function getVideoData($sessions){
        $video_ids = array();
        foreach($sessions as $s){
            $vid = $s['VIDEO_ID'];
            if($vid>0){
                $video_ids[]=$vid;
            }
        }
        $vid_to_data = $this->videoModel->getVideoIdToDataMap($video_ids);
        
        $output = array();
        foreach($sessions as $s){
            $vid = $s['VIDEO_ID'];
            $data = null;
            if(array_key_exists($vid, $vid_to_data)){
                $data = $vid_to_data[$vid];
            }
            $s['VIDEO_DATA'] = $data;
            $output[]=$s;
        }
        return $output;
    }
    private function addCourseFundRaisingDeadline($row){
        $course_presumed_date = new DateTime($row['FUNDING_START_TIME']);
        $course_presumed_date->add(new DateInterval('P30D'));
        $row['COURSE_FUND_RAISING_DEADLINE'] = $course_presumed_date->format('Y-m-d H:i:s');
        return $row;
    }
    private function addCoursePresumedDateTime($row){
        $course_presumed_date = new DateTime($row['FUNDING_START_TIME']);
        $course_presumed_date->add(new DateInterval('P'.($row['PREPARE_DAYS']+30).'D'));
        $row['COURSE_PRESUMED_DATETIME'] = $course_presumed_date->format('Y-m-d H:i:s');
        return $row;
    }
    private function addCoursePresumedDate($row){
        $course_presumed_date = new DateTime($row['FUNDING_START_TIME']);
        $course_presumed_date->add(new DateInterval('P'.($row['PREPARE_DAYS']+30).'D'));
        $row['COURSE_PRESUMED_DATE'] = $course_presumed_date->format('Y-m-d');
        return $row;
    }
    private function selectTutorialIconNeededFields(){
        $this->db->select('TUTORIAL_ID, TEACHER_ACCOUNT, TITLE, SHORT_INTRO, STUDENT_COUNT, IMAGE_ID, DISCOUNT_REQ_PRICE, REQ_PRICE, FUNDING_START_TIME, PREPARE_DAYS, CREATE_TIME, FUNDING_START_TIME, STATE, REQ_STUDENT_COUNT, FAILED_REASON, CATEGORY');
        
    }
    private function OrLikeMultiple($col_names, $match_str){
        $i=0;
        foreach($col_names as $col){
            if($i==0){
                $this->db->like($col, $match_str);
            }else{
                $this->db->or_like($col, $match_str);
            }
            ++$i;
        }        
    }
    /*
     * returns some meta data of the tutorials, and add some fields for rendering
     * such as the 'TEACHER_PICTURE_URL' and 'TUTORIAL_IMAGE_URL'
     * 
     */
    public function searchForTutorial($state, $keyword, $subtab_idx = 0){
        $offset = $subtab_idx * TutorialModel::$TutorialSearchSubtabLimit;
        if(strlen($keyword)==0){
            return $this->searchTutorialWithState($state, $offset);
        }
        //  $match_col_names = array(
        //      'TEACHER_ACCOUNT','TITLE','SHORT_INTRO','COURSE_OUTPUT','NEEDED_ITEMS','REQ_KNOWLEDGE','CATEGORY','STATE'
        //  );
        // $this->selectTutorialIconNeededFields();
        // if($state!==false){
        //     $this->db->where('STATE', $state);
        // }
        
        // $this->OrLikeMultiple($match_col_names, $keyword);
        
        
        $sql = "SELECT * FROM (SELECT * FROM TUTORIAL WHERE STATE='".$state."') AS T1 WHERE (
        TEACHER_ACCOUNT LIKE '%".$keyword."%' OR 
        TITLE LIKE '%".$keyword."%' OR 
        SHORT_INTRO LIKE '%".$keyword."%' OR 
        COURSE_OUTPUT LIKE '%".$keyword."%' OR 
        NEEDED_ITEMS LIKE '%".$keyword."%' OR 
        REQ_KNOWLEDGE LIKE '%".$keyword."%' OR 
        CATEGORY LIKE '%".$keyword."%')";         

        //$this->db->select($sql); 
        //$q = $this->db->get(TutorialModel::$TutorialTableName, TutorialModel::$TutorialSearchSubtabLimit, $offset);
        $q = $this->db->query($sql);                
        
        $r = $q->result_array();
        if($r){
            return $this->addTutorialIconAddFields($r);
        }else return false;
    }
    public function searchTutorialWithState($state, $offset = 0){
        //$this->selectTutorialIconNeededFields();
        if($state!==false){  
            $q = $this->db->get_where(TutorialModel::$TutorialTableName, array('STATE'=>$state), TutorialModel::$TutorialSearchSubtabLimit, $offset);
        }
        else {
            $q=$this->db->get(TutorialModel::$TutorialTableName, TutorialModel::$TutorialSearchSubtabLimit, $offset);
        }
        $r = $q->result_array();
        if($r){
            return $this->addTutorialIconAddFields($r);
        }else return false;
    }
    public function getBoughtTutorialStudentRecordsForUser($user){
        $q = $this->db->get_where(TutorialModel::$TutorialStudentTableName, array('STUDENT_ACCOUNT'=>$user));
        $r = $q->result_array();
        return $r;
    }
    public function getBoughtTutorialIdsForStudent($user){
        $r = $this->getBoughtTutorialStudentRecordsForUser($user);
        $output = array();
        foreach($r as $row){
            $output[] = $row['TUTORIAL_ID'];
        }
        return $output;
    }
    public function getBoughtTutorialsIconData($user){
        $ids = $this->getBoughtTutorialIdsForStudent($user);
        if(count($ids)<=0) return array();
        $this->selectTutorialIconNeededFields();
        $this->db->where_in('TUTORIAL_ID',$ids);
        $q = $this->db->get(TutorialModel::$TutorialTableName);
        $r = $q->result_array();
        if($r){
            return $this->addTutorialIconAddFields($r);
        }else{
            return array();
        }
                
    }
    public function getOpenedTutorialsIconData($user){
        $this->selectTutorialIconNeededFields();
        $q = $this->db->get_where(TutorialModel::$TutorialTableName, array('TEACHER_ACCOUNT'=>$user));
        $r = $q->result_array();
        if($r){
            return $this->addTutorialIconAddFields($r);
        }else return array();
    }
    public function addTutorialIconAddFields($rows){
        $output = array();
        foreach($rows as $row){
            $output[]=$this->addTutorialIconAddFieldsSingle($row);
        }
        return $output;
    }
    public function addTutorialIconAddFieldsSingle($row){
        //get tutorial img url
        $img_id = $row['IMAGE_ID'];
        $url = $this->imageModel->getImageUrlOrPlaceholder($img_id);
        $row['TUTORIAL_IMAGE_URL'] = $url;
        //get teacher image url
        $teacher_data = $this->userModel->getUserData($row['TEACHER_ACCOUNT']);
        $teacher_pic_url = $teacher_data['PICTURE_URL'];
        $row['TEACHER_PICTURE_URL'] = $teacher_pic_url;
        //add the tutorial url
        $row['TUTORIAL_URL'] = base_url().'index.php/pages/tutorials/'.$row['TUTORIAL_ID'];
        //if the state is raising fund, output how many days left
        if($row['STATE']=='raising_funds'){
            $now = new DateTime();
            $fund_deadline =  new DateTime($row['FUNDING_START_TIME']);
            $fund_deadline->add(new DateInterval('P30D'));
            
            $interval = $fund_deadline->diff($now);
            
            //$row['RAISE_FUND_DAYS_REMAINING'] = $interval->format("%a days, %h hours, %i minutes, %s seconds");
            $row['RAISE_FUND_DAYS_REMAINING'] = $interval->format("%a");
        }
        $row = $this->addCoursePresumedDate($row);
        return $row;
    }
    
    //移除影片相關程式 修改PREPARE_DAYS相關參數
    //public function createNewTutorial($data, $tutorial_session_titles, $intro_vid_id, $user){
    public function createNewTutorial($data, $tutorial_session_titles ,$user){
        //intro video id is the video that is attached to the first session of
        //every tutorial, it is a free video for the purpose of providing
        //a preview of the course, so that the student can decide whether
        //or not to buy this course(tutorial)
        $data['TEACHER_ACCOUNT'] = $user;
        $data['CREATE_TIME'] = date("Y-m-d H:i:s");
        $data['STATE'] = 'pending';
        if(!$this->db->insert(TutorialModel::$TutorialTableName, $data)) return false;
        $tut_id = $this->db->insert_id();
       // if(!$this->insertTutorialSessions($tutorial_session_titles, $tut_id, $intro_vid_id)) return false;
        if(!$this->insertTutorialSessions($tutorial_session_titles, $tut_id)) return false;
        return $tut_id;
    }
    //public function insertTutorialSessions($tutorial_session_titles, $tut_id, $intro_vid_id = -1){
    public function insertTutorialSessions($tutorial_session_titles, $tut_id){//note, they will be inserted in this order
        
        $batch_data = array();
        $i = 0;
        foreach($tutorial_session_titles as $title){
            $row = array(
                'TITLE'=>$title,
                'TUTORIAL_ID'=>$tut_id,
                'TUTORIAL_INDEX'=>$i
            );
            $batch_data[] = $row;
            ++$i;
        }
        
        if(!$this->db->insert_batch(TutorialModel::$TutorialSessionTableName, $batch_data)) return false;
        //影片相關程式
        /*if($intro_vid_id>0){
            return $this->db->update(TutorialModel::$TutorialSessionTableName, 
                array('VIDEO_ID'=>$intro_vid_id),
                array('TUTORIAL_ID'=>$tut_id, 'TUTORIAL_INDEX'=>0)
            );
        }*/
        return true;
    }
    public function getTutorialRow($id){
        $q = $this->db->get_where(TutorialModel::$TutorialTableName,array('TUTORIAL_ID'=>$id));
        $r = $q->result_array();
        if($r){
            return $r[0];
        }else return false;
    }
    public function checkTutorialExists($where){
        $this->db->select('TUTORIAL_ID');
        $q = $this->db->get_where(TutorialModel::$TutorialTableName,$where);
        $r = $q->result_array();
        if($r){
            return true;
        }else return false;
    }
    public function updateTutorialData($id, $data_array){
        return $this->db->update(TutorialModel::$TutorialTableName, $data_array, array('TUTORIAL_ID'=>$id));
    }
    public function updateTutorialState($id, $state){
        return $this->updateTutorialData($id, array('STATE'=>$state));
    }
    //寄發email
    public function authorizePendingTutorial($tut_id){
        $this->db->select('TUTORIAL_ID, TEACHER_ACCOUNT, TITLE');
        $tut_data= $this->getTutorialRow($tut_id);
        $teacher_data = $this->userModel->getUserRow($tut_data['TEACHER_ACCOUNT']);
        $course_url = base_url().'index.php/pages/tutorials/'.$tut_id;
        $msg = '您申請的課程 '.$tut_data['TITLE'].' 通過申請,進入募資階段,請點選以下連結觀看: '.$course_url;
        $this->siteMsgModel->sendMsgToUser($teacher_data, '您開的課程 '.$tut_data['TITLE'].' 進入募資階段!', '您開的課：'.$tut_data['TITLE'].' 通過申請,進入開放選課的階段，您可以點擊下面連結瀏覽： '.$course_url, true);
        return $this->tutorialModel->updateTutorialData($tut_id, array('STATE'=>'raising_funds','FUNDING_START_TIME'=>date('Y-m-d H:i:s')));
    }
    // public function getTutorialSessionRowWithVideoId($vid_id){
    //     $q = $this->db->get_where(TutorialModel::$TutorialSessionTableName, array('VIDEO_ID'=>$vid_id));
    //     $r = $q->result_array();
    //     if($r){
    //         return $r[0];
    //     }else return false;
    // }
    public function updateTutorialPicture($id, $img_id){
        $tut_data = $this->getTutorialRow($id);
        if(!$tut_data) return false;
        if(strlen($tut_data['IMAGE_ID'])>0){
            //delete the old picture
            $this->imageModel->deleteImage($user_data['IMAGE_ID']);
            //add the new picture to user data
            $this->updateTutorialData($id, array('IMAGE_ID'=>$img_id));
        }
    }
    // Wishing 
    public function createNewWishing($USERNAME,$TITLE,$LEVEL,$SHORT_INTRO,$TEACHER,$CATEGORY,$DESIRE,$EYES_COUNT){
        $data = array(
                'NAME'=>$USERNAME,
                'TITLE'=>$TITLE,
                'LEVEL'=>$LEVEL,
                'SHORT_INTRO'=>$SHORT_INTRO,
                'TEACHER'=>$TEACHER,
                'CATEGORY'=>$CATEGORY,
                'DESIRE'=>$DESIRE,
                'EYES_COUNT'=>$EYES_COUNT
            );
        $data['CREATE_TIME'] = date("Y-m-d H:i:s");
        if(!$this->db->insert('TUTORIAL_WISHING', $data)) return false;
        return true;
    }
    
    public function searchForWishing($keyword, $subtab_idx = 0){
        $offset = $subtab_idx * TutorialModel::$TutorialSearchSubtabLimit;
        if(strlen($keyword)==0){
            return $this->searchWishingWithState($offset);
        }
        $match_col_names = array(
            'NAME','TITLE','LEVEL','SHORT_INTRO','TEACHER','CATEGORY','CREATE_TIME'
        );
        //$where = "MATCH (".implode(',', $match_col_names).") AGAINST ('".$keyword."')";
        $this->selectWishingIconNeededFields();
        
        $this->OrLikeMultiple($match_col_names, $keyword);
        $q = $this->db->get(TutorialModel::$WishingTableName, TutorialModel::$TutorialSearchSubtabLimit, $offset);
        $r = $q->result_array();
        // echo "<script type='text/javascript'>alert('".$r."');</script>";
        if($r){
            return $this->addWishingIconAddFields($r);
        }else return false;
    }
    public function searchWishingWithState($offset = 0){
        $q=$this->db->get(TutorialModel::$WishingTableName, TutorialModel::$TutorialSearchSubtabLimit, $offset);
        $r = $q->result_array();
        if($r){
            return $this->addWishingIconAddFields($r);
        }else return false;
    }
    private function selectWishingIconNeededFields(){
        $this->db->select('WISH_ID','NAME','TITLE','LEVEL','SHORT_INTRO','TEACHER','DESIRE','EYES_COUNT','CATEGORY','CREATE_TIME');
    }
    public function addWishingIconAddFields($rows){
        $output = array();
        foreach($rows as $row){
            $output[]=$this->addWishingIconAddFieldsSingle($row);
        }
        return $output;
    }
    public function addWishingIconAddFieldsSingle($row){
        // //get tutorial img url
        // $img_id = $row['IMAGE_ID'];
        // $url = $this->imageModel->getImageUrlOrPlaceholder($img_id);
        // $row['TUTORIAL_IMAGE_URL'] = $url;
        //get teacher image url
        $student_data = $this->userModel->getUserData($row['NAME']);
        $student_pic_url = $student_data['PICTURE_URL'];
        $row['STUDENT_PICTURE_URL'] = $student_pic_url;
        //add the tutorial url
        //$row['WISHING_URL'] = base_url().'index.php/pages/tutorials/'.$row['WISH_ID'];        
        return $row;
    }

    //按讚功能
    public function likeWishing($wish_id, $account){ 

        $sql = "SELECT * FROM WISHING_SESSION WHERE WISH_ID='".$wish_id."' AND ACCOUNT='".$account."'";
        $q = $this->db->query($sql);
        $r = $q->result_array();
        if($r){

            $sql = "DELETE FROM WISHING_SESSION WHERE WISH_ID='".$wish_id."' AND ACCOUNT='".$account."'";
            if($this->db->query($sql)){            
                $this->db->select('DESIRE');
                $this->db->where('WISH_ID', $wish_id);
                $q = $this->db->get(TutorialModel::$WishingTableName); 
                $r = $q->result_array();
                $cnt = (int)($r[0]['DESIRE']) - 1;

                $data = array(
                       'DESIRE' => $cnt,
                    );

                $this->db->where('WISH_ID', $wish_id);

                if($this->db->update(TutorialModel::$WishingTableName, $data))
                    return true;                
                else
                    return false;

            }else return false;
        }
        else{

            $this->db->select('DESIRE');
            $this->db->where('WISH_ID', $wish_id);
            $q = $this->db->get(TutorialModel::$WishingTableName); 
            $r = $q->result_array();
            $cnt = (int)($r[0]['DESIRE']) + 1;

            $data = array(
                   'DESIRE' => $cnt,
                );

            $this->db->where('WISH_ID', $wish_id);

            if($this->db->update(TutorialModel::$WishingTableName, $data)){
                $data = array(
                    'WISH_ID' => $wish_id,
                    'ACCOUNT' => $account,
                );

                if($this->db->insert(TutorialModel::$WishingSessionTableName, $data))
                    return true;
                else
                    return false;
            }
            else
                return false;
        }
    }
    //按讚與否
    public function checkLikeWishing($user){
        $q = $this->db->get_where(TutorialModel::$WishingSessionTableName, array('ACCOUNT'=>$user));
        $r = $q->result_array();
        $output = array();
        foreach($r as $row){
            $output[] = $row['WISH_ID'];
        }
        if($output){
            return $output;
        }else{
            return false;
        }
    }
}