<?php
class Backgroundjobs extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        //only can  be executed on the local machine
        if(!$this->input->is_cli_request()){
            show_404();
            exit();
        }
        $this->load->model('UserModel', 'userModel');
        $this->load->model('TutorialModel', 'tutorialModel');
        $this->load->model('ImageModel', 'imageModel');
        $this->load->model('VideoModel', 'videoModel');
    }
    private function cleanupVideos(){
        $hours = $this->config->item('cleanup_video_hours');
        //get the videos that are (1) not validated (2) created for more than $limit hours
        //validate the videos, if valid, update their valid column
        //if invalid, delete the video
        $offset = 0;
        $limit = 2048;
        while(true){
            $r = $this->videoModel->getNotValidatedVideoRowsHoursAgo($hours, $limit, $offset);
            $count = count($r);
            if($count==0) break;//no more
            $ids = $this->videoModel->getIdsFromRows($r);
            $valid_ids = array();
            $invalid_ids = $this->videoModel->getInvalidIds($ids, $valid_ids);
            $this->videoModel->deleteMultipleVideos($invalid_ids);
            $this->videoModel->updateMultipleVideos($valid_ids, array('VALIDATED'=>true));
            $offset +=$count;        
        }
    }
    
    private function cleanupImages(){
        $hours = $this->config->item('cleanup_img_hours');
        $offset = 0;
        $limit = 2048;
        while(true){
            $r = $this->imageModel->getNotValidatedImagesCreatedHoursAgo($hours, $limit, $offset);
            $count = count($r);
            if($count==0) break;//no more
            $valid_images = array();
            $invalid_ids = $this->imageModel->getInvalidImageIds($r, $valid_ids);
            $this->imageModel->deleteMultipleImages($invalid_ids);
            //update the valid ids
            $this->imageModel->updateMultipleImages($valid_ids, array('VALIDATED'=>true));
            $offset +=$count;        
        }
    }
    private function updateTutorialStates(){
        //at the end of raising_funds and prepare, we need to change the state
        $this->tutorialModel->updateRaisingFundTutorials();
        $this->tutorialModel->updatePrepareTutorials();
    }
    public function dailyOperations(){
        //cleanup unused videos
        $this->cleanupVideos();
        //cleanup unused imgs
        $this->cleanupImages();
        //update tutorial states if they have reached some deadline
        $this->updateTutorialStates();
    }
}