<?php
use \AmoCRM\Client;

function JSON_Response($array){
    $jsnString=json_encode($array);
    header('Content-type: application/json');
    die($jsnString);
}



require_once("config.php");
require_once("ext/Mailer.php");
require_once("vendor/autoload.php");


$mail=$_POST['email']??'';
$phone=$_POST['phone']??'';

$mail=strip_tags(trim($mail));
$phone=strip_tags(trim($phone));


//валидируем
if($mail=='' || $phone==''){header("HTTP/1.1 403 Forbidden" );die('Error:403 Forbidden');}
if(preg_match("/[^@\s]+@[^@\s]+\.[^@\s]+/",$mail)===false){JSON_Response(['error'=>'Неверный формат Email']);}
if(preg_match("/\+7\([0-9]{3}\)[0-9]{3}-[0-9]{2}-[0-9]{2}/",$phone)===false){JSON_Response(['error'=>'Неверный формат Телефона']);}

$maket=file_get_contents("maket/mail.html");
$maket=str_replace("%[mail]",$mail,$maket);
$maket=str_replace("%[phone]",$phone,$maket);

$mailer = new App\Mailer();
$mailer->smtp_fromstring="IntroZorn(C) Sender";

$rt=$mailer->send(MAILTO,'Заявка Хроленко',$maket,"");





require_once("amo.php");

$amo=new AMO;

//$amo->AddLead();


JSON_Response(['success'=>'Заявка на получение файлов отправлена, дождитесь ответа']);
