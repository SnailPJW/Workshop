<?php
class OT_Emailer{
    public static $OT_Email = 'onlineteachwise@gmail.com';
    //public static $OT_HU_Name = '線上教學系統';
    public static $OT_HU_Name = '沃課Shop平台';
    public static function SendMail($target_email, $subject, $content){
        $CI =& get_instance();
        $CI->load->library('email');
        $CI->email->set_newline("\r\n");
        // Set to, from, message, etc.
        //email account info:
        /*
         * wisecamera.tms@gmail.com
         * pw:openfoundry
         * 
         */
        $CI->email->from(
            self::$OT_Email,
            self::$OT_HU_Name
        );
        $CI->email->to($target_email);
        $CI->email->subject($subject);
        $CI->email->message($content);
        $result = $CI->email->send();
        return $result;
    }
    
}