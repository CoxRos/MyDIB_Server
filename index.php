<?php
	header('Content-Type: application/json');
	//include($_SERVER['DOCUMENT_ROOT'] . "\MyDIB_SERVER\config\my_include\setup.php");
	include($_SERVER['DOCUMENT_ROOT'] . "\MyDIB_SERVER\config\my_include\setupDB.php");
	
	$json = file_get_contents('php://input');
	$request = json_decode($json, true);
	$response;
	
	if($request['accessKey'] == $configKey['accessKey']) {
		$db = new db($cartella_ini,$messaggi_errore,true);
		if(!$db->getStato()) {
			echo $messaggi_errore['DB_ERROR'];
			exit;
		}
		$query = "select id from calezioni'";
		$righe_estratte = $db->select($query);
		if($righe_estratte === false) {
			echo $messaggi_errore['DB_ERROR_QUERY'];
			exit;
		}
		
		if (count($righe_estratte) > 0) //Ha trovato qualcosa
		{
			echo $righe_estratte[0];
			                              
		} else {
			echo "Nothing";
		}
		$db->db_close();
		
		
	}
	
?>