<div class="ts narrow container">
    <div class="ts big fluid dashed insetted slate">
        <span class="header">吾時吾課，食時嗑課</span>
        <span class="description">心中充滿各式各樣的點子，就試試在這片沃土撒下吧。</span>
    </div>
    <div class="ts ordered stackable top attached mini steps">
        <a class="active step" data-toggle="tab" title="Step 1" href="#step1">
            <div class="content"><div class="title">基本</div><div class="description">課程資訊</div></div>
        </a>
        <a class="step" data-toggle="tab" title="Step 2" href="#step2">
            <div class="content"><div class="title">如何進行</div><div class="description">型態與方式</div></div>
        </a>
        <a class="step" data-toggle="tab" title="Step 3" href="#step3">
           <div class="content"><div class="title">課前</div><div class="description">學生所需</div></div>
        </a>
        <a class="step" data-toggle="tab" title="Step 4" href="#step4">
           <div class="content"><div class="title">單元</div><div class="description">安排</div></div>
        </a>
        <a class="step" data-toggle="tab" title="Step 5" href="#step5">
           <div class="content"><div class="title">時間</div><div class="description">規劃</div></div>
        </a>

        <a class="step" data-toggle="tab" title="Step 6" href="#step6">
            <div class="content"><div class="title">詳細</div><div class="description">課程內容</div></div>
        </a>
    </div> 
    <div class="ts very padded attached tertiary segment">
        <div class="ts relaxed stackable grid">
            <div class="ts five wide column raised info card" id='coursePreview' style="height: 450px;">
                <div class="ts link small image" id="courseCover" data-tooltip-position="top center" data-tooltip="試試看上傳課程宣傳封面吧!">
                    <img id="course-preview-img" src="<?php echo base_url();?>asset/img/4-3.png">
                    <i class="negative mouse pointer icon" id='imgIcon'></i>
                    <input type="file" class="fileUpload" id="picture_file_uploadNew" accept="image/*">
                </div>
                <div class="content">
                    <div class="header">吸引人的"課程標題"</div>
                    <div class="description">
                        "課程文宣標語"不需要太多文字!
                    </div>
                </div>
                <div class="extra content">
                    <div class="right floated author">
                        <img class="ts circular avatar image" src="<?php echo $user_data['PICTURE_URL'];?>">
                    </div>
                </div>
            </div>  
            <form class="ts large form stretched column">
    <!-- ................................ 基本要素 ...................................... -->
                <div class="formSection" id='step1'>
                    <div class="field">
                        <label>課程標題 (16個字以內)</label>
                        <input type="text" id="tutorial_title" name="TITLE" placeholder="請輸入課程標題" maxlength="16">
                    </div>
                    <div class="field">
                        <label>課程文宣標語 (49個字以內)</label>
                        <textarea id="short_intro" name="SHORT_INTRO" placeholder="輸入描述" maxlength="49" rows="2"></textarea>
                    </div>
                    <div class="ts three column grid">
                        <div class="column field">
                            <label>課程宣傳封面 (圖檔)</label>
                            <div class="wrapper" data-tooltip-position="top center" data-tooltip="試試看上傳課程宣傳封面吧!">
                              <div class="file-upload">
                                <i class="circular tiny cloud upload icon"></i>
                                <input type="file" class="fileUpload" id="picture_file_upload2" accept="image/*">
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
    <!-- ................................ 人數地點 ...................................... -->
                <div class="formSection" id='step2' style='display:none;'>
                    <div class="fields">
                        <div class="field">
                            <label>課程程度</label>
                            <select class="ts basic positive dropdown" id="tutorial_level"  name="TUTORIAL_LEVEL">
                                <option value="基礎" selected="selected">基礎</option>
                                <option value="進階">進階</option>
                                <option value="高階">高階</option>
                            </select>
                        </div>
                        <div class="field">
                            <label>課程類別</label>
                            <select class="ts basic primary dropdown" id="tutorial_category"  name="CATEGORY">
                                <option value="手作">手作</option>
                                <option value="語言">語言</option>
                                <option value="科技">科技</option>
                                <option value="程式">程式</option>
                                <option value="商業">商業</option>
                                <option value="生活">生活</option>
                                <option value="運動">運動</option>
                                <option value="其他">其他</option>
                            </select>
                        </div>
                        <div class="field">
                            <label>進行方式</label>
                            <select class="ts basic info dropdown" id="tutorial_program"  name="PROGRAM">
                                <option value="體驗式" selected="selected">體驗式</option>
                                <option value="關懷式">關懷式</option>
                                <option value="實作式">實作式</option>
                            </select>
                        </div>
                        <div class="field">
                            <label>教學方式</label>
                            <select class="ts basic warning dropdown" id="tutorial_method"  name="METHOD">
                                <option value="業師雙師制" selected="selected">業師雙師制</option>
                                <option value="專題製作">專題製作</option>
                                <option value="SPOC">SPOC</option>
                                <option value="MOOC">MOOC</option>
                                <option value="PBL">PBL</option>
                                <option value="其他">其他</option>
                            </select>
                        </div>
                    </div>
                    <div class="fields">
                        <div class="field">
                            <label>開課人數 (最少25人)</label>
                            <input type="text" id="req_student_count" name="REQ_STUDENT_COUNT" placeholder="開課最少人數" value="25">
                        </div>
                        <div class="field">
                            <label>預計上課地點</label>
                            <input type="text" id="predicted_course_location" name="PREDICTED_COURSE_LOCATION" placeholder="星巴克、三創、光華、大稻埕">
                        </div>
                    </div>
                </div>
    <!-- ................................ 所需條件 ...................................... -->
                <div class="formSection" id='step3' style='display:none;'>
                    <div class="fields">
                        <div class="field">
                            <label>上課所需之物品</label>
                            <textarea name="NEEDED_ITEMS" maxlength="128" rows="2" placeholder="某個軟體、某種工具或某種材料"></textarea>
                        </div>
                        <div class="field">
                            <label>學生必須具備之背景知識技能</label>
                            <textarea name="REQ_KNOWLEDGE" maxlength="128" rows="2" placeholder="比較進階的課程，建議先跟學生說明必備的知識，幫助學生了解這堂課"></textarea>
                        </div>
                    </div>
                    <div class="field">
                        <label>上完這堂課程，預期學生的學習成效</label>
                        <textarea name="COURSE_OUTPUT" maxlength="128" rows="2" placeholder="完成某種作品、達到哪種目標甚至是得到什麼結果"></textarea>
                    </div>
                </div>
    <!-- ................................ 單元規劃 ...................................... -->
                <div class="formSection" id='step4' style='display:none;'>
                    <div class="field">
                        <!-- <div id="session_div">
                            <div>
                        <span>單元1</span><input class="session_input" type="text" placeholder="輸入單元名稱" style="display: inline-block;width: 70%;"><button class="ts basic negative icon button remove_session_but"><i class="remove icon"></i></button>
                            </div>
                        </div> -->
                        <div class="ts large middle aligned selection list" id="session_div">
                            <div class="item">
                                <div class="ts large primary label">單元 1</div>
                                <div class="content">
                                    <div class="ts underlined fluid input">
                                        <input type="text" class="session_input" placeholder="輸入單元名稱">
                                    </div>
                                </div>
                                <div class="right floated content">
                                    <button class="ts mini basic inverted negative icon button remove_session_but">
                                        <i class="remove icon"></i>
                                    </button>
                                    <button class="ts mini basic inverted positive icon button" onclick="return addNewSession();">
                                        <i class="add icon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <button class="ts basic positive icon button" onclick="return addNewSession();">
                    <i class="plus icon"></i>
                    </button> -->
                </div>
    <!-- ................................ 時間規劃 ...................................... -->
                <div class="formSection" id='step5' style='display:none;'>
                    <div class="field">
                        <label>開課日期</label>
                        <input type="text" id="prepare_days"  name="PREPARE_DAYS">
                    </div>
                    <div class="field">
                        <label>預計總上課時數(小時)</label>
                        <input type="text" id="predicted_course_length" name="PREDICTED_COURSE_LENGTH" placeholder="15" value="15">
                    </div>
                    <div class="field ts grid">
                        <div class="four wide column">
                            <div class="ts primary segment" id='external-events' data-tooltip-position="bottom center" data-tooltip="拖曳單元色塊至右側行事曆上">
                                <p>單元安排</p>
                                <div class='fc-event'>單元 1</div>
                                <div class='fc-event'>單元 2</div>
                                <div class='fc-event'>單元 3</div>
                                <div class='fc-event'>單元 4</div>
                                <div class='fc-event'>單元 5</div>
                            </div>
                        </div>
                        <div class="twelve wide column">
                            <div class="ts clearing segment">
                                <div id='calendar'></div>
                            </div>
                        </div>
                    </div>
                </div>
    <!-- ................................ 詳細內容 ...................................... -->
                <div class="formSection" id='step6' style='display:none;'>
                編寫詳細課程內容
                    <div class="row">
                    <!-- Simple MDE 編輯器 -->
                    <textarea id="mde"></textarea>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="right floated column">
                    <div class="ts buttons">
                        <button class="ts negative button" id='prev-step'>上一步</button>
                        <div class="or"></div>
                        <button class="ts primary pulsing button" id='next-step'>下一步</button>
                        <div class="or"></div>
                        <button id="btnSubmit" class="ts large positive disabled button">送出</button>
                    </div>
                </div>
            </div>
            <!-- <div id='wrap'>
                <div id='calendar'></div>
                <div style='clear:both'></div>
            </div> -->
        </div><!-- grid end-->
    </div>
</div>
<script>
$(function(){
        //處理按鈕 '上一步'&'下一步'
        $('#prev-step').addClass('disabled');
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
        $(document).on('click','#btnSubmit', function(e){
            e.preventDefault();//防止在Form裡的Button執行預設的Submit行為!
            submitNewTutorial();
        });
        $('.fileUpload').on('change', function(){
            var tagID = $(this).attr('id');
            var file_node = document.getElementById(tagID);
            if(file_node.files.length>0){
                var file = file_node.files[0];
                if(validateMime(file.type,['image/jpeg','image/gif','image/png'])){
                    //see if it is 
                    SimpleMsgHandler.handleRequest(false, ImageController.uploadImage(file), function(resp){
                        tutorialImgRow = resp.data;
                        $('#course-preview-img').attr('src', tutorialImgRow['URL']);
                        updateSubmitButton();
                        $('#imgIcon').removeClass('negative mouse pointer').addClass('positive check');
                        $('#imgIcon').attr('style','z-index: 1;')
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
        /* initialize the external events
        -----------------------------------------------------------------*/

        $('#external-events .fc-event').each(function() {

            // store data so the calendar knows to render an event upon drop
            $(this).data('event', {
                title: $.trim($(this).text()), // use the element's text as the event title
                stick: true // maintain when user navigates (see docs on the renderEvent method)
            });

            // make the event draggable using jQuery UI
            $(this).draggable({
                zIndex: 999,
                revert: true,      // will cause the event to go back to its
                revertDuration: 0  //  original position after the drag
            });

        });

        /* initialize the calendar
        -----------------------------------------------------------------*/

        $('#calendar').fullCalendar({
            theme: true,
            locale: 'zh-tw',
            header: {
                left: 'prev,next',
                center: 'title',
                right: 'today,month,agendaDay,listMonth'//month,agendaWeek,
            },
            contentHeight: 464,
            navLinks: true, // can click day/week names to navigate views
            editable: true,
            eventLimit: true, // allow "more" link when too many events
            droppable: true, // this allows things to be dropped onto the calendar
            drop: function() {
                // is the "remove after drop" checkbox checked?
                // if ($('#drop-remove').is(':checked')) {
                    // if so, remove the element from the "Draggable Events" list
                    $(this).remove();
                // }
            }
        });
    });
//頁籤Tab效果
function nextTab(elem) {
    // var check = $(elem).next().attr('href');
    // console.log(check);
    $(elem).next().click();
}
function prevTab(elem) {
    // var check = $(elem).prev().attr('href');
    // console.log(check);
    $(elem).prev().click();
}
$('.step').on('click',function(){
    $('.steps a').removeClass('active');
    $(this).addClass('active');
    // $('form .formSection').attr('style','display:none');
    $('form .formSection').hide();
    var $prehref = $(this).attr('href');
    // $($prehref).attr('style','display:block');
    $($prehref).show();
    // var stepHref = $(this).attr('href');
    if($prehref == '#step1'){
        $('#prev-step').addClass('disabled');
        $('#next-step').removeClass('disabled').addClass('pulsing');
        $('#coursePreview').attr('style','display:block').attr('style','height: 450px');
    }else{
        $('#coursePreview').attr('style','display:none');
        $('#prev-step').removeClass('disabled');
        if($prehref == '#step6'){
            $('#next-step').addClass('disabled').removeClass('pulsing');
        }else{
            if($prehref == '#step5'){
                $('#calendar').fullCalendar('render');//顯示行事曆
            }
            $('#next-step').removeClass('disabled').addClass('pulsing');
        }
    }
});
$("#next-step").on('click',function () {
        var $active = $('.steps a.active');
        // console.log($('.steps a.active').attr('href'));
        nextTab($active);
});
$("#prev-step").on('click',function () {
        var $active = $('.steps a.active');
        // console.log($('.steps a.active').attr('href'));
        prevTab($active);
});
//頁籤Tab效果 END
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
//$( "#tabs" ).tabs();

//下拉式選單 '其他' 跳出輸入視窗
$('form').find("select[id='tutorial_category'],[id='tutorial_method']").on('change',function(){
    var selectedTag = $(this);
    var optionSelected = $("option:selected", this);
    var valueSelected = this.value;
    // console.log(valueSelected);
    if(valueSelected=='其他'){
        // console.log(valueSelected);
        swal({
          title: '簡單定義您想像中的分類!',
          input: 'text',
          showCancelButton: true,
          inputValidator: function (value) {
            return new Promise(function (resolve, reject) {
              if (value) {
                resolve()
              } else {
                reject('你必須輸入一些文字!')
              }
            })
          }
        }).then(function (result) {
            swal({
                type: 'success',
                html: '您輸入的是：' + result
            })
            console.log(result);
            selectedTag.append($("<option></option>")
                    .attr("value","其他:"+result)
                    .text(result)
                    .attr('selected',true)); 
        })
    }
});
//日曆選擇
    $( function() {
        $( "#prepare_days" ).multiDatesPicker({
            minDate: 0, // today
            dateFormat: "y-mm-dd"
        });
        
    });
    
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
        //課程類別 下拉式選單
        output['CATEGORY'] = $('#tutorial_category').val();
        //進行方式 下拉式選單
        output['PROGRAM'] = $('#tutorial_program').val();
        //教學方式 下拉式選單
        output['METHOD'] = $('#tutorial_method').val();
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
            // $('#sub_button').attr('disabled', false);
            $('#btnSubmit').removeClass('disabled');
            $('#btnSubmit').addClass('pulsing');
        }else{
            // $('#sub_button').attr('disabled', true);
            $('#btnSubmit').addClass('disabled');
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
            ).then(function () {
                callAgilepoint(post_data,response.data);
                window.location.href = "<?php echo base_url().'index.php/pages/view/raise-fund-for-tutorial';?>";
            });
        });
    }
    var tutorialImgRow = null;
    function removeSession(jq_button){
        jq_button.closest('.item').remove();
        adjustSessionIndex();
        return false;
    }
    function adjustSessionIndex(){
        $('.session_input').closest('.item').each(function(){
            var row_idx = $(this).index()+1;
            $(this).find('.ts.label').empty();
            $(this).find('.ts.label').append('單元 '+row_idx);
        });
    }
    function addNewSession(){
        var session_count = $('.session_input').length;
        var str = "<div class='item'>\
                    <div class='ts large primary label'>單元 "+(session_count+1)+"</div>\
                    <div class='content'>\
                        <div class='ts underlined fluid input'>\
                            <input type='text' class='session_input' placeholder='輸入單元名稱'>\
                        </div>\
                    </div>\
                    <div class='right floated content'>\
                        <button class='ts mini basic inverted negative icon button remove_session_but'>\
                            <i class='remove icon'></i>\
                        </button>\
                        <button class='ts mini basic inverted positive icon button' onclick='return addNewSession();''>\
                            <i class='add icon'></i>\
                        </button>\
                    </div>\
                </div>";
        // var str ='<div><span>單元'+(session_count+1)+'</span><input class="session_input" type="text" placeholder="輸入單元名稱" style="display: inline-block; width: 70%;"><button class="ts basic negative icon button remove_session_but"><i class="remove icon"></i><button><br></div>';
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
</script>