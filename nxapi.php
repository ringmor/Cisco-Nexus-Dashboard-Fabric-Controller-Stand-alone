<?php
if($_SERVER['REQUEST_METHOD']==='POST'){
header("Content-Type: application/json");
$nexus_ip="10.10.100.80";
$user="admin";
$pass="admin";
$cmd=isset($_POST['cmd']) ? $_POST['cmd'] : 'show interface brief';
$type=isset($_POST['type']) ? $_POST['type'] : 'cli_show';
$body=json_encode(["ins_api"=>["version"=>"1.0","type"=>$type,"chunk"=>"0","sid"=>"1","input"=>$cmd,"output_format"=>"json"]]);
// Debug logging
file_put_contents('nxapi_debug.log', "CMD: $cmd\nTYPE: $type\nBODY: $body\n", FILE_APPEND);
$ch=curl_init();
curl_setopt($ch,CURLOPT_URL,"https://$nexus_ip/ins");
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_USERPWD,"$user:$pass");
curl_setopt($ch,CURLOPT_POST,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,$body);
curl_setopt($ch,CURLOPT_HTTPHEADER,["Content-Type: application/json"]);
curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
$res=curl_exec($ch);
// Log response
file_put_contents('nxapi_debug.log', "RESPONSE: $res\n\n", FILE_APPEND);
if(!$res){
echo json_encode(["error"=>curl_error($ch)]);
} else {
echo $res;
}
curl_close($ch);
exit;
}

