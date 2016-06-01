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

$app->post('/info_uni', function() use ($app) {
    //echo(json_encode(array("result" => 0)));;
    /*
      echo "{
      'doveSiamo': 'Campus Universitario \'Ernesto Quagliariello\' \n\'Via E. Orabona, 4 - Bari 70125\'',
      'pec': 'HOLAAAAAAAA',
      'nomeDir': 'Prof. Donato Malerba',
      'emailDir': 'direttore.dib@uniba.it',
      'nomeSegr': 'dott. Rosaria Lacalamita',
      'emailSegr': 'sad.dib@uniba.it'
      }"; */

    // controllo i parametri
    verifyRequiredParams(array('username', 'password'));

    // prendo i paraemtri della post
    $username = $app->request()->post('username');
    $password = $app->request()->post('password');
    $response = array();

    if ($username == "pippo") {
        $db = new DbHandler();
        $info = $db->getInfoUni();
        if ($info != null) {
            /*
            $response['doveSiamo'] = 'Campus Universitario \'Ernesto Quagliariello\' \n\'Via E. Orabona, 4 - Bari 70125\'';
            $response['pec'] = 'direzione.pic@uniba.it';
            $response['nomeDir'] = 'Prof. Donato Malerba';
            $response['emailDir'] = 'direttore.dib@uniba.it';
            $response['nomeSegr'] = 'dott. Rosaria Lacalamita';
            $response['emailSegr'] = 'sad.dib@uniba.it';
            */
            $response['doveSiamo'] = $info['doveSiamo'];
            $response['pec'] = $info['pec'];
            $response['nomeDir'] = $info['nomeDir'];
            $response['emailDir'] = $info['emailDir'];
            $response['nomeSegr'] = $info['nomeSegr'];
            $response['emailSegr'] = $info['emailSegr'];
            echoResponse(200, $response);
                
        }
    } else {
        $response['error'] = 'Username sbagliato';
        echoResponse(400, $response);
    }
    /*
      $db = new DbHandler();
      // controllo se sono corrette email e password
      if ($db->checkLogin($username, $password)) {

      $response['error'] = false;
      $response['message'] = "Login effettuato con successo";
      } else {

      $response['error'] = true;
      $response['message'] = 'Login incorretto, controlla le credenziali';
      }
     */
});

$app->run();
?>
