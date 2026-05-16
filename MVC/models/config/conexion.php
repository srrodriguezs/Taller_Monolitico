<?php
namespace app\models\config;

use mysqli;

class Conexion {
    private $host = "localhost";
    private $db_name = "registro_retro_db";
    private $username = "root";
    private $password = "";
    public $conx_db = null;

    public function __construct() {
        $this->conx_db = new mysqli($this->host, $this->username, $this->password, $this->db_name);
    }

    public function Execute($sql, $params = null) {
        $stmt = $this->conx_db->prepare($sql);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function Close() {
        $this->conx_db->close();
    }
}