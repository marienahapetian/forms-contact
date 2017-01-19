<?php
if(!isset($_SESSION))session_start();


if(isset($_GET['id'])){
    $captcha_id=$_GET['id'];
    if(isset($_GET['l']))$digitsLength=$_GET['l'];
    hugeit_contact_create_new_captcha($captcha_id,$digitsLength);
}



function hugeit_contact_create_new_captcha($captcha_id,$digitsLength=5){
    $captcha='';
    for($i=1;$i<=$digitsLength;$i++){
        $captcha.=chr(rand(97,122));
    }
    if($digitsLength<=5){
        $font_size=30;
    }
    else{
        $font_size=25;
    }

    $_SESSION['hugeit_contact_captcha-'.$captcha_id]=$captcha;


    $font='../elements/fonts/Super_Webcomic_Bros.ttf';
    $image=imagecreatetruecolor(170,60);
    $black=imagecolorallocate($image,0,0,0);
    $white=imagecolorallocate($image,255,255,255);
    $color=imagecolorallocate($image,rand(0,200),rand(0,200),rand(0,200));

    imagefilledrectangle($image,0,0,200,100,$color);
    imagettftext($image,$font_size,5,30,45,$white,$font,$_SESSION['hugeit_contact_captcha-'.$captcha_id]);
    header('Content-type: image/png');
    imagepng($image);
}

?>
