<?php
//прокси для проброса к локалхосту
$url = "http://teste0.mooo.com/generator_test/amoauth.php";



$ch = curl_init($url . "?" . http_build_query($_GET));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, getallheaders());

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    curl_setopt($ch, CURLOPT_POST, 1);

    curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents('php://input'));
}


$html = curl_exec($ch);
curl_close($ch);
echo ($html);
