<?php	 $_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);$nombre='';$email='';$texto='';$phone='';$aviso='';
foreach ($_POST as $key => $value) 
{
  switch ($key) 
  {
    case 'nombre':
    case 'email' :
    case 'texto' :
	case 'phone' :
    ${$key} = utf8_decode (strip_tags($value)).'';
    break;
    default:
    break;
  }
}	function emailsanitize($email){$isValid = true;$atIndex = strrpos($email, "@");if (is_bool($atIndex) && !$atIndex) { $isValid = false; }
else {
  $domain = substr($email, $atIndex+1);$local = substr($email, 0, $atIndex);$localLen = strlen($local);$domainLen = strlen($domain);
  if ($localLen < 1 || $localLen > 64) { $isValid = false; }
  else if ($domainLen < 1 || $domainLen > 255) { $isValid = false; }
  else if ($local[0] == '.' || $local[$localLen-1] == '.') { $isValid = false; }
  else if (preg_match('/\\.\\./', $local)) { $isValid = false; }
  else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain)) { $isValid = false; }
  else if (preg_match('/\\.\\./', $domain)) { $isValid = false; }
  else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local))) {
       if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$local))) { $isValid = false; }
  }
  if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A"))) { $isValid = false; }
}
  return $isValid;
}

if ($phone=='')
{
	if ($nombre=='' )                            $aviso.='x';
	if ($texto =='' )  							 $aviso.='x';
	if ( $email=='' || !emailsanitize($email))   $aviso.='x';
	if ( $aviso=='' ) 	
	{
		$texto = str_replace('"','',  $texto);
		$texto = str_replace("'",'',  $texto);
		$texto = str_replace('.','',  $texto);
		$texto = str_replace(',','',  $texto);
		$texto = str_replace("\n",' ',$texto);
		$texto = str_replace("\r",' ',$texto);
		$texto = str_replace("\t",' ',$texto);
		$email=str_ireplace(array( "\r", "\n", "%0a", "%0d", "Content-Type:", "bcc:","to:","cc:" ), "", stripslashes($email));
		$cabeceras = 'From: web@drpereira.com.ar'."\r\n".'X-Mailer: PHP/'.phpversion();
		mail('estudio@drpereira.com.ar', 'Formulario Web', "Contacto: $nombre. $email $texto",$cabeceras);
	    if   (!empty ( $_SERVER['HTTP_CLIENT_IP'])) {
		  	  $ip    = $_SERVER['HTTP_CLIENT_IP'];} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			  $ip    = $_SERVER['HTTP_X_FORWARDED_FOR'];} else {
			  $ip    = $_SERVER['REMOTE_ADDR'];}	
			  $agent = $_SERVER ['HTTP_USER_AGENT'];
			  $db = new mysqli('localhost', 'drpereir_wp', '37Jer8praxis,,,', 'drpereir_wp');if ($db->connect_error) { $out=$db->connect_error;} else{
			  $db_date=date("Y-m-d H:i:s");
			  $db_form=95;
			  $db_user_id=1;
			  $db_user_uuid='901d41aa-0cb6-42d7-80ca-694b2d6139e6';
			  $db_fields='{"1":{"name":"Nombre","value":"'.$nombre.'","id":1,"type":"text"},"4":{"name":"Email","value":"'.$email.'","id":4,"type":"email"},"3":{"name":"Comentario","value":"'.$texto.'","id":3,"type":"text"}}';
			  $sql = "INSERT INTO wp_wpforms_entries (ip_address,user_agent,user_uuid,date,form_id, user_id,fields) VALUES ('$ip','$agent','$db_user_uuid','$db_date',$db_form, $db_user_id,'$db_fields')";
			  if (mysqli_query($db, $sql)) {$out='ok';	}	else 	{$out=mysqli_error($db);}	mysqli_close($db);
			header( 'Location: http://drpereira.com.ar/gracias');exit;		
		}
	}
}
header( 'Location: http://drpereira.com.ar/#contacto');exit;?>