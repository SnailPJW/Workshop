//觸發AgilePoint審核流程
function callAgilepoint(postData) {
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
            "Initiator": "測試人員PJW",//觸發流程之人(欲開課之老師)
            "Attributes": []
    };
    var preS = "/pd:AP/pd:formFields/pd:tbxCourse";
    var boxName = ["Name","Brief","Level","OpenDate","StuNum","Len","ImgUrl","ItemNeed","Background","StuOutput","UnitPlan","Detail"];
    var inputValue = [postData['TITLE'],postData['SHORT_INTRO'],postData['TUTORIAL_LEVEL'],postData['PREPARE_DAYS'],postData['REQ_STUDENT_COUNT'],postData['PREDICTED_COURSE_LENGTH'],postData['URL'],postData['NEEDED_ITEMS'],postData['REQ_KNOWLEDGE'],postData['COURSE_OUTPUT'],"很多單元",postData['INTRODUCTION']];
    // $('.course').each(function(){
    //     inputValue.push(this.value);
    // })
    
    
    for(var i=0; i < boxName.length; i++){
        data.Attributes.push({Name:preS+boxName[i],Value:inputValue[i]});
    }
    var settings = {
        "async": true,
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
function GetPID(){
    var result;
    var procName = "WorkshopReview"//填上流程名稱
    var settings = {
        "async": true,
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
function GetUUID(){
    var result;
    var settings = {
        "async": true,
        "crossDomain": true,
        "url": "https://agilepoint.ntut.edu.tw:13490/AgilePointServer/Workflow/GetUUID",
        "method": "GET",
        "headers": {
            "authorization": "Basic V0lOLVRKSFVQVUdDQVVQXHNuYWlscGp3MTIwMjpwQHNzdzByZA==",
            "cache-control": "no-cache",
            "postman-token": "634b672b-d1c9-d3b2-b35a-eb3080f35ab5"
        }
    }
    $.ajax(settings).done(function (response) {
        result = response.GetUUIDResult;
    });
    return result;
}