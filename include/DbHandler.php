<?php

/**
 * Classe per le operazione sul db
 */
class DbHandler {

    private $conn;

    function __construct() {
        require_once dirname(__FILE__) . '/DbConnect.php';
        //apro la connessione
        $db = new DbConnect();
        $this->conn = $db->connect();
    }

    /**
     * Controlla se l'utente è presente
     */
    public function checkLogin($username, $password) {
        $query = "SELECT password FROM Studenti WHERE nome = ?";

        $stm = $this->conn->prepare($query);
        $stm->bind_param("s", $username);

        $stm->execute();
        $stm->bind_result($pass);
        $stm->store_result();

        if ($stm->num_rows > 0) {
            $stm->fetch();
            $stm->close();

            if ($pass == $password) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            $stm->close();
            return FALSE;
        }
    }
    
    /*
     * Prende le info dell'universita
     */
    public function getInfoUni() {
        $query = "SELECT * FROM info_uni";

        $stm = $this->conn->prepare($query);

        if ($stm->execute()) {
            $info = $stm->get_result()->fetch_assoc();
            $stm->close();
            return $info;
        } else {
            return NULL;
        }
    }

    /*
     * Restituisce uno specifico utente se esiste
     */

    public function getSingleUser($username) {
        $query = "SELECT nome, telefono, mestiere FROM Studenti WHERE username = ?";

        $stm = $this->conn->prepare($query);
        $stm->bind_param("s", $username);

        if ($stm->execute()) {
            $user = $stm->get_result()->fetch_assoc();
            $stm->close();
            return $user;
        } else {
            return NULL;
        }
    }

    /*
     * Restituisce tutti gli studenti
     */

    public function getAllUser() {
        $query = "SELECT * FROM Studenti";

        $stm = $this->conn->prepare($query);
        $list = $stm->get_result()->fetch_assoc();
        $stm->close();
        return $list;
    }

}

?>
