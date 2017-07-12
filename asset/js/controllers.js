function SimpleMsgHandler(){
    
}
SimpleMsgHandler.handleRequest = function(run_anim, promise, success_callback){
    if(run_anim){
        AnimationUtil.startWaitingAnimation();//loading 時等待的燈箱感覺
    }
    promise.done(function(resp){                 
        if(resp.status == 'success'){
            success_callback(resp);
        }else{
            console.log(resp.data);
        }
    })
        .fail(function(resp){
            console.log("伺服器錯誤!js-- = " + JSON.stringify(resp));
        })
        .always(function(){
            if(run_anim)    AnimationUtil.endWaitingAnimation();
        })
}
function GenericController(){
    
}
GenericController.post = function(controller, method, post_data, args){
    return $.post(GenericController.getUrlFromControllerMethod(controller, method, args), post_data);
}
GenericController.get = function(controller, method, args){//args is array
    var url = GenericController.getUrlFromControllerMethod(controller, method, args);
    return $.get(url);
}
GenericController.getUrlFromControllerMethod = function(controller, method, args){
    var url = BASE_URL+"index.php/"+controller+"/"+method;
    if(typeof args !== 'undefined'){
        for(var i=0;i<args.length;++i){
            var a = args[i];
            url += '/'+ a;
        }
    }
    return url;
}
function ImageController(){
    
}
ImageController.uploadImage = function(file){
    //return GenericController.post('images','uploadImage', {'file':file});
    return $.ajax({
            url: GenericController.getUrlFromControllerMethod('images','uploadImage'),
            type: 'POST',
            processData: false,
            headers: {
                'Content-Type': file.type
            },
            data:file,
            async: true,
    });
}

function VideoController(){
    this.file = false;
    this.fileSize = false;
    this.extension = '';
    this.videoId = 0;
    this.chunk_callback=null;
    this.complete_callback=null;
    this.upload_complete_callback = null;
    this.fail_callback = null;
    this.progress_callback = null;
    this.byte_offset = -1;
    this.run_check_video_state_loop = true;
}
VideoController.chunkSize = 8388608;//8MiB
VideoController.getVideoUrl = function(id){
    return GenericController.getUrlFromControllerMethod('videos','getVideoData',[id]);
}
VideoController.createVideoPlaceHolder = function(ext, file_size){
    return GenericController.post('videos','createVideoPlaceHolder',{'ext':ext, 'file_size':file_size});
}
VideoController.uploadVideoChunk = function(id, chunk, progress_callback_internal){
    var url = GenericController.getUrlFromControllerMethod('videos','uploadVideoChunk');
    url += '/'+id;
    
    return $.ajax({
            'url': url,
            type: 'POST',
            processData: false,
            xhr: function () {
                var xhr = $.ajaxSettings.xhr();
                xhr.upload.onprogress = function (e) {
                    if (e.lengthComputable) {
                        if(typeof progress_callback_internal !== 'undefined')    progress_callback_internal(e);
                    }
                };
                return xhr;
            },

            data:chunk,
            async: true
    });
}
VideoController.checkVideoChunkOffset = function(id){
    var url = GenericController.getUrlFromControllerMethod('videos','checkVideoChunkOffset');
    url += '/'+id;
    return $.get(url);
}
VideoController.prototype.uploadWholeVideo = function(file){
    this.file = file;
    this.fileSize = file.size;
    this.byte_offset = 0;
    //get the extension
    
    this.extension = file.name.split('.').pop();
    var file_size = file.size;
    var cntl = this;
    VideoController.createVideoPlaceHolder(this.extension, file_size)
            .done(function(resp){
                if(resp.status=='success'){
                    cntl.videoId = resp.data;
                    cntl.loopUpload(0);
                }else{
                    if(cntl.fail_callback!=null)    cntl.fail_callback(resp);
                }
            })
            .fail(function(){
                if(cntl.fail_callback!=null)    cntl.fail_callback();
            });
}
VideoController.prototype.loopUpload = function(start_offset){
    var cntl = this;
    this.byte_offset = start_offset;
    var blob = cntl.file.slice(start_offset, Math.min(cntl.file.size, start_offset+VideoController.chunkSize));
    VideoController.uploadVideoChunk(cntl.videoId, blob, function(e){
                if(cntl.progress_callback!=null) cntl.progress_callback((cntl.byte_offset+e.loaded) /cntl.fileSize)
            })
            .done(function(resp){
                if(resp.status=='success'){
                    var upload_status = resp.data.upload_status;
                    if(cntl.chunk_callback!=null)   cntl.chunk_callback(resp.data);
                    
                    if(upload_status=='complete'){
                        //start optimization process
                        if(cntl.upload_complete_callback!=null) cntl.upload_complete_callback();
                        cntl.optimizeVideoAndEnterCheckLoop();
                    }else if(upload_status=='continue'){
                        var byte_offset = resp.data.byte_offset;
                        cntl.loopUpload(byte_offset);
                    }
                }else{
                    if(cntl.fail_callback!=null)    cntl.fail_callback(resp);
                    return;
                }
            })
            .fail(function(){
                setTimeout(function(){ 
                    cntl.checkAndUploadChunk();
                }, 3000);
            });
    
}
VideoController.prototype.checkAndUploadChunk = function(){
    var cntl = this;
    VideoController.checkVideoChunkOffset(cntl.videoId)
            .done(function(resp){
                if(resp.status=='success'){
                    if(upload_status=='complete'){
                        if(cntl.complete_callback!=null)    cntl.complete_callback();
                    }else if(upload_status=='continue'){
                        var byte_offset = resp.data.byte_offset;
                        cntl.loopUpload(byte_offset);
                    }
                }else{
                    if(cntl.fail_callback!=null)    cntl.fail_callback(resp);
                    return;
                }
            })
            .fail(function(){
                setTimeout(function(){ 
                    cntl.checkAndUploadChunk();
                }, 3000);
                
            });
}
VideoController.optimizeVideo = function(id){
    return GenericController.get('videos', 'optimizeVideo', [id]);
}
VideoController.checkVideoOptimized = function(id){
    return GenericController.get('videos', 'checkVideoOptimized', [id]);
}
VideoController.prototype.optimizeVideoAndEnterCheckLoop = function(){
    var cntl = this;
    VideoController.optimizeVideo(cntl.videoId)
            .done(function(resp){
                if(resp.status=='success'){
                    if(cntl.run_check_video_state_loop){
                        cntl.checkOptStateLoop();
                    }else{
                        if(cntl.complete_callback!=null)    cntl.complete_callback();
                    }
                }else{
                    if(cntl.fail_callback!=null)    cntl.fail_callback(resp);
                    return;
                }
            })
            .fail(function(){
                setTimeout(function(){ 
                    cntl.optimizeVideoAndEnterCheckLoop()
                }, 3000);
            });
}

VideoController.prototype.checkOptStateLoop = function(){
    var cntl = this;
    VideoController.checkVideoOptimized(cntl.videoId)
            .done(function(resp){
                if(resp.status=='success'){
                    var finished = resp.data;
                    if(finished){
                        if(cntl.complete_callback!=null)    cntl.complete_callback();
                    }else{
                        //not finished, check again in a few seconds
                        setTimeout(function(){ 
                            cntl.checkOptStateLoop();
                        }, 5000);
                    }
                }else{
                    if(cntl.fail_callback!=null)    cntl.fail_callback(resp);
                    return;
                }
            })
            .fail(function(){
                setTimeout(function(){ 
                    cntl.checkOptStateLoop();
                }, 3000);
            });
}
function TutorialController(){
    
}
TutorialController.submitNewTutorial = function(post_data){
    return GenericController.post('tutorials','submitNewTutorial', post_data);
}
TutorialController.submitNewWishing = function(post_data){
    return GenericController.post('tutorials','submitNewWishing', post_data);
}
TutorialController.searchForTutorial = function(state, keyword, subtab_idx){
    var post_data = {
        'state':state,
        'keyword':keyword,
        'subtab_idx':subtab_idx
    };
    return GenericController.post('tutorials','searchForTutorial', post_data);
}

TutorialController.searchForWishing = function(keyword, subtab_idx){
    var post_data = {
        'keyword':keyword,
        'subtab_idx':subtab_idx
    };
    return GenericController.post('tutorials','searchForWishing', post_data);
}
TutorialController.likeWishing = function(wish_id, account){
    var post_data = {
        'wish_id':wish_id,
        'account':account
    };
    return GenericController.post('tutorials','likeWishing', post_data);
}
// TutorialController.checkLikeWishing = function(wish_id, account){
//     var post_data = {
//         'wish_id':wish_id,
//         'account':account
//     };
//     return GenericController.post('tutorials','checkLikeWishing', post_data);
// }
TutorialController.authorizePendingTutorial = function(tut_id){
    var post_data = {
        'TUTORIAL_ID':tut_id
    }
    return GenericController.post('tutorials','authorizePendingTutorial', post_data);
}
TutorialController.buyTutorial = function(tut_id){
    var post_data = {
        'TUTORIAL_ID':tut_id
    };
    return GenericController.post('tutorials','buyTutorial', post_data);
}
TutorialController.registerVideoToTutorialSession = function(vid_id, session_id){
    var post_data = {
        'TUTORIAL_SESSION_ID':session_id,
        'VIDEO_ID':vid_id
    };
    return GenericController.post('tutorials','registerVideoToTutorialSession', post_data);
}
TutorialController.checkSessionStates = function(tutorial_id){
    var post_data = {
        'TUTORIAL_ID':tutorial_id
    };
    return GenericController.post('tutorials','checkSessionStates', post_data);
}
TutorialController.openTutorial = function(tutorial_id){
    var post_data = {
        'TUTORIAL_ID':tutorial_id
    };
    return GenericController.post('tutorials','openTutorial', post_data);
}
TutorialController.enterPrepareStage = function(tutorial_id){
    var post_data = {
        'TUTORIAL_ID':tutorial_id
    };
    return GenericController.post('tutorials','enterPrepareStage', post_data);
}