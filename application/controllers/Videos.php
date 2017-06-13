<?php
class Videos extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UserModel', 'userModel');
        $this->load->model('TutorialModel', 'tutorialModel');
        $this->load->model('VideoModel', 'videoModel');
    }
    public function getVideoData($id){
        
        $row  = $this->videoModel->getVideoRowWithId($id);
        if($row===false){
            JSON_Util::SendErrorResponse('無此影片!');
            return;
        }
        $user = $this->session->userdata('ACCOUNT');
        /*
        if(!$user){
            JSON_Util::SendErrorResponse('你尚未登入!');
            return;
        }
         */
        $file_size = $this->videoModel->getOnlineFileSizeFromFileSystem($id);
        
        if($this->videoModel->canViewVideo($row, $user)){
            @apache_setenv('no-gzip', 1);
            @ini_set('zlib.output_compression', 'Off');

            @ob_clean();
            $file = $this->videoModel->getOnlineVideoPath($id);
            header('Content-type: application/octet-stream');
            //header('Content-type: video/mp4');
            $req_headers = $this->input->request_headers();
            //file_put_contents('/OnlineTeachVideos/debug_'.date("Y-m-d_H_i_s").'.log', json_encode($req_headers));
            if(array_key_exists('Range', $req_headers)){
                $ranges = array();
                $ranges = explode('-', substr($req_headers['Range'], 6));
                ob_start();
                
                if(!$ranges[1]) {
                    $ranges[1] = $file_size-1;
                }
                header('HTTP/1.1 206 Partial Content');
                header('Accept-Ranges: bytes');
                header('Content-Length: ' . ($ranges[1] - $ranges[0]+1));
                header(
                    sprintf(
                        'Content-Range: bytes %d-%d/%d', // header format
                        $ranges[0], 
                        $ranges[1], 
                        $file_size 
                    )
                );
                $f = fopen($file, 'rb'); // Open the file in binary mode
                $chunkSize = 10240; // The size of each chunk to output
                fseek($f, $ranges[0]);
                // Start outputting the data
                while(true){
                    if(ftell($f) >= $ranges[1]){
                        break;
                    }
                    echo fread($f, $chunkSize);
                    @ob_flush();
                    flush();
                }
            }else{//no range header, just output entire file
                header('Content-Length: ' . $file_size);
                @readfile($file);
                @ob_flush();
                flush();
            }
        }else{
            JSON_Util::SendErrorResponse('您不能觀看此影片!');
            return;
        }
    }
    public function createVideoPlaceHolder(){
        $user = $this->session->userdata('ACCOUNT');
        if(!$user){
            JSON_Util::SendErrorResponse('你尚未登入!');
            return;
        }
        $ext = $this->input->post('ext');
        $filesize = $this->input->post('file_size');
        $video_id = $this->videoModel->createVideoPlaceholder($user, $ext, $filesize);
        if($video_id!==false){
            JSON_Util::SendSuccessResponse($video_id);
        }else{
            JSON_Util::SendErrorResponse('資料庫錯誤!');
        }
    }
    
    public function uploadVideoChunk($id){
        $user = $this->session->userdata('ACCOUNT');
        if(!$user){
            JSON_Util::SendErrorResponse('你尚未登入!');
            return;
        }
        $row  = $this->videoModel->getVideoRowWithId($id);
        if($row===false){
            JSON_Util::SendErrorResponse('無此影片!');
            return;
        }
        if($row['STATE']!='uploading'){
            JSON_Util::SendErrorResponse('此影片已經不能再上傳');
            return;
        }
        
        if($row['OWNER']!=$user){
            JSON_Util::SendErrorResponse('你沒有權限上傳至此檔案!');
            return;
        }
        $chunk = file_get_contents('php://input');
        $chunk_size = strlen($chunk);
        $current_filesize = $this->videoModel->getRawFileSizeFromFileSystem($id, $row['RAW_EXTENSION']);
        if($current_filesize+$chunk_size>$row['RAW_FILE_SIZE']){
            JSON_Util::SendErrorResponse('超出檔案範圍!');
            return;
        }
        $current_filesize = $this->videoModel->uploadVideoChunk($chunk, $id, $row['RAW_EXTENSION']);
        $output = array();
        if($current_filesize>=$row['RAW_FILE_SIZE']){//upload done
            $output['upload_status'] = 'complete';
            $this->videoModel->changeVideoState($id, 'uploaded');
            JSON_Util::SendSuccessResponse($output);
            return;
        }else if($current_filesize>0){//continue next chunk
            $output['upload_status'] = 'continue';
            $output['byte_offset'] = $current_filesize;
            JSON_Util::SendSuccessResponse($output);
            return;
        }else{//current file size <0, this means the operation failed
            $output['upload_status'] = 'continue';
            $output['byte_offset'] = $this->videoModel->getRawFileSizeFromFileSystem($id, $row['RAW_EXTENSION']);
            JSON_Util::SendSuccessResponse($output);
            return;
        }
    }
    public function checkVideoChunkOffset($id){
        $user = $this->session->userdata('ACCOUNT');
        if(!$user){
            JSON_Util::SendErrorResponse('你尚未登入!');
            return;
        }
        $row  = $this->videoModel->getVideoRowWithId($id);
        if($row===false){
            JSON_Util::SendErrorResponse('無此影片!');
            return;
        }
        if($row['STATE']!='uploading'){
            JSON_Util::SendErrorResponse('此影片不能再上傳!');
            return;
        }
        
        if($row['OWNER']!=$user){
            JSON_Util::SendErrorResponse('你沒有權限上傳至此檔案!');
            return;
        }
        $output = array();
        $output['upload_status'] = 'continue';
        $output['byte_offset'] = $this->videoModel->getRawFileSizeFromFileSystem($id, $row['RAW_EXTENSION']);
        JSON_Util::SendSuccessResponse($output);
    }
    
    public function optimizeVideo($id){
        $user = $this->session->userdata('ACCOUNT');
        if(!$user){
            JSON_Util::SendErrorResponse('你尚未登入!');
            return;
        }
        $row  = $this->videoModel->getVideoRowWithId($id);
        if($row['OWNER']!=$user){
            JSON_Util::SendErrorResponse('你沒有權限操作此檔案!');
            return;
        }
        if($row===false){
            JSON_Util::SendErrorResponse('無此影片!');
            return;
        }
        if($row['STATE']!='uploaded'){
            JSON_Util::SendErrorResponse('此影片還未上傳完畢!');
            return;
        }
        if($row['STATE']=='processing'){
            JSON_Util::SendSuccessResponse('此影片已經在優化!');
            return;
        }
        
        
        ini_set('max_execution_time', 0);
        $this->videoModel->optimizeVideo($row);
        JSON_Util::SendSuccessResponse('');
    }
    public function checkVideoOptimized($id){
        $user = $this->session->userdata('ACCOUNT');
        if(!$user){
            JSON_Util::SendErrorResponse('你尚未登入!');
            return;
        }
        $row  = $this->videoModel->getVideoRowWithId($id);
        if($row['OWNER']!=$user){
            JSON_Util::SendErrorResponse('你沒有權限操作此檔案!');
            return;
        }
        if($row===false){
            JSON_Util::SendErrorResponse('無此影片!');
            return;
        }
        if($row['STATE']=='online'){
            JSON_Util::SendSuccessResponse(true);
        }else{
            JSON_Util::SendSuccessResponse(false);
        }
    }
    //local cli methods, these methods can only be executed on the local machine
    public function changeVideoState($id, $state){
        if( $this->input->is_cli_request()){
            $this->videoModel->changeVideoState($id, $state);
            echo 'the video with id: '.$id.' has changed state to '.$state.PHP_EOL;
        }else{
            JSON_Util::SendErrorResponse('你沒有權限進行此操作!');
        }
    }
    public function runOptScript($raw_filename, $output_filename, $id){
        if( $this->input->is_cli_request()){
            if($this->videoModel->runOptScript($raw_filename, $output_filename, $id)){
                echo '影片優化成功'.PHP_EOL;
                return; 
            }else{
                JSON_Util::SendErrorResponse('資料庫錯誤!');
                return;
            }
        }else{
            JSON_Util::SendErrorResponse('你沒有權限進行此操作!');
        }
    }
    
    public function turnVideoOnline($id, $state, $vid_length){
        if( $this->input->is_cli_request()){
            //$this->videoModel->changeVideoState($id, $state);
            echo $id." : ".$state." : ".$vid_length.PHP_EOL;
        }else{
            JSON_Util::SendErrorResponse('你沒有權限進行此操作!');
        }
    }
    
}