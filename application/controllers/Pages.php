<?php
class Pages extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserModel', 'userModel');
        $this->load->model('TutorialModel', 'tutorialModel');
        $this->load->model('TicketModel', 'ticketModel');
        $this->load->model('SiteMsgModel', 'siteMsgModel');
    }
    public function view($page="aboutWork"){
        if (!file_exists('application/views/pages/'.$page.'.php')) {
            //header('Content-Type: text/plain');
            header("Location: ".base_url()."index.php/pages/onConstruction");
            echo "鬆土中...";
            return;
        }
        $this->writeReqUriToSession();
        $context = $this->getContextCommon();
        $load_navbar = true;
        $page_to_title = $context['page_to_title'];
        if(array_key_exists($page, $page_to_title)){
            $context['title'] = $page_to_title[$page];
        }else{
            $context['title'] = $page;
        }
        $context['page'] = $page;
        switch($page){
            case 'login':
                $user = $this->session->userdata('ACCOUNT');
                if($user){
                    //$this->session->sess_destroy();//logout user
                    $this->session->unset_userdata('ACCOUNT');
                }
                $load_navbar = false;
                break;
            case 'account-settings':
                if(!array_key_exists('user_data', $context)){
                    $this->redirectToLogin();
                    return;
                }
                $user = $context['user_data']['ACCOUNT'];
                //add bought and opened tutorial data
                $context['user_data']['bought_tutorials'] = $this->tutorialModel->getBoughtTutorialsIconData($user);
                $context['user_data']['opened_tutorials'] = $this->tutorialModel->getOpenedTutorialsIconData($user);
                break;
            case 'start-new-tutorial':
                if(!array_key_exists('user_data', $context)){
                    $this->redirectToLogin();
                    return;
                }
                break;
            //課程許願池
            case 'Wishing-tutorial':
                 if(!array_key_exists('user_data', $context)){
                    $this->redirectToLogin();
                    return;
                }
                $user = $context['user_data']['ACCOUNT'];
                $context['user_data']['wishLikeList'] = $this->tutorialModel->checkLikeWishing($user);
                break;
            case 'customer-service':
                if(!array_key_exists('user_data', $context)){
                    $this->redirectToLogin();
                    return;
                }
                break;
            case 'on-site-msg':
                if(!array_key_exists('user_data', $context)){
                    $this->redirectToLogin();
                    return;
                }
                $user = $context['user_data']['ACCOUNT'];
                $context['on_site_msgs'] = $this->siteMsgModel->getMessageMapForUser($user);
                break;
            case 'answer-ticket':
                if(!array_key_exists('user_data', $context)){
                    $this->redirectToLogin();
                    return;
                }
                $user_data = $context['user_data'];
                if(!$user_data['ADMIN']){
                    echo '你沒有管理者權限';
                    return;
                }
                //get the un-answered tickets
                $context['unanswered_tickets'] = $this->ticketModel->getUnansweredTickets();
                break;
        }
        $this->loadPageHeader($context);
        if($load_navbar)    $this->loadNavBar($context);
        $this->load->view('pages/'.$page, $context);
        $this->loadPageFooter($context);
    }
    private function writeReqUriToSession(){
        $req_uri = base_url().'index.php/'.$this->uri->uri_string();
        if(strpos($req_uri, 'login')===false){
            $this->session->set_userdata('LAST_URI', $req_uri);
        }
    }
    private function getPageToTitle(){
        return array(
            'account-settings'=>'帳戶設定',
            'aboutWork'=>'關於沃課',
            'search-tutorial'=>'沃課搜尋',
            'register-account'=>'註冊',
            'start-new-tutorial'=>'沃課播種',
            'login'=>'登入/註冊',
            'authorize-pending-tutorials'=>'核准新課程',
            'customer-service'=>'意見反應',
            'answer-ticket'=>'回覆課訴',
            'on-site-msg'=>'站內訊息',
            'Wishing-tutorial'=>'祈求播課',
            'teachers-community'=>'播種人員',
            'ResultPower'=>'成果研究能量',
            'ResPowSecond'=>array(
                'nonBorder' => '無邊界',
                'classic' => '典範',
                'teachBravo' => '教卓',
                'other' => '其他'
            )
        );
    }
    private function getContextCommon(){
        $context = array();
        $user = $this->session->userdata('ACCOUNT');
        $user_data = false;
        if($user){
            $user_data = $this->userModel->getUserData($user);
        }
        $navbar_links = array('aboutWork','search-tutorial','start-new-tutorial','Wishing-tutorial','ResultPower');
        //$navbar_links = array('search-tutorial','start-new-tutorial','Wishing-tutorial');
        //一般會員
        if($user_data){
            $navbar_links[]='customer-service';//課訴
            $navbar_links[]='on-site-msg';//站內訊息
        }
        //管理者
        if($user_data&&$user_data['ADMIN']){
            $navbar_links[]='authorize-pending-tutorials';//核准
            $navbar_links[]='answer-ticket';//回覆課訴
        }
        $page_to_title = $this->getPageToTitle();
        $context['navbar_links']=$navbar_links;
        $context['page_to_title'] = $page_to_title;
        if($user_data){
            $context['user_data'] = $user_data;
        }
        return $context;
    }
    
    public function tutorials($tutorial_id){
        $context = $this->getContextCommon();
        $context['title'] = '課程內容';
        $user_data = false;
        if(array_key_exists('user_data', $context)) $user_data = $context['user_data'];
        $tut_data = $this->tutorialModel->getTutorialDataForFullRender($tutorial_id);
        if($tut_data === false){
            echo '你尋找的課程不存在或者當前不能瀏覽!';
            return;
        }
        $this->writeReqUriToSession();
        $tut_state = $tut_data['STATE'];
        /*
        if($tut_state=='online'){
            //get the session data with video data
            $tut_data['SESSIONS'] = $this->tutorialModel->getVideoData($tut_data['SESSIONS']);
        }
         */
        $context['tutorial_data'] = $tut_data;
        if($user_data&&($user_data['ACCOUNT']==$tut_data['TEACHER_ACCOUNT'])){
            $context['is_teacher'] = true;
        }
        if($user_data&&$this->tutorialModel->tutorialHasStudent($tut_data['TUTORIAL_ID'], $user_data['ACCOUNT'])){
            $context['tutorial_bought'] = true;
        }
        $this->loadPageHeader($context);
        $this->loadNavBar($context);
        $this->fullRenderTutorial($tut_state, $context);
        $this->loadPageFooter($context);
    }
    public function editTutorial($tutorial_id){
        $context = $this->getContextCommon();
        $context['title'] = '編輯課程';
        $user_data = false;
        if(array_key_exists('user_data', $context)) $user_data = $context['user_data'];
        $tut_data = $this->tutorialModel->getTutorialDataForFullRender($tutorial_id, true);
        if($tut_data === false){
            show_404();
            return;
        }
        $tut_state = $tut_data['STATE'];
        if($tut_state!='prepare'){
            show_404();
            return;
        }
        $context['tutorial_data'] = $tut_data;
        if($user_data&&($user_data['ACCOUNT']==$tut_data['TEACHER_ACCOUNT'])){
            $context['is_teacher'] = true;
        }
        if($user_data&&$this->tutorialModel->tutorialHasStudent($tut_data['TUTORIAL_ID'], $user_data['ACCOUNT'])){
            $context['tutorial_bought'] = true;
        }
        if(!array_key_exists('is_teacher', $context)){//user is not the teacher, redirect
            $this->redirectToLogin();
            return;
        }
        $this->loadPageHeader($context);
        $this->loadNavBar($context);
        $this->load->view('pages/edit-tutorial', $context);
        $this->loadPageFooter($context);
    }
    private function fullRenderTutorial($state, $context){
        $this->load->view('pages/tutorial/'.$state, $context);
    }
    
    private function redirectToPage($page){
        header("Location: ".base_url()."index.php/pages/view/".$page);
    }
    private function redirectToLogin(){
        $this->redirectToPage('login');
    }
    //for loading jquery, jquery-ui, css assets...etc
    //header and footer views should be put in /views/templates/header or footer
    private function loadPageHeader($context){
        $title = $context['title'];
        $user = $this->session->userdata('ACCOUNT');
        $data = array('title'=>$title, 'username'=>$user);

        // constructheader
        $this->load->view('pages/templates/header', $data);
    }
    private function loadNavBar($context){
        $user = $this->session->userdata('ACCOUNT');
        $context['username'] = $user;
        $this->load->view('pages/templates/navheader', $context);
        $this->load->view('pages/templates/navfooter', $context);
    }
    
    private function loadPageFooter($context){
        $this->load->view('pages/templates/footer', $context);
    }
    //check if user is logged in, if not redirect to login
    private function handleIfUserNotLogin(){
        $user = $this->session->userdata('ACCOUNT');
        if (!$user) {
            header('Location: '.base_url().'index.php/pages/login');
            exit(0);
        }
    }
}
