<?php

function request($url, $data = null, $headers = null)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    if($data):
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    endif;
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if($headers):
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_HEADER, 1);
    endif;

    curl_setopt($ch, CURLOPT_ENCODING, "GZIP");
    return curl_exec($ch);
}
function getstr($str, $exp1, $exp2)
{
    $a = explode($exp1, $str)[1];
    return explode($exp2, $a)[0];
}
function randomNumber() {
    $min = pow(10, 14); 
    $max = pow(10, 15) - 1; 
    return mt_rand($min, $max); 
}


echo "Email : ";
$email = trim(fgets(STDIN));
echo "Password : ";
$password = trim(fgets(STDIN));

login:
$url = "https://www.googleapis.com/identitytoolkit/v3/relyingparty/verifyPassword?key=AIzaSyDWijA0JRIB-v226FtahwduJIcChrNyDwg";
$headers[] = "Content-Type: application/json";
$headers[] = "X-Android-Package: tv.mola.apq";
$headers[] = "X-Android-Cert: C407DA0E93C30EB722C05EB6F8797F988247A12A";
$headers[] = "Accept-Language: en-US";
$headers[] = "X-Client-Version: Android/Fallback/X21000002/FirebaseCore-Android";
$headers[] = "X-Firebase-GMPID: 1:125729913452:android:6e76330b3952938de7f649";
$headers[] = "User-Agent: Dalvik/2.1.0 (Linux; U; Android 7.1.2; Redmi Note 5A MIUI/V11.0.2.0.NDKMIXM)";
$headers[] = "Connection: Keep-Alive";
$headers[] = "Accept-Encoding: gzip";
$data = '{"email":"'.$email.'","password":"'.$password.'","returnSecureToken":true}';
$login = request($url, $data, $headers);
if(strpos($login, 'idToken')!==false)
{
    $idToken = getstr($login, '"idToken": "','"');
}
else if(strpos($login, 'message": "')!==false)
{
    $errorMessage = getstr($login, 'message": "','"');
    echo "$errorMessage\n";
    exit();
}
else
{
    goto login;
}

sub:
echo "Activating Subscription : ";
$deviceId = randomNumber();

$url = "https://api2-mola.onwards.pro/v1/subscriber/login";
$headers[] = "user-agent: Mola/2.2.5.18 (Android 7.1.2; Polytron 2K PA Smart TV)";
$headers[] = "x-mola-version: Mola/2.2.5.18 (Android 7.1.2; Polytron 2K PA Smart TV)";
$headers[] = "content-type: application/json; charset=UTF-8";
$headers[] = "accept-encoding: gzip";
$data = '{"advertisingId":"efdbe35f-f4f8-4f57-b27e-ba39b7802fb5","appsflyerId":"1683431267418-5848736556801870020","deviceId":"'.$deviceId.'","deviceName":"Polytron","deviceType":"Android","idToken":"'.$idToken.'","modelNo":"2K PA Smart TV","serialNo":"'.$deviceId.'"}';
$subscribe = request($url, $data, $headers);
if(strpos($subscribe, 'status":"active"')!==false)
{
    echo "Active\n";
}
else
{
    goto sub;
}