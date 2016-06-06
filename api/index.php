<?php

require_once '../include/DbHandler.php';
require_once '../include/PassHash.php';
require '../libs/Slim/Slim.php';


\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

/**
 * Verifica che i parametri richiesti sono settati
 */
function verifyRequiredParams($required_fields) {
    $error = false;
    $error_fields = "";
    $request_params = array();
    $request_params = $_REQUEST;

    // per un PUT
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $app = \Slim\Slim::getInstance();
        parse_str($app->request()->getBody(), $request_params);
    }
    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }

    if ($error) {
        //i parametri sono mancanti o vuori
        // stampa l'errore e blocca l'applicazione
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["error"] = true;
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        echoResponse(400, $response);
        $app->stop();
    }
}

/**
 * controlla se la mail è in formato giusto
 */
function validateEmail($email) {
    $app = \Slim\Slim::getInstance();
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["error"] = true;
        $response["message"] = 'Email address is not valid';
        echoResponse(400, $response);
        $app->stop();
    }
}

/**
 * stampa il json di risposta
 * @param String lo stato della risposta
 * @param Int risposta json
 */
function echoResponse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);

    // setting response content type to json
    $app->contentType('application/json');

    echo json_encode($response);
}

/**
 * User Login
 * url - /login
 * method - POST
 * params - email, password
 */
$app->post('/login', function() use ($app) {
    echo(json_encode(array("result" => 0)));



    // controllo i parametri
    /* verifyRequiredParams(array('username', 'password'));

      // prendo i paraemtri della post
      $username = $app->request()->post('email');
      $password = $app->request()->post('password');
      $response = array();

      $db = new DbHandler();
      // controllo se sono corrette email e password
      if ($db->checkLogin($username, $password)) {

      $response['error'] = false;
      $response['message'] = "Login effettuato con successo";
      } else {

      $response['error'] = true;
      $response['message'] = 'Login incorretto, controlla le credenziali';
      }
      echoResponse(200, $response); */
});

$app->get('/info_uni', function() {
    
    $response = array();
    $db = new DbHandler();
    
    /*
      echo "{
      'doveSiamo': 'Campus Universitario \'Ernesto Quagliariello\' \n\'Via E. Orabona, 4 - Bari 70125\'',
      'pec': 'HOLAAAAAAAA',
      'nomeDir': 'Prof. Donato Malerba',
      'emailDir': 'direttore.dib@uniba.it',
      'nomeSegr': 'dott. Rosaria Lacalamita',
      'emailSegr': 'sad.dib@uniba.it'
      }"; */

        $info = $db->getInfoUni();
        $flagEntrato = false;
        while($task = $info->fetch_assoc()) {
            $flagEntrato = true;
            $response['doveSiamo'] = $task['doveSiamo'];
            $response['pec'] = $task['pec'];
            $response['nomeDir'] = $task['nomeDir'];
            $response['emailDir'] = $task['emailDir'];
            $response['nomeSegr'] = $task['nomeSegr'];
            $response['emailSegr'] = $task['emailSegr'];
        }
        if($flagEntrato) {
            echoResponse(200, $response);
        } else {
            $response['err'] = 'NO_ITEMS';
            echoResponse(200, $response);
        }
});

$app->get('/get_dirigente/:id', function($id_dir) { //DA VERIFICARE SE FUNZIONA
    
    $response = array();
    $db = new DbHandler();

        $dirigente = $db->getDirigente($id_dir);
        if($dirigente != null) {
          if($dirigente['Prof'] == 'Y') {
              $response['WebDirigente'] = $dirigente['WebDirigente'];
              $response['RicevimentoDirigente'] = $dirigente['RicevimentoDirigente'];
          }
          $response['CognomeDirigente'] = $dirigente['CognomeDirigente'];
          $response['NomeDirigente'] = $dirigente['NomeDirigente'];
          $response['EmailDirigente'] = $dirigente['EmailDirigente'];
          $response['TelefonoDirigente'] = $dirigente['TelefonoDirigente'];
      } else {
          $response['message'] = "Il dirigente non e' presente nel database.";
      }
      echoResponse(200, $response);
});

$app->get('/get_studente/:id', function($id_stud) { //DA VERIFICARE SE FUNZIONA
    
    $response = array();
    $db = new DbHandler();

        $studente = $db->getStudente($id_stud);
        if($studente != null) {
          $response['CognomeStudente'] = $studente['CognomeStudente'];
          $response['NomeStudente'] = $studente['NomeStudente'];
          $response['EmailStudente'] = $studente['EmailStudente'];
      } else {
          $response['message'] = "Lo studente non e' presente nel database.";
      }
      echoResponse(200, $response);
});

$app->get('/searchUtente/:type/:testo', function($type,$testo) { //DA VERIFICARE SE FUNZIONA
    
    $response = array();
    $db = new DbHandler();

        $result = $db->getAllSearched($type,$testo);
        if(count($result) > 0) {
			echoResponse(200, $result);
		} else {
			$message = "Nessun elemento trovato";
			echoResponse(200,$message);
		}
      
});


/*
$app->post('/dirigente', function() use ($app) { //Devo avvalorare il DB e aggiungere al DB il campo Prof

    // controllo i parametri
    verifyRequiredParams(array('idDirigente'));

      // prendo i paraemtri della post
      $idDirigente = $app->request()->post('idDirigente');
      $response = array();

      $db = new DbHandler();
      // controllo se sono corrette email e password
      $dirigente = $db->getDirigente($idDirigente);
      if($dirigente != null) {
          if($dirigente['Prof'] == 'Y') {
              $response['WebDirigente'] = $dirigente['WebDirigente'];
              $response['RicevimentoDirigente'] = $dirigente['RicevimentoDirigente'];
          }
          $response['CognomeDirigente'] = $dirigente['CognomeDirigente'];
          $response['NomeDirigente'] = $dirigente['NomeDirigente'];
          $response['EmailDirigente'] = $dirigente['EmailDirigente'];
          $response['TelefonoDirigente'] = $dirigente['TelefonoDirigente'];
      } else {
          $response['message'] = "Il dirigente non e' presente nel database.";
      }
      echoResponse(200, $response);
});
*/

$app->run();
/*
Ho bisogno di un servizio che ricerchi o un dirigente oppure uno studente quindi farò il metodo ricerca e in base al parametro passato
chiamo il metodo nell'handler di ricercaStudente oppure ricercaDirigente oppure RicercaTutto.


*/
?>


