<?php
	include($_SERVER['DOCUMENT_ROOT'] . "\MyDIB_SERVER\config\my_include\setup.php");
?>

<?php 

class db {
	private static $conn;
	
	private 
		$nl,
		$cartella_ini,
		$messaggi_errore,
		$accessData,
		$stato,
		$descrizione_stato,
		$stampa_errori;
		
	public function getStato() {return $this->stato;}
	public function get_descrizione_stato() {return $this->descrizione_stato;}
	
	public function __construct($cartella_ini,$messaggi_errore,$stampa_errori=true) {
		$this->accessData = parse_ini_file($cartella_ini . "\configDB.ini");
		
		$this->messaggi_errore = $messaggi_errore;
		
		//devono essere stampati gli errori?
			$this->stampa_errori = $stampa_errori;
			
			$this->connessione(); //la connessione modifica la variabile $stato
			
			if($this->stato) {
				$this->scelta_database();
			}
	}
	
	private function connessione() {
			if(!isset($this->conn)) { //eccesso di prudenza perch� si prevede la possibilit� in futuro di avviare la connessione altrove
			
				$this->conn = @mysqli_connect(
									$this->accessData['host'],
									$this->accessData['username'],
									$this->accessData['password']);
				
				if(!$this->conn) {
					$this->stato = false;
					$this->descrizione_stato= $this->messaggi_errore['connessione_fallita'];
					
					if($this->stampa_errori) {
						echo $this->messaggi_errore['connessione_fallita'] . $this->nl;
					}
				} else {
					$this->stato = true;
				}
			}
		}
		
		private function scelta_database() {
			
			if(!@mysqli_select_db($this->conn,$this->accessData['dbname'])) {
				
				$this->$stato = false;
				$this->descrizione_stato= $this->messaggi_errore['db_non_trovato'];
				
				if($this->stampa_errori) {
						echo $this->messaggi_errore['db_non_trovato'] . $this->nl;
					}
			} else {
				$this->stato = true;
			}
		}

		public function db_sanifica_parametro($parametro) {
			return mysqli_escape_string($this->conn,$parametro);
		}
		
		public function select($query){
			$risultato_query = mysqli_query($this->conn,$query);
			
			if($risultato_query ===false) {
				$this->stato = false;
		
				$this->descrizione_stato = $this->messaggi_errore['problema_con_server'];
				
				if($this->stampa_errori) {
					echo $this->messaggi_errore['problema_con_server'] . $this->nl;
				}
				return false;
			} else {
				$this->stato = true;
				$righe_estratte = array();
				while($riga = mysqli_fetch_assoc($risultato_query)) {
					$righe_estratte[] = $riga;
				}
				/* PER IL DEBUG NELL'ARRAY
				foreach($righe_estratte as $riga) {
				        echo $riga['titolo'];
				}
				*/
				return $righe_estratte;
			}
		}
		
		function db_close() {
			mysqli_close($this->conn);
		}

		function db_insert($comandoSQL) {
		$esito = mysqli_query($this->conn,$comandoSQL);
		
		if($esito) {
			return mysqli_insert_id($this->conn);
		} else {
			$this->stato = false;
			$this->descrizione_stato = $this->messaggi_errore['problema_con_server'];
			
			if($this->$stampa_errori) {
				echo $this->messaggi_errore['problema_con_server'];
			}
			return false;
		}
		}
			
		public function getLastIndex() {
			return mysqli_insert_id($this->conn);
		}
		
		





}