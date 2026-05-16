<?php
namespace app\models\config;

use mysqli;

class Conexion {
    private $host = "localhost";
    private $db_name = "registro_retro_db";
    private $username = "root";
    private $password = "";
    private $conx_db = null;
    private $lastError = '';

    public function __construct() {
        $this->conx_db = new mysqli($this->host, $this->username, $this->password, $this->db_name);
        if ($this->conx_db->connect_errno) {
            $this->lastError = $this->conx_db->connect_error;
        }
    }

    public function Execute($sql, array $params = null) {
        $this->lastError = '';

        if (!$this->conx_db) {
            $this->lastError = 'No hay conexión a la base de datos.';
            return false;
        }

        $stmt = $this->conx_db->prepare($sql);
        if (!$stmt) {
            $this->lastError = $this->conx_db->error;
            return false;
        }

        if ($params !== null && count($params) > 0) {
            $types = str_repeat('s', count($params));
            $bind_names = [];
            $bind_names[] = & $types;
            foreach ($params as $key => $value) {
                $bind_names[] = & $params[$key];
            }
            call_user_func_array([$stmt, 'bind_param'], $bind_names);
        }

        try {
            $execOk = $stmt->execute();
        } catch (\mysqli_sql_exception $e) {
            $this->lastError = $e->getMessage();
            $stmt->close();
            return false;
        } catch (\Throwable $e) {
            $this->lastError = $e->getMessage();
            $stmt->close();
            return false;
        }

        if (!$execOk) {
            $this->lastError = $stmt->error;
            $stmt->close();
            return false;
        }

        $result = $stmt->get_result();
        if ($result !== false) {
            $stmt->close();
            return $result;
        }

        $stmt->close();
        return true;
    }

    public function Close() {
        if ($this->conx_db) {
            $this->conx_db->close();
        }
    }

    public function getLastError() {
        return $this->lastError;
    }
}