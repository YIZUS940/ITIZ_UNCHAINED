<?php
class CConexion{	
	function ConecionBD()
	{
		$host="localhost";
		$bd="SIGS";
		$user="postgres";
		$pass="8833";
		try{
			$conn = new PDO ("pgsql:host=$host; dbname=$bd", $user, $pass);
			echo "Se conecto correctamente a la base de datos";
			
		}
		catch(PDOException $exp){
			echo  ("No se conecto correctamente a la base de datos, $exp");
		}
		return $conn; 
	}

}
?>