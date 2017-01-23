<?php
if(!isset($_SESSION))session_start();


function hugeit_contact_get_field_row($id){
    global $wpdb;
    $query="SELECT * FROM  " . $wpdb->prefix . "huge_it_contact_contacts_fields WHERE id={$id}";
    $captcha_field=$wpdb->get_results($query,'ARRAY_A');

    return $captcha_field[0];
}



function hugeit_contact_create_new_captcha($captcha_id='',$from=''){
    $is_ajax_request=false;
    if(isset($_POST['captchaid'])){
        $captcha_id=$_POST['captchaid']; $from='user';
        $is_ajax_request=true;
    }

    $field=hugeit_contact_get_field_row($captcha_id);

    $captchaRow=json_decode($field['hc_other_field']);

    $digitsLength=$captchaRow->digits;
    $color=$captchaRow->color;

    $colorOption=$field['description'];

    $captcha='';

    if($digitsLength=='null'){
        $digitsLength=5;
    }

    for($i=1;$i<=$digitsLength;$i++){
        $captcha.=chr(rand(97,122));
    }


    if($digitsLength<=5){$font_size=30;}
    else{$font_size=25;}


    $_SESSION['hugeit_contact_captcha-'.$from.'-'.$captcha_id]=$captcha;



    $font=plugin_dir_path(__FILE__).'../elements/fonts/Super_Webcomic_Bros.ttf';
    $image=imagecreatetruecolor(170,60);

    $black=imagecolorallocate($image,0,0,0);
    $white=imagecolorallocate($image,255,255,255);

    if($colorOption=='default'){
        $color=imagecolorallocate($image,rand(0,200),rand(0,200),rand(0,200));
    }
    else{
        $rgbArray=hugeit_hex_to_rgb($color);
        $color=imagecolorallocate($image,$rgbArray['red'],$rgbArray['green'],$rgbArray['blue']);
    }


    imagefilledrectangle($image,0,0,200,100,$color);
    //var_dump($captcha);
    imagettftext($image,$font_size,5,30,45,$white,$font,$captcha);
    //var_dump($font);

    $time=time();
    $filename='captcha-'.$from.'-'.$time.'.png';

    imagepng($image,plugin_dir_path(__FILE__)."../images/tmp/".$filename);

    if($is_ajax_request){
        wp_send_json(plugin_dir_url(__FILE__)."../images/tmp/".$filename);
    }

    return plugin_dir_url(__FILE__)."../images/tmp/".$filename;


}


function hugeit_hex_to_rgb($hex) {
    $hex = str_replace("#", "", $hex);

    if(strlen($hex) == 3) {
        $r = hexdec(substr($hex,0,1).substr($hex,0,1));
        $g = hexdec(substr($hex,1,1).substr($hex,1,1));
        $b = hexdec(substr($hex,2,1).substr($hex,2,1));
    } else {
        $r = hexdec(substr($hex,0,2));
        $g = hexdec(substr($hex,2,2));
        $b = hexdec(substr($hex,4,2));
    }
    $rgb = array(
        'red'=>$r,
        'green'=>$g,
        'blue'=>$b
    );
    //return implode(",", $rgb); // returns the rgb values separated by commas
    return $rgb; // returns an array with the rgb values
}

?>
