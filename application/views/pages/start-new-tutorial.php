<div style="height: 100%;">
    <div class="fill height wrapper">
        <div class="ts narrow container">

            <div class="ts ordered stackable top attached mini steps">
                <a class="active step" data-toggle="tab" title="Step 1" href="#step1">
                    <i class="browser icon"></i>
                </a>
                <a class="step" data-toggle="tab" title="Step 2" href="#step2">
                    <i class="calendar icon"></i>
                </a>
                <a class="step" data-toggle="tab" title="Step 3" href="#step3">
                    <i class="write icon"></i>
                </a>
                <a class="step" data-toggle="tab" title="Step 4" href="#step4">
                   <i class="list layout icon"></i>
                </a>
                <a class="step" data-toggle="tab" title="Step 5" href="#step5">
                    <i class="checkmark end icon"></i>
                </a>
            </div>  
            <div class="ts padded attached segment">
            <div class="ts relaxed stackable grid">
                <div class="ts six wide column raised info card" style="height: 500px;">
                    <div class="image">
                        <img id="course-preview-img" src="https://tocas-ui.com/assets/img/15d7510.png">
                    </div>
                    <div class="content">
                        <div class="header">這裡是放"課程標題"的位置!!!</div>
                        <div class="description">
                            這裡是放"課程簡述"的地方, 不需要太多字
                        </div>
                    </div>
                    <div class="extra content">
                        <div class="right floated author">
                            <img class="ts circular avatar image" src="<?php echo $user_data['PICTURE_URL'];?>">
                        </div>
                    </div>
                </div>  
                <form class="ts ten wide column form">
                    <div class="formSection" id='step1'>
                    <div class="field">
                        <label>課程標題 (16個字以內)</label>
                        <input type="text" id="tutorial_title" name="TITLE" placeholder="請輸入課程標題" maxlength="16">
                    </div>
                    <div class="field">
                        <label>課程程度</label>
                        <select class="ts basic inverted dropdown" id="tutorial_level"  name="TUTORIAL_LEVEL">
                            <option value="入門" selected="selected">入門</option>
                            <option value="進階">進階</option>
                            <option value="專業">專業</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>課程簡短描述 (49個字以內)</label>
                        <textarea id="short_intro" name="SHORT_INTRO" placeholder="輸入描述" maxlength="49" rows="2"></textarea>
                    </div>
                    <div class="field">
                        <label>選擇課程封面</label>
                        <div class="wrapper">
                          <div class="file-upload">
                            <i class="tiny cloud upload icon">
                            <input type="file" id="picture_file_upload" accept="image/*">
                            </i>
                          </div>
                        </div>
                    </div>
                    </div>
                    <div class="formSection" id='step2' style='display:none;'>
                    <div class="field">
                        <label>開課人數 (最少25人)</label>
                        <input type="text" id="req_student_count" name="REQ_STUDENT_COUNT" placeholder="開課最少人數" value="25">
                    </div>
                    <div class="field">
                        <label>開課日期</label>
                        <input type="text" id="prepare_days"  name="PREPARE_DAYS">
                    </div>
                    <div class="field">
                        <label>預計總上課時數(小時)</label>
                        <input type="text" id="predicted_course_length" name="PREDICTED_COURSE_LENGTH" placeholder="15" value="15">
                    </div>
                    <div class="field">
                        <label>預計上課地點</label>
                        <input type="text" id="predicted_course_location" name="PREDICTED_COURSE_LOCATION" placeholder="第一演講廳" value="第一演講廳">
                    </div>
                    </div>
                    <div class="formSection" id='step3' style='display:none;'>
                    <div class="field">
                        <label>上課所需之物品</label>
                        <textarea name="NEEDED_ITEMS" maxlength="128" rows="3"></textarea>
                    </div>
                    <div class="field">
                        <label>學生必須具備之背景知識技能</label>
                        <textarea name="REQ_KNOWLEDGE" maxlength="128" rows="3"></textarea>
                    </div>
                    <div class="field">
                        <label>上完這個課程，學生能夠做出或者做到什麼</label>
                        <textarea name="COURSE_OUTPUT" maxlength="128" rows="3"></textarea>
                    </div>
                    </div>
                    <div class="formSection" id='step4' style='display:none;'>
                    <div class="field">
                        <div id="session_div"><div>
                        單元1：<input class="session_input" type="text" placeholder="輸入單元名稱" style="display: inline-block;width: 70%;"><button class="ts basic negative icon button remove_session_but"><i class="remove icon"></i><button></div>
                        </div>
                    </div>
                        <button class="ts basic positive icon button" onclick="return addNewSession();">
                        <i class="plus icon"></i>
                    </div>

                    <div class="formSection" id='step5' style='display:none;'>
                    編寫課程介紹
                        <div class="sixteen wide column">
                        <!-- Simple MDE 編輯器 -->
                        <textarea id="mde"></textarea>
                        <!-- / Simple MDE 編輯器 -->
                        <!-- <div id="editor-container"></div> -->
                        <br>
                        <div style="text-align: center;">
                            <input type="button" id="sub_button" class="ts large basic positive icon button" onclick="submitNewTutorial();" disabled value="提交資料，等待審查">
                            <!-- <button id="sub_button" class="ts large basic positive icon button" onclick="submitNewTutorial();" disabled>提交資料，等待審查</button> -->
                        </div>
                        <br>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
//觸發AgilePoint審核流程    
function callAgilepoint(postData,tutorial_id) {
    var email = '<?php echo $user_data['EMAIL']?>';
    var name =  '<?php echo $user_data['NAME']?>';
    var imgURL = $('#course-preview-img').attr('src');
    var timestamp = new Date().getUTCMilliseconds();
    var dt = new Date().toLocaleString(); 
    var pin = "Workshop Course - " + dt + ":" + timestamp;

    var pid = GetPID();
    var uuid = GetUUID(); 
    var data = {
            "ProcInstName": pin,
            "ProcessID": pid,
            "WorkObjID": uuid,
            "blnStartImmediately": true,
            "Initiator": name+" - "+email,//觸發流程之人(欲開課之老師)
            "Attributes": []
    };
    var preS = "/pd:AP/pd:formFields/pd:tbxCourse";
    var boxName = ["TITLE","SHORT_INTRO","TUTORIAL_LEVEL","PREPARE_DAYS","REQ_STUDENT_COUNT","PREDICTED_COURSE_LENGTH","PREDICTED_COURSE_LOCATION","imgURL","NEEDED_ITEMS","REQ_KNOWLEDGE","COURSE_OUTPUT","SESSION","INTRODUCTION","Email","Name","ID"];
    var inputValue = [postData['TITLE'],postData['SHORT_INTRO'],postData['TUTORIAL_LEVEL'],postData['PREPARE_DAYS'],postData['REQ_STUDENT_COUNT'],postData['PREDICTED_COURSE_LENGTH'],postData['PREDICTED_COURSE_LOCATION'],imgURL,postData['NEEDED_ITEMS'],postData['REQ_KNOWLEDGE'],postData['COURSE_OUTPUT'],JSON.stringify(postData['TUTORIAL_SESSION_TITLES']),postData['INTRODUCTION'],email,name,tutorial_id];
    for(var i=0; i < boxName.length; i++){
        data.Attributes.push({Name:preS+boxName[i],Value:inputValue[i]});
    }
    var settings = {
        "async": false,
        "crossDomain": true,
        "url": "https://agilepoint.ntut.edu.tw:13490/AgilePointServer/Workflow/CreateProcInst",
        "method": "POST",
        "headers": {
            "authorization": "Basic V0lOLVRKSFVQVUdDQVVQXHNuYWlscGp3MTIwMjpwQHNzdzByZA==",
            "content-type": "application/json",
            "cache-control": "no-cache",
            "postman-token": "7aaaf79e-a8c7-8d2d-d387-20752abdd113"
        },        
        "processData": false,
        "data":JSON.stringify(data)
    }     
    $.ajax(settings).done();
    //alert(resultProcInstID+',/n'+resultWorkObjectID);
    // var data = JSON.stringify({
    //   "ProcInstName": pin,
    //   "Initiator": "測試人員Peggy",
    //   "ProcessID": pid,
    //   "WorkObjID": "80C7005056AB4B79117754B05F0E283F",
    //   "blnStartImmediately": "true",
    //   "Attributes": [
    //     {
    //       "Name": "/pd:AP/pd:formFields/pd:tbxCourseTITLE",
    //       "Value": "test2吧!!!YOOOOOO"
    //     },
    //     {
    //       "Name": "/pd:AP/pd:formFields/pd:tbxCourseSHORT_INTRO",
    //       "Value": "這是簡短描述!"
    //     },
    //     {
    //       "Name": "/pd:AP/pd:formFields/pd:tbxCourseTUTORIAL_LEVEL",
    //       "Value": "專業!!!0524"
    //     },
    //     {
    //       "Name": "/pd:AP/pd:formFields/pd:tbxCoursePREPARE_DAYS",
    //       "Value": "05/25/2017"
    //     },
    //     {
    //       "Name": "/pd:AP/pd:formFields/pd:tbxCourseREQ_STUDENT_COUNT",
    //       "Value": "224"
    //     },
    //     {
    //       "Name": "/pd:AP/pd:formFields/pd:tbxCoursePREDICTED_COURSE_LENGTH",
    //       "Value": "54"
    //     },
    //     {
    //       "Name": "/pd:AP/pd:formFields/pd:tbxCoursePREDICTED_COURSE_LOCATION",
    //       "Value": "lab535"
    //     },
    //     {
    //       "Name": "/pd:AP/pd:formFields/pd:tbxCourseimgURL",
    //       "Value": "http://130.211.173.219/ntutv01"
    //     },
    //     {
    //       "Name": "/pd:AP/pd:formFields/pd:tbxCourseNEEDED_ITEMS",
    //       "Value": "帶一科學習的心!!0524"
    //     },
    //     {
    //       "Name": "/pd:AP/pd:formFields/pd:tbxCourseREQ_KNOWLEDGE",
    //       "Value": "無局限的想像思維吧!!!0524"
    //     },
    //     {
    //       "Name": "/pd:AP/pd:formFields/pd:tbxCourseCOURSE_OUTPUT",
    //       "Value": "了解金工製作的基本技術!!!0524"
    //     },
    //     {
    //       "Name": "/pd:AP/pd:formFields/pd:tbxCourseSESSION",
    //       "Value": "單元1: 金工安全與環境介紹"
    //     },
    //     {
    //       "Name": "/pd:AP/pd:formFields/pd:tbxCourseINTRODUCTION",
    //       "Value": "這是課程Detail吧!!!0524"
    //     },
    //     {
    //       "Name": "/pd:AP/pd:formFields/pd:tbxCourseEmail",
    //       "Value": "alanwei1202@gmail.com"
    //     }
    //   ]
    // });
}

function GetPID() {
    var result;
    var procName = "WORKSHOP_FLOW";//填上流程名稱
    var settings = {
        "async": false,
        "crossDomain": true,
        "url": "https://agilepoint.ntut.edu.tw:13490/AgilePointServer/Workflow/GetReleasedPID/"+procName,
        "method": "GET",
        "headers": {
            "authorization": "Basic V0lOLVRKSFVQVUdDQVVQXHVzZXIwNjpwQHNzdzByZA==",
            "cache-control": "no-cache",
            "postman-token": "91280bb5-bf39-0395-5f92-7fbc742a9efd"
        }
    }
    $.ajax(settings).done(function (response) {
        result = response.GetReleasedPIDResult;
    });
    return result;
}

function GetUUID() {
    var result;
    var settings = {
      "async": false,
      "crossDomain": true,
      "url": "https://agilepoint.ntut.edu.tw:13490/AgilePointServer/Workflow/GetUUID",
      "method": "GET",
      "headers": {
        "authorization": "Basic V0lOLVRKSFVQVUdDQVVQXHNuYWlscGp3MTIwMjpwQHNzdzByZA==",
        "cache-control": "no-cache",
        "postman-token": "09753934-d309-5ea0-0c58-023ddea3d3fa"
      }
    }
    $.ajax(settings).done(function (response) {
        result = response.GetUUIDResult;
    });
    return result;
}
//End觸發AgilePoint審核流程

//tocas dropdown list needs
ts('.ts.dropdown:not(.basic)').dropdown();
$( "#tabs" ).tabs();
//日曆選擇
    $( function() {
        $( "#prepare_days" ).multiDatesPicker({
            minDate: 0, // today
            dateFormat: "y-mm-dd"
        });
    });
    //頁籤Tab效果
    function nextTab(elem) {
        $(elem).next().find('a[data-toggle="tab"]').click();
    }
    function prevTab(elem) {
        $(elem).prev().find('a[data-toggle="tab"]').click();
    }
    
    $('.step').on('click',function(){
        $('.steps a').removeClass('active');
        $(this).addClass('active');
        $('form .formSection').attr('style','display:none');
        var $prehref = $(this).attr('href');
        $($prehref).attr('style','display:block');
    });
    //頁籤Tab效果 END
    //Editor 效果
    var simplemde = new SimpleMDE({ element: document.getElementById("mde"), spellChecker: false, status: false });
    
    //End Editor

    function gatherAllInfo(){
        if(!allInfoFilled()){ alert('資料填寫不完整!');return;}
        var output = {};
        $('input[type=text]').each(function(){
            var name = $(this).attr('name');
            if(typeof name!=='undefined'){
                output[name] = $(this).val();
            }
        });

        //課程程度 下拉式選單
        output['TUTORIAL_LEVEL'] = $('#tutorial_level').val();
        $('textarea').each(function(){
            var name = $(this).attr('name');
            if(typeof name!=='undefined'){
                output[name] = $(this).val();
            }
        });
        var tut_sessions = ['課程介紹'];
        $('.session_input').each(function(){
            tut_sessions.push($(this).val());
        });
        output['TUTORIAL_SESSION_TITLES'] = tut_sessions;
        //get summernote code
        // output['INTRODUCTION'] = $('#summernote').summernote('code');        
        output['INTRODUCTION'] = JSON.stringify(simplemde.value());        
        output['IMAGE_ID'] = tutorialImgRow['IMAGE_ID'];
        uuid = GetUUID();
        return output;
    }
    function allInfoFilled(){
        var filled = true;
        $('input[type=text]').each(function(){
            var name = $(this).attr('name');
            if(typeof name!=='undefined'){
                if($(this).val().length==0) {
                    filled = false;
                    return;
                }
            }
        });
        if(!filled) return false;
        $('textarea').each(function(){
            var name = $(this).attr('name');
            if(typeof name!=='undefined'){
                if($(this).val().length==0) {
                    filled = false;
                    return;
                }
            }
        });
        if(!filled) return false;
        //規劃單元如果沒有單元也不行
        if($('.session_input').length<1) return false;
        $('.session_input').each(function(){
            if($(this).val().length==0) {
                filled = false;
                return;
            }
        });
        if(!filled) return false;
        if(tutorialImgRow==null) return false;
        return true;
    }
    function updateSubmitButton(){
        var filled = allInfoFilled();
        if(filled){
            $('#sub_button').attr('disabled', false);
        }else{
            $('#sub_button').attr('disabled', true);
        }
    }
    function submitNewTutorial(){
        var post_data = gatherAllInfo();
        if(post_data === false) return;
        
        SimpleMsgHandler.handleRequest(true, TutorialController.submitNewTutorial(post_data), function(response){
            swal(
              '申請開課資訊已送出',
              '請等待 1 ~ 3 天審核流程!',
              'success'
            );
            callAgilepoint(post_data,response.data);
            window.location.href = "<?php echo base_url().'index.php/pages/view/search-tutorial';?>";
        });
    }
    var tutorialImgRow = null;
    function removeSession(jq_button){
        jq_button.closest('div').remove();
        adjustSessionIndex();
        return false;
    }
    function adjustSessionIndex(){
        $('.session_input').closest('div').each(function(){
            var row_idx = $(this).index()+1;
            $(this).find('span').empty();
            $(this).find('span').append('單元'+row_idx+'：');
        });
    }
    function addNewSession(){
        var session_count = $('.session_input').length;
        var str ='<div>單元'+(session_count+1)+'：<input class="session_input" type="text" placeholder="輸入單元名稱" style="display: inline-block; width: 70%;"><button class="ts basic negative icon button remove_session_but"><i class="remove icon"></i><button><br></div>';
        $('#session_div').append(str);
        updateSubmitButton();
        return false;
    }
    function updatePreviewIconText(){
        //only updates the text related
        var title = $('#tutorial_title').val();
        var short_intro = $('#short_intro').val();
        $('.card .content .header').empty();
        $('.card .content .description').empty();
        $('.card .content .header').append(title);
        $('.card .content .description').append(short_intro);
    }
    function validateMime(target, accepted_mimes){
        for(var i=0;i<accepted_mimes.length;++i){
            if(target==accepted_mimes[i]) return true;
        }
        return false;
    }
    $(function(){
        //var preview_related_inputs = ['#tutorial_title', '#short_intro','#discount_req_price','#req_student_count'];
        var preview_related_inputs = ['#tutorial_title', '#short_intro','#req_student_count']
        for(var i=0;i<preview_related_inputs.length;++i){
            var id = preview_related_inputs[i];
            $(id).on('propertychange change click keyup input paste', function(){
                updatePreviewIconText();
            });
        }
        $(document).on('click','.remove_session_but', function(e){
            e.preventDefault();
            removeSession($(this));
            updateSubmitButton();
        });
        $('#picture_file_upload').on('change', function(){
            var file_node = document.getElementById('picture_file_upload');
            if(file_node.files.length>0){
                var file = file_node.files[0];
                if(validateMime(file.type,['image/jpeg','image/gif','image/png'])){
                    //see if it is 
                    SimpleMsgHandler.handleRequest(false, ImageController.uploadImage(file), function(resp){
                        tutorialImgRow = resp.data;
                        $('#course-preview-img').attr('src', tutorialImgRow['URL']);
                        updateSubmitButton();
                    });
                }else{
                    alert('不支援的檔案格式!');
                }
            }
        });
        $('input[type=text]').on('propertychange change click keyup input paste', function(){
            updateSubmitButton();
        });
        $(document).on('propertychange change click keyup input paste','.session_input',function(){
            updateSubmitButton();
        });
        $('textarea').on('propertychange change click keyup input paste', function(){
            updateSubmitButton();
        });
        
    });
</script>