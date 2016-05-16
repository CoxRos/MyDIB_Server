<?php
 $cartella_ini =  $_SERVER['DOCUMENT_ROOT'] . "\MyDIB_Server\config";
 
 $messaggi_errore= parse_ini_file($cartella_ini."\messaggi_errore.ini");
 
 $configKey = parse_ini_file($cartella_ini."\ConfigKey.ini");
 
