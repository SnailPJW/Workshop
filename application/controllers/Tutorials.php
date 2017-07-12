<?php
class Tutorials extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserModel', 'userModel');
        $this->load->model('TutorialModel', 'tutorialModel');
        $this->load->model('ImageModel', 'imageModel');
        $this->load->model('VideoModel', 'videoModel');
    }
    public function enterPrepareStage(){
        $tutorial_id = $this->input->post('TUTORIAL_ID');
        $user = $this->session->userdata('ACCOUNT');
        if(!$user){
            JSON_Util::SendErrorResponse('你尚未登入!');
            return;
        }
        if(!$this->tutorialModel->userCanEditTutorial($user, $tutorial_id)){
            JSON_Util::SendErrorResponse('你沒有足夠的權限查詢此課程之狀態!');
            return;
        }
        if($this->tutorialModel->canEnterPrepareStage($tutorial_id)){
            if($this->tutorialModel->enterPrepareStage($tutorial_id)){
                JSON_Util::SendSuccessResponse('課程進入準備階段!');
                return;
            }else{
                JSON_Util::SendErrorResponse('資料庫錯誤!');
                return;
            }
        }else{
            JSON_Util::SendErrorResponse('課程仍然沒有達到備課接段的條件!');
            return;
        }
    }
    public function openTutorial(){
        $tutorial_id = $this->input->post('TUTORIAL_ID');
        $user = $this->session->userdata('ACCOUNT');
        if(!$user){
            JSON_Util::SendErrorResponse('你尚未登入!');
            return;
        }
        if(!$this->tutorialModel->userCanEditTutorial($user, $tutorial_id)){
            JSON_Util::SendErrorResponse('你沒有足夠的權限查詢此課程之狀態!');
            return;
        }
        //validate if the tutorial can be turned online
        
        if(!$this->tutorialModel->canTurnTutorialOnline($tutorial_id)){
            JSON_Util::SendErrorResponse('未完成備課，無法開課!');
            return;
        }
        if($this->tutorialModel->turnTutorialOnline($tutorial_id)){
            JSON_Util::SendSuccessResponse('正式開課!');
            return;
        }else{
            JSON_Util::SendErrorResponse('資料庫錯誤!');
            return;
        }
        
    }
    public function checkSessionStates(){
        $tutorial_id = $this->input->post('TUTORIAL_ID');
        $user = $this->session->userdata('ACCOUNT');
        if(!$user){
            JSON_Util::SendErrorResponse('你尚未登入!');
            return;
        }
        if(!$this->tutorialModel->userCanEditTutorial($user, $tutorial_id)){
            JSON_Util::SendErrorResponse('你沒有足夠的權限查詢此課程之狀態!');
            return;
        }
        $session_states = $this->tutorialModel->getTutorialSessionsWithVideoData($tutorial_id);
        if($session_states===false){
            JSON_Util::SendErrorResponse('資料庫錯誤!');
            return;
        }
        JSON_Util::SendSuccessResponse($session_states);
    }
    public function registerVideoToTutorialSession(){
        $vid_id = $this->input->post('VIDEO_ID');
        $session_id = $this->input->post('TUTORIAL_SESSION_ID');
        $user = $this->session->userdata('ACCOUNT');
        if(!$user){
            JSON_Util::SendErrorResponse('你尚未登入!');
            return;
        }
        if(!$this->tutorialModel->userCanEditSession($user, $session_id)){
            JSON_Util::SendErrorResponse('你不能修改此課程!');
            return;
        }
        if(!$this->videoModel->userOwnsVideo($user,$vid_id)){
            JSON_Util::SendErrorResponse('你不能使用這個影片!');
            return;
        }
        //delete the old video if it exists
        $this->tutorialModel->deleteVideoOfSession($session_id);
        if($this->tutorialModel->updateTutorialSessionVideoId($session_id, $vid_id)){
            JSON_Util::SendSuccessResponse('更新成功!');
            return;
        }else{
            JSON_Util::SendErrorResponse('資料庫錯誤!');
            return;
        }
    }
    public function buyTutorial(){
        $user = $this->session->userdata('ACCOUNT');
        $tut_id = $this->input->post('TUTORIAL_ID');
        if(!$user){
            JSON_Util::SendErrorResponse('你尚未登入!');
            return;
        }
        $row = $this->tutorialModel->getTutorialRow($tut_id);
        if($row===false){
            JSON_Util::SendErrorResponse('無此課程!');
            return;
        }
        $buy_type = 'normal';
        //if($row['STATE']=='raising_funds'||$row['STATE']=='prepare'){
        if($row['STATE']=='raising_funds'){
            $buy_type = 'discount';
        }else if($row['STATE']=='started'){
            $buy_type = 'normal';
        }else{//the tutorial is not in a buyable state
            JSON_Util::SendErrorResponse('此課程目前不能進行購買!');
            return;
        }
        if($this->tutorialModel->tutorialHasStudent($tut_id, $user)){
            JSON_Util::SendErrorResponse('你已經購買了此課程!');
            return;
        }
        //add redirecting here for real buying mechanisms
        
        //--------
        if($this->tutorialModel->addBuyRecord($tut_id, $user, $buy_type)){
            JSON_Util::SendSuccessResponse('選課成功');
            return;
        }else{
            JSON_Util::SendErrorResponse('資料庫錯誤!');
            return;
        }
            
        
    }
    public function authorizePendingTutorial(){
        $user = $this->session->userdata('ACCOUNT');
        if(!$user){
            JSON_Util::SendErrorResponse('你尚未登入!');
            return;
        }
        $user_data = $this->userModel->getUserData($user);
        if(!$user_data['ADMIN']){
            JSON_Util::SendErrorResponse('你沒有管理者權限!');
            return;
        }
        $tut_id = $this->input->post('TUTORIAL_ID');
        $where = array('TUTORIAL_ID'=>$tut_id, 'STATE'=>'pending');
        if($this->tutorialModel->checkTutorialExists($where)){
            if($this->tutorialModel->authorizePendingTutorial($tut_id)){
                JSON_Util::SendSuccessResponse('審核成功!');
                return;
            }else{
                JSON_Util::SendErrorResponse('資料庫錯誤!');
                return;
            }
        }else{
            JSON_Util::SendErrorResponse('無此課程或者該課程已經不在等待審核的狀態!');
            return;
        }
        
    }

    public function authorizePendingTutorialA9($tut_id){
            $where = array('TUTORIAL_ID'=>$tut_id, 'STATE'=>'pending');
            if($this->tutorialModel->checkTutorialExists($where)){
            if($this->tutorialModel->authorizePendingTutorial($tut_id)){
                JSON_Util::SendSuccessResponse('審核成功!');
                return;
            }else{
                JSON_Util::SendErrorResponse('資料庫錯誤!');
                return;
            }
        }else{
            JSON_Util::SendErrorResponse('無此課程或者該課程已經不在等待審核的狀態!');
            return;
        
        }
        
    }
    public function searchForTutorial(){
        $state = $this->input->post('state');
        $keyword = $this->input->post('keyword');
        $subtab_idx = $this->input->post('subtab_idx');
        if(strlen($keyword)>32){
            JSON_Util::SendErrorResponse('過長的搜尋關鍵字!');
            return;
        }
        if($subtab_idx===false) $subtab_idx = 0;
        $rows = $this->tutorialModel->searchForTutorial($state, $keyword, $subtab_idx);
        if($rows!==false){
            JSON_Util::SendSuccessResponse($rows);
        }else{
            JSON_Util::SendErrorResponse('沒有更多的課程!');
        }
    }
    
    public function submitNewTutorial(){
        $user = $this->session->userdata('ACCOUNT');
        if(!$user){
            JSON_Util::SendErrorResponse('你尚未登入!');
            return;
        }
        $v = $this->getInfo('IMAGE_ID', '課程圖片錯誤!','validateAmount', array('min'=>0));
        if($v===false) return;
        $img_id = $v;
        //課程程度判斷
        $v = $this->getInfo('TUTORIAL_LEVEL', '課程程度不可為空!','validateNotEmpty');
        if($v===false) return;
        //課程類別判斷
        $v = $this->getInfo('CATEGORY', '課程類別不可為空!','validateNotEmpty');
        if($v===false) return;
        //進行方式判斷
        $v = $this->getInfo('PROGRAM', '進行方式不可為空!','validateNotEmpty');
        if($v===false) return;
        //教學方式判斷
        $v = $this->getInfo('METHOD', '教學方式不可為空!','validateNotEmpty');
        if($v===false) return;
        //上課位置
        $v = $this->getInfo('PREDICTED_COURSE_LOCATION', '課程地點不可為空!','validateNotEmpty');
        if($v===false) return;
        $v = $this->getInfo('INTRODUCTION', '課程介紹錯誤!','validateNotEmpty');
        if($v===false) return;
        $v = $this->getInfo('NEEDED_ITEMS', '上課所需物品不可為空!','validateNotEmpty');
        if($v===false) return;
        //變更時間判斷改為小時
        $v = $this->getInfo('PREDICTED_COURSE_LENGTH', '預計上課總長錯誤!','validateAmount', array('min'=>1));
        if($v===false) return;
        $v = $this->getInfo('REQ_KNOWLEDGE', '學生必須具備的背景知識不可為空!','validateNotEmpty');
        if($v===false) return;
        $v = $this->getInfo('COURSE_OUTPUT', '學生上完課能做到的不可為空!','validateNotEmpty');
        if($v===false) return;
        $v = $this->getInfo('REQ_STUDENT_COUNT', '開課人數錯誤!','validateAmount', array('min'=>25));
        if($v===false) return;
        //新增開課日期判斷
        $v = $this->getInfo('PREPARE_DAYS', '開課日期不可為空!','validateNotEmpty');
        if($v===false) return;
        $v = $this->getInfo('SHORT_INTRO', '簡短介紹不可為空!','validateNotEmpty');
        if($v===false) return;
        $v = $this->getInfo('TITLE', '課程標題不可為空!','validateNotEmpty');
        if($v===false) return;
        //$v = $this->getInfo('VIDEO_ID', '課程介紹影片錯誤!','validateAmount', array('min'=>0));
        //if($v===false) return;
        //$vid_id = $v;
        //validate tutorial sessions
        $v = $this->input->post('TUTORIAL_SESSION_TITLES');
        if($v===false){
            JSON_Util::SendErrorResponse('課程規劃錯誤!');
            return;
        }
        if(!is_array($v)){
            JSON_Util::SendErrorResponse('課程規劃錯誤!');
            return;
        }
        if(count($v)<=1){
            JSON_Util::SendErrorResponse('課程規劃錯誤!');
            return;
        }
        
        $empty = false;
        foreach($v as $session_title){
            if(strlen($session_title)==0){
                $empty = true;
                break;
            }
        }
        if($empty){
            JSON_Util::SendErrorResponse('課程規劃錯誤!');
        }
        $tutorial_titles = $v;
        //validate the image
        if(!$this->imageModel->userCanUseAsTutorialImage($user, $img_id)){
            JSON_Util::SendErrorResponse('此圖片尚不能使用!');
            return;
        }
        //validate the video
        /*if(!$this->videoModel->userCanUseAsTutorialSessionVideo($user, $vid_id)){
            JSON_Util::SendErrorResponse('此影片尚不能使用!');
            return;
        }*/

        //all validated, actually write to db
        //gather data
        //Remove the video and REQ_PRICE  and REQ_STUDENT_COUNT
        /*$data = $this->getPostFieldsWithKeys(array(
            'DISCOUNT_REQ_PRICE','IMAGE_ID','INTRODUCTION','NEEDED_ITEMS',
            'PREDICTED_COURSE_LENGTH','PREPARE_DAYS','REQ_KNOWLEDGE','COURSE_OUTPUT',
            'REQ_PRICE','REQ_STUDENT_COUNT','SHORT_INTRO','TITLE'
        ));*/
        //做出(到)=COURSE_OUTPUT   基本知識=REQ_KNOWLEDGE
        //PREPARE_DAYS 改成開課日期
        $data = $this->getPostFieldsWithKeys(array(
            'IMAGE_ID','INTRODUCTION','NEEDED_ITEMS','PREDICTED_COURSE_LENGTH','PREPARE_DAYS','REQ_KNOWLEDGE','COURSE_OUTPUT',
            'REQ_STUDENT_COUNT','SHORT_INTRO','TITLE','TUTORIAL_LEVEL','CATEGORY','PROGRAM','METHOD','PREDICTED_COURSE_LOCATION'
        ));

        //if($this->tutorialModel->createNewTutorial($data, $tutorial_titles, $vid_id, $user)>0){
        $tutorial_id = $this->tutorialModel->createNewTutorial($data, $tutorial_titles,$user);
        if($tutorial_id>0){
            JSON_Util::SendSuccessResponse($tutorial_id);
        }else{
            JSON_Util::SendErrorResponse('資料庫錯誤');
        }
    }
    private function getPostFieldsWithKeys($keys){
        $data = array();
        foreach($keys as $k){
            $v = $this->input->post($k);
            if($v!==false){
                $data[$k] = $v;
            }
        }
        return $data;
    }
    private function validateNotEmpty($v, $ctx){
        if(strlen($v)>0) return true;
        else return false;
    }
    private function validateAmount($v, $ctx){
        $min = false;
        $max = false;
        if(array_key_exists('min', $ctx)) $min = $ctx['min'];
        if(array_key_exists('max', $ctx)) $max = $ctx['max'];
        
        if(!is_numeric($v)) return false;
        if($min!==false && $min>$v) return false;
        if($max!==false && $max<$v) return false;
        return true;
    }
    private function getInfo($k, $err_msg, $validate_func = false, $validate_context = array()){
        /*
        $key_names = array(
            'DISCOUNT_REQ_PRICE','IMAGE_ID','INTRODUCTION','NEEDED_ITEMS','PREDICTED_COURSE_LENGTH','PREPARE_DAYS','REQ_KNOWLEDGE','REQ_PRICE','REQ_STUDENT_COUNT','SHORT_INTRO','TITLE','VIDEO_ID'
        );
         */
        $v = $this->input->post($k);
        if($v===false)  {
            JSON_Util::SendErrorResponse($err_msg);
        }else{
            if($validate_func!=false && !call_user_func(array($this, $validate_func), $v, $validate_context)){
                JSON_Util::SendErrorResponse($err_msg);
                $v=false;
            }
        }
        return $v;
    }
    public function uploadRawTutorialSessionChunk($chunk_upload_id){
        $user = $this->session->userdata('ACCOUNT');
        if(!$user){
            JSON_Util::SendErrorResponse('你尚未登入!');
            return;
        }
        $session = $this->getSessionRowWithUploadId($chunk_upload_id);
        if($session===false){
            JSON_Util::SendErrorResponse('無此教學影片!');
            return;
        }
        if($session['STATE']!='uploading'){
            JSON_Util::SendErrorResponse('此教學已經不能再上傳');
            return;
        }
        
        if($session['OWNER']!=$user){
            JSON_Util::SendErrorResponse('你沒有權限上傳至此檔案!');
            return;
        }
        $chunk = file_get_contents('php://input');
        $this->tutorialModel->uploadSessionChunk($chunk, $chunk_upload_id);
    }
     public function submitNewWishing(){
        $user = $this->session->userdata('ACCOUNT');
        $TITLE=$this->input->post('TITLE');
        $LEVEL=$this->input->post('LEVEL');
        $CATEGORY=$this->input->post('CATEGORY');
        $SHORT_INTRO=$this->input->post('SHORT_INTRO');
        $TEACHER=$this->input->post('TEACHER');
        $DESIRE=$this->input->post('DESIRE');
        $EYES_COUNT=$this->input->post('EYES_COUNT');
        //加入判斷
        $v = $this->getInfo('TITLE', '課程標題不可為空!','validateNotEmpty');
        if($v===false) return;
        $v = $this->getInfo('LEVEL', '課程程度不可為空!','validateNotEmpty');
        if($v===false) return;
        $v = $this->getInfo('CATEGORY', '課程類別不可為空!','validateNotEmpty');
        if($v===false) return;
        $v = $this->getInfo('SHORT_INTRO', '課程描述不可為空!','validateNotEmpty');
        if($v===false) return;
        $v = $this->getInfo('TEACHER', '推薦老師不可為空!','validateNotEmpty');
        if($v===false) return;

        $v = $this->getInfo('DESIRE', '推薦老師不可為空!','validateNotEmpty');
        if($v===false) return;
        $v = $this->getInfo('EYES_COUNT', '推薦老師不可為空!','validateNotEmpty');
        if($v===false) return;
        

        if(!$user){
            JSON_Util::SendErrorResponse('你尚未登入!');
            return;
        }
        if($this->tutorialModel->createNewWishing($user,$TITLE,$LEVEL,$SHORT_INTRO,$TEACHER,$CATEGORY,$DESIRE,$EYES_COUNT)>0){
            JSON_Util::SendSuccessResponse('');
        }else{
            JSON_Util::SendErrorResponse('資料庫錯誤!!!');
        }
     }
    
     public function searchForWishing(){
        $keyword = $this->input->post('keyword');
        $subtab_idx = $this->input->post('subtab_idx');
        if(strlen($keyword)>32){
            JSON_Util::SendErrorResponse('過長的搜尋願望關鍵字!');
            return;
        }
        if($subtab_idx===false) $subtab_idx = 0;
        $rows = $this->tutorialModel->searchForWishing($keyword, $subtab_idx);
        if($rows!==false){
            JSON_Util::SendSuccessResponse($rows);
        }else{
            JSON_Util::SendErrorResponse('沒有更多的願望!');
        }
    }

    public function likeWishing(){
        $wish_id = $this->input->post('wish_id');
        $account = $this->input->post('account');
        
        $rows = $this->tutorialModel->likeWishing($wish_id, $account);
        if($rows!==false){
            JSON_Util::SendSuccessResponse('ok');
        }else{
            JSON_Util::SendErrorResponse('update error');
        }
    }
    
    // public function checkLikeWishing(){
    //     $account = $this->input->post('account');
        
    //     $rows = $this->tutorialModel->checkLikeWishing($account);
    //     if($rows!==false){
    //         JSON_Util::SendSuccessResponse('ok');
    //     }else{
    //         JSON_Util::SendErrorResponse('error');
    //     }
    // }
}