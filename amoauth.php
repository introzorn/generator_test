<?php



require_once("config.php");
require_once("vendor/autoload.php");

$c=$_GET["code"]??'';

if($c!=''){

    file_put_contents("amo/code.json",json_encode($_GET));
    $user=json_decode(file_get_contents('amo/user.json'),true);
  

    $apiClient = new \AmoCRM\Client\AmoCRMApiClient($user['client_id'], $user['client_secret'],AMO_CLIENT_REDIRECT);

    $apiClient->setAccountBaseDomain($_GET['referer']);


    try {
          $accessToken = $apiClient->getOAuthClient()->getAccessTokenByCode($_GET['code']);
    
        if (!$accessToken->hasExpired()) {
            file_put_contents('amo/token.json',json_encode([
                'accessToken' => $accessToken->getToken(),
                'refreshToken' => $accessToken->getRefreshToken(),
                'expires' => $accessToken->getExpires(),
                'baseDomain' => $apiClient->getAccountBaseDomain(),
            ]));
        }
    } catch (Exception $e) {
        die((string)$e);
    }
    
    $ownerDetails = $apiClient->getOAuthClient()->getResourceOwner($accessToken);
    
    printf('Hello, %s!', $ownerDetails->getName());

    die();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = file_get_contents('php://input');
    file_put_contents("amo/user.json",$data);
    die();
}



echo('<h1>авторизация amocrm</h1>');

echo('<script
class="amocrm_oauth"
charset="utf-8"
data-name="IntroZorn(c)"
data-description="IntroZorn(c) Int"
data-redirect_uri="'.AMO_CLIENT_REDIRECT.'"
data-secrets_uri="'.AMO_CLIENT_REDIRECT.'"
data-logo="https://example.com/amocrm_logo.png"
data-scopes="crm"
data-title="Button"
data-compact="false"
data-class-name="className"
data-color="default"
data-state="'.bin2hex(random_bytes(16)).'"
data-error-callback="functionName"
data-mode="popup"
src="https://www.amocrm.ru/auth/button.min.js"
></script>');







