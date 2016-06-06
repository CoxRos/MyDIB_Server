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
            $info = $stm->get_result();
            $stm->close();
            return $info;
        } else {
            return NULL;
        }
    }
    
    /*
     * Ritorna il dirigente
     */
    public function getDirigente($id) {
        $query = "SELECT * FROM dirigenti WHERE id = ?";
        
        $stm = $this->conn->prepare($query);
        $stm->bind_param("s",$id);
        
        if($stm->execute()) {
            $dirigente = $stm->get_result()->fetch_assoc();
            $stm->close();
            return $dirigente;
        } else {
            return NULL;
        }
    }
	
	/*
	 * Ritorna lo studente
	 */
	public function getStudente($id) {
		$query = "SELECT * FROM studenti WHERE id = ?";
        
        $stm = $this->conn->prepare($query);
        $stm->bind_param("s",$id);
        
        if($stm->execute()) {
            $studente = $stm->get_result()->fetch_assoc();
            $stm->close();
            return $studente;
        } else {
            return NULL;
        }
	}
	
	public function getAllSearched($type,$testo) {
		$query = "";
		$flag = false;
		$result = array();
		
		if($type == "studente") {
			$query = "SELECT * FROM studenti where nome = " . $testo . " OR cognome = " . $testo;
		} elseif($type == "dirigente") {
			$query = "SELECT * FROM dirigenti where nome = " . $testo . " OR cognome = " . $testo;
		} else {
			$flag = true;
			$query1 = "SELECT * FROM studenti";/* where nome = " . $testo . " OR cognome = " . $testo;*/
			$query2 = "SELECT * FROM dirigenti";/* where nome = " . $testo . " OR cognome = " . $testo;*/
			
			$stmt = $this->conn->prepare($query1);
			$stmt->execute();
			$tasks = $stmt->get_result();
			
			while ($utente = $tasks->fetch_assoc()) { //Per studenti
				$tmp = array();
				$tmp['id'] = $utente['matricola'];
				$tmp['nome'] = $utente['nome'];
				$tmp['tipo'] = 'studente';
				$tmp['cognome'] = $utente['cognome'];
				$tmp['email'] = $utente['email'];
				array_push($result,$tmp);
			}
			
			$stmt = $this->conn->prepare($query2);
			$stmt->execute();
			$tasks = $stmt->get_result();
			
			while ($utente = $tasks->fetch_assoc()) { //Per dirigenti
				$tmp = array();
				
				  $tmp['id'] = $utente['idDirigenti'];
				  $tmp['nome'] = $utente['nome'];
				  $tmp['email'] = $utente['email'];
				  $tmp['cognome'] = $utente['cognome'];
				  $tmp['tipo'] = 'Dirigente';
					
				if($utente['Prof'] == 'Y') {
					$tmp['tipo'] = 'Professore';
				}
				array_push($result,$tmp);
			}
			
			$stmt->close();
			return $result;
		}
		
		if(!flag) {
			$stmt = $this->conn->prepare($query);
			$stmt->bind_param("i", $user_id);
			$stmt->execute();
			$tasks = $stmt->get_result();
			while ($utente = $tasks->fetch_assoc()) {
				$tmp = array();
				
				  $tmp['id'] = $utente['idDirigenti'];
				  $tmp['nome'] = $utente['nome'];
				  $tmp['email'] = $utente['email'];
				  $tmp['cognome'] = $utente['cognome'];
				  $tmp['tipo'] = $type;
				
				array_push($result,$tmp);
			}
			
			$stmt->close();
			return $result;
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
