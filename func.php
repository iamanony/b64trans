<?php
function access_url_fopen($url) {
    $d = "";
    $fo = fopen($url,'rb');
    if($fo){
        while(!feof($fo)) {
            $d .= fgets($fo);
        }
    }
    fclose($fo);
    //@$nn = implode("|",$http_response_header);
    //@$hrh = explode("|",$nn);
    unset($nn);
    return array($d,$http_response_header);
}
function access_url_curl($url) {
    $oCurl = curl_init();
    curl_setopt($oCurl,CURLOPT_URL,$url);
    curl_setopt($oCurl, CURLOPT_HEADER, true);
    curl_setopt($oCurl, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($oCurl, CURLOPT_POST, false);
    $sContent = curl_exec($oCurl);
    $headerSize = curl_getinfo($oCurl, CURLINFO_HEADER_SIZE);
    $header = substr($sContent, 0, $headerSize);
    curl_close($oCurl);
    $d = substr($sContent,$headerSize);
    $headArr = explode("\r\n", $header);
    return array($d,$headArr);
}
if (USING_CURL) {
    function access_url($url) {return access_url_curl($url);}
} else {
    function access_url($url) {return access_url_fopen($url);}
}
function gzgetcont($f) {
    $d = "";
    $fo = gzopen($f,'r');
    if($fo){
        while(!gzeof($fo)) {
            $d .= gzgets($fo);
        }
    }
    gzclose($fo);
    return $d;
}

function urlsafe_b64encode($string) {
    $data = base64_encode($string);
    $data = str_replace(array('+','/','='),array('-','_',''),$data);
    return $data;
}
function urlsafe_b64decode($string) {
    $data = str_replace(array('-','_'),array('+','/'),$string);
    $mod4 = strlen($data) % 4;
    if ($mod4) {
        $data .= substr('====', $mod4);
    }
    return base64_decode($data);
}
function getheader($headersArray,$header)
{
    $length = strlen($header);
    $headerValue = "";
    foreach ($headersArray as $loop) {
        if(stripos($loop,$header) !== false){
            $realPos = stripos($loop,':');
            $headerValue = rtrim(trim(substr($loop, $realPos+1)));
            return $headerValue;
        }
    }
    return $headerValue;
}
if (!(file_exists(CACHING_DICTIONARY)))
{
    mkdir(CACHING_DICTIONARY);
}
?>
