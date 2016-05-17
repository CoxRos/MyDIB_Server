<?php

/*
 * Questa pagina la considero valida per studenti, professori e dirigenti. All'interno 
 * andrò a distinguere ciò di cui avrò bisogno
 */

include($_SERVER['DOCUMENT_ROOT'] . "\MyDIB_SERVER\config\my_include\setupDB.php");
include($_SERVER['DOCUMENT_ROOT'] . "\MyDIB_SERVER\config\my_include\JsonSetting.php");

if($request['accessKey'] == $configKey['accessKey']) {
	/*
	 * Qui dentro faccio quello di cui ho bisogno
	 */
} else {
	echo $messaggi_errore['ACCESSKEY_REFUSED'];
}


