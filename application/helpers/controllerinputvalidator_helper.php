<?php
class ControllerInputValidator{
    /*
     * returns:
     * array(
     *  'status':success or error
     *  'data':whatever
     * )
     */
    public static function CheckUserLoggedIn(&$account){
       $CI =& get_instance();
       $account = $CI->session->userdata('ACCOUNT');
       if($account){
           return JSON_Util::FormSimpleResponse(JSON_Util::$Status_Success, '');
       }else{
           return JSON_Util::FormSimpleResponse(JSON_Util::$Status_Error, '你尚未登入!');
       }
    }
    //will check if user is logged in, if not form error msg
    //then it will check if user is authorized, if not form error
    //returns a simple message
    //output argument $account
    //$level = admin/assistant/normal
    public static function CheckUserAuthLevel($level, &$account){
        $CI =& get_instance();
        $result = ControllerInputValidator::CheckUserLoggedIn($account);
        if($result['status']==JSON_Util::$Status_Error){
            return $result;
        }else{
            $CI->load->model('UserModel', "userModel");
            if($CI->userModel->checkUserAuthorizationLevel($account, $level)){
                return JSON_Util::FormSuccessResponse('');
            }else{
                return JSON_Util::FormSimpleResponse(JSON_Util::$Status_Error, '你沒有足夠的權限!');
            }
        }
    }
    //go through all the input and check if they are set
    //if any of the elements in input var array are null/empty, then the name in var_name_array is picked up to form the error message
    public static function CheckInputExists($input_var_array, $var_name_array){
        
    }
    public static function RunningFromCLI(){
        return (php_sapi_name() === 'cli');
    }
    //following functions return true/false depending on if the input is valid
    //correct yearmonth = '2015-1' '2015-01'
    public static function verifyYearMonth($yearmonth, &$year, &$month){
        if(strpos($yearmonth, '-')!==false){
            list($year, $month) = explode('-', $yearmonth);
            $year = intval($year);
            $month = intval($month);
            if($year<=0 || $month>13 || $month<1) return false;
            else return true;
        }else return false;
    }
    public static function validateDate($date)
    {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') == $date;
    }
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

