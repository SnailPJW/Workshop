function GetPID(){
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
        return response.GetReleasedPIDResult;
    });
}
function GetUUID(){
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
        return response.GetUUIDResult;
    });
}