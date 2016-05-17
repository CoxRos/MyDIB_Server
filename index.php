<?php

/*
 * Pagina da prendere solo come esempio ... verrà eliminata
 */
	include($_SERVER['DOCUMENT_ROOT'] . "\MyDIB_SERVER\config\my_include\setupDB.php");
	include($_SERVER['DOCUMENT_ROOT'] . "\MyDIB_SERVER\config\my_include\JsonSetting.php");
	
	if($request['action'] == 'nota') {
		
		$db = new db($cartella_ini,$messaggi_errore,true);
		if(!$db->getStato()) {
			echo $messaggi_errore['DB_ERROR'];
			exit;
		}
		$query = "select id from calezioni";
		$righe_estratte = $db->select($query);
		if($righe_estratte === false) {
			echo $messaggi_errore['DB_ERROR_QUERY'];
			exit;
		}
		
		if (count($righe_estratte) > 0) //Ha trovato qualcosa
		{
			echo json_encode($righe_estratte[0]);
			                              
		} else {
			echo "Nothing";
		}
		$db->db_close();
		
		
	} else {
		echo "Ciao";
	}
	
?>