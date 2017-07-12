function AnimationUtil(){
  
}
AnimationUtil.startWaitingAnimation = function(){
  var count = $('.waiting_screen_full').length;
  if(count === 0){
    $(document.body).append("<div class='waiting_screen_full'></div>");
  }
  $(document.body).addClass('waiting');
}
AnimationUtil.endWaitingAnimation = function(){
  $(document.body).removeClass('waiting');
}
function TutorialIconUtil(){
    
}
TutorialIconUtil.generateHtml = function(tutorial_data){
    if(tutorial_data.STATE=='pending'){
        return TutorialIconUtil.generateHtmlForPending(tutorial_data);
    }else if(tutorial_data.STATE=='raising_funds'){
        return TutorialIconUtil.generateHtmlForRaiseFund(tutorial_data);
    }else if(tutorial_data.STATE=='started'){
        return TutorialIconUtil.generateHtmlForStarted(tutorial_data);
    }else if(tutorial_data.STATE=='prepare'){
        return TutorialIconUtil.generateHtmlForPrepare(tutorial_data);
    }else{//failed
        return TutorialIconUtil.generateHtmlForFailed(tutorial_data);
    }
}
TutorialIconUtil.generateCard = function(wishing_data){
    var str = "<a class='ts positive card'>\
                  <div class='extra content'>\
                  <div class='actions'>\
                    <div class='ts icon buttons'>\
                        <button class='ts button btnDesire' id='"+wishing_data.WISH_ID+"'  name='"+wishing_data.NAME+"'>\
                            <i class='thumbs up icon'></i>\
                            <div class='ts floating circular basic primary label'>"+wishing_data.DESIRE+"</div>\
                        </button>\
                    </div>\
                  </div>\
                      <div class='floated author'>\
                          <img class='ts circular avatar image' src='"+wishing_data.STUDENT_PICTURE_URL+"'>"+wishing_data.NAME+"\
                      </div>\
                      <div class='meta'>\
                        <div>"+wishing_data.CREATE_TIME+"</div>\
                    　</div>\
                  </div>\
                  <div class='content'>\
                      <div class='header'>我想學 "+wishing_data.TITLE+"</div>\
                      <div class='description'>"+wishing_data.SHORT_INTRO+"</div>\
                  </div>\
                  <div class='symbol'>\
                        <i class='volume control phone icon'></i>\
                    </div>\
                  <div class='secondary extra content'>\
                      <i class='icon student'></i> 推薦教師："+wishing_data.TEACHER+"\
                  </div>\
                  <div class='tertiary extra content'>\
                      <i class='icon tags'></i> "+wishing_data.CATEGORY+"\
                  </div>\
              </a>";
    return str;
}
TutorialIconUtil.generateHtmlForPending = function(tutorial_data){
    var str =   "<a class='ts card' href='"+tutorial_data.TUTORIAL_URL+"'>\
                <div class='image'>\
                    <img src='"+tutorial_data.TUTORIAL_IMAGE_URL+"'/>\
                </div>\
                <div class='content'>\
                    <div class='header'>\
                        "+tutorial_data.TITLE+"\
                    </div>\
                    <div class='description'>\
                        "+tutorial_data.SHORT_INTRO+"\
                    </div>\
                </div>\
                <div class='extra content'>\
                    <div class='right floated author'>\
                        <img class='ts circular avatar image' src='"+tutorial_data.TEACHER_PICTURE_URL+"'>\
                    </div>\
                </div>\
            </a>";
    return str;
}
TutorialIconUtil.generateHtmlForRaiseFund = function(tutorial_data){
    var str =   "<a class='ts card' href='"+tutorial_data.TUTORIAL_URL+"'>\
                <div class='image'>\
                    <img src='"+tutorial_data.TUTORIAL_IMAGE_URL+"'/>\
                </div>\
                <div class='content'>\
                    <div class='header'>\
                        "+tutorial_data.TITLE+"\
                    </div>\
                    <div class='description'>\
                        "+tutorial_data.SHORT_INTRO+"\
                    </div>\
                </div>\
                <div class='extra content'>\
                    <div class='right floated author'>\
                        <img class='ts circular avatar image' src='"+tutorial_data.TEACHER_PICTURE_URL+"'>\
                    "+tutorial_data.TEACHER_ACCOUNT+"</div>\
                </div>\
                <div class='secondary extra content'>\
                    <div class='ts small active progress'>\
                        <div class='bar' style='width: 25%'></div>\
                    </div>\
                    <div class='ts three column grid'>\
                        <div class='column'>\
                            <strong>"+tutorial_data.STUDENT_COUNT+"人</strong>\
                            <br>\
                            已選課\
                        </div>\
                        <div class='column'>\
                            <strong>"+tutorial_data.REQ_STUDENT_COUNT+"</strong>\
                            <br>\
                            人數上限\
                        </div>\
                        <div class='column'>\
                            <strong>"+tutorial_data.RAISE_FUND_DAYS_REMAINING+"</strong>\
                            <br>\
                            天剩餘\
                        </div>\
                    </div>\
                </div>\
                <div class='tertiary extra content'>\
                    <i class='icon tags'></i> "+tutorial_data.CATEGORY+"\
                </div>\
            </a>";
    return str;
}
TutorialIconUtil.generateHtmlForPrepare = function(tutorial_data){
    var str =   "<a href='"+tutorial_data.TUTORIAL_URL+"'><div class='course-preview-panel col-sm-3'>\
                    <div style='display:inline-block;' class='course-preview-teacher-img-container'>\
                        <img class='course-preview-teacher-img' src='"+tutorial_data.TEACHER_PICTURE_URL+"'/>\
                    </div>\
                    <div class='course-preview-img-container'>\
                        <img class='course-preview-img' src='"+tutorial_data.TUTORIAL_IMAGE_URL+"'/>\
                    </div>\
                    <div class='course-preview-text'>\
                        <h3 class='course-preview-text-title'>\
                            "+tutorial_data.TITLE+"<br><small>"+tutorial_data.SHORT_INTRO+"</small>\
                        </h3>\
                        <br>\
                    </div>\
                    <div class='course-preview-fundraise'>\
                        <h4>預購價$"+tutorial_data.DISCOUNT_REQ_PRICE+", 已募得"+tutorial_data.STUDENT_COUNT+"/"+tutorial_data.REQ_STUDENT_COUNT+"人</h4>\
                    </div><div>募資成功,老師備課中!預計 "+tutorial_data.COURSE_PRESUMED_DATE+" 開課!</div>\
                </div></a>";
    return str;
}
TutorialIconUtil.generateHtmlForStarted = function(tutorial_data){
    var str =   "<a href='"+tutorial_data.TUTORIAL_URL+"'><div class='course-preview-panel col-sm-3'>\
                    <div style='display:inline-block;' class='course-preview-teacher-img-container'>\
                        <img class='course-preview-teacher-img' src='"+tutorial_data.TEACHER_PICTURE_URL+"'/>\
                    </div>\
                    <div class='course-preview-img-container'>\
                        <img class='course-preview-img' src='"+tutorial_data.TUTORIAL_IMAGE_URL+"'/>\
                    </div>\
                    <div class='course-preview-text'>\
                        <h3 class='course-preview-text-title'>\
                            "+tutorial_data.TITLE+"<br><small>"+tutorial_data.SHORT_INTRO+"</small>\
                        </h3>\
                        <br>\
                    </div>\
                    <div class='course-preview-fundraise'>\
                        <h4>價位$"+tutorial_data.REQ_PRICE+", 學生"+tutorial_data.STUDENT_COUNT+"人</h4>\
                    </div><div>正式開課囉!</div>\
                </div></a>";
    return str;
}
TutorialIconUtil.generateHtmlForFailed = function(tutorial_data){
    var str =   "<a href='"+tutorial_data.TUTORIAL_URL+"'><div class='course-preview-panel col-sm-3'>\
                    <div style='display:inline-block;' class='course-preview-teacher-img-container'>\
                        <img class='course-preview-teacher-img' src='"+tutorial_data.TEACHER_PICTURE_URL+"'/>\
                    </div>\
                    <div class='course-preview-img-container'>\
                        <img class='course-preview-img' src='"+tutorial_data.TUTORIAL_IMAGE_URL+"'/>\
                    </div>\
                    <div class='course-preview-text'>\
                        <h3 class='course-preview-text-title'>\
                            "+tutorial_data.TITLE+"<br><small>"+tutorial_data.SHORT_INTRO+"</small>\
                        </h3>\
                        <br>\
                    </div>\
                    <div class='course-preview-fundraise'>\
                        <h4>開課失敗</h4>\
                    </div><div>原因:"+tutorial_data.FAILED_REASON+"</div>\
                </div></a>";
    return str;
}
function TimeUtil(){
    
}
TimeUtil.secondsToHRTime = function(seconds){
    var hours = Math.floor(seconds/3600.0);
    var mins = Math.floor((seconds%3600)/60.0);
    var secs = seconds%60;
    var output = secs+"秒";
    if(mins>0){
        output = mins+"分"+output;
    }
    if(hours>0){
        return hours+"小時"+output;
    }
    return output;
}
// function WishingCardUnit(){

// }
// WishingCardUnit.generateCard = function(wishing_data){
//     var str = "<a class='ts card' href='"+#+"'>\
//                   <div class='content'>\
//                       <div class='header'>"+wishing_data.TITLE+"</div>\
//                       <div class='meta'>\
//                           <div>"+wishing_data.CREATE_TIME+"</div>\
//                       </div>\
//                       <div class='description'>"+wishing_data.SHORT_INTRO+"</div>\
//                   </div>\
//                   <div class='extra content'>\
//                       <div class='right floated author'>\
//                           <img class='ts circular avatar image' src='"+wishing_data.STUDENT_PICTURE_URL+"'>"+wishing_data.NAME+"\
//                       </div>\
//                   </div>\
//                   <div class='secondary extra content'>\
//                       <i class='icon unhide'></i> "+wishing_data.CATEGORY+"\
//                   </div>\
//                   <div class='tertiary extra content'>\
//                       <i class='icon thumbs up'></i> "+wishing_data.DESIRE+" 個喜歡\
//                   </div>\
//               </a>";
//     return str;
// }
// WishingCardUnit.generateCard = function(wishing_data){
//     var str = "<a class='ts card' href='"+#+"'>\
//                   <div class='content'>\
//                       <div class='header'>"+'123'+"</div>\
//                       <div class='meta'>\
//                           <div>"+'123'+"</div>\
//                       </div>\
//                       <div class='description'>"+'123'+"</div>\
//                   </div>\
//                   <div class='extra content'>\
//                       <div class='right floated author'>\
//                           <img class='ts circular avatar image' src='"+'123'+"'>"+'123'+"\
//                       </div>\
//                   </div>\
//                   <div class='secondary extra content'>\
//                       <i class='icon unhide'></i> "+'123'+"\
//                   </div>\
//                   <div class='tertiary extra content'>\
//                       <i class='icon thumbs up'></i> "+'123'+" 個喜歡\
//                   </div>\
//               </a>";
//     return str;
// }