<?php
class JSON_Util{
    public static $Status_Success = "success";
    public static $Status_Error = "error";
    public static function FormSimpleResponse($status, $data){
        return array('status'=>$status, 'data'=>$data);
    }
    public static function SendSimpleResponse($status, $data){
        header('Content-Type: application/json');
        echo json_encode(JSON_Util::FormSimpleResponse($status, $data));
    }
    public static function SendJSONEncodedResponse($response_array){
        header('Content-Type: application/json');
        echo json_encode($response_array);
    }
    public static function SendSimpleJSONEncodedResponse($response_array){
        header('Content-Type: application/json');
        $output = array('status'=>$response_array['status'], 'data'=>$response_array['data']);
        echo json_encode($response_array);
    }
    public static function CheckSimpleStatus($simple_array, $status){
        if($status==$simple_array['status']) return true;
        else false;
    }
    public static function FormErrorResponse($data){
        return JSON_Util::FormSimpleResponse(JSON_Util::$Status_Error, $data);
    }
    public static function FormSuccessResponse($data){
        return JSON_Util::FormSimpleResponse(JSON_Util::$Status_Success, $data);
    }
    public static function SendErrorResponse($data){
        JSON_Util::SendSimpleResponse(JSON_Util::$Status_Error, $data);
    }
    public static function SendSuccessResponse($data){
        JSON_Util::SendSimpleResponse(JSON_Util::$Status_Success, $data);
    }
    public static function IsSuccess($simple_array){
        if($simple_array['status'] == 'success'){
            return true;
        }else return false;
    }
}