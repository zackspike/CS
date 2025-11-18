<?php

class ConexionBD {
    private $hostname = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "filey_cs";
    
    public function conectar() {
        $mysqli = new mysqli($this->hostname, $this->username, $this->password, $this->database);
        
        if ($mysqli->connect_errno) {
            die("No se pudo conectar a la base de datos");
        }
        $mysqli->set_charset("utf8");

        return $mysqli;
    }
}

