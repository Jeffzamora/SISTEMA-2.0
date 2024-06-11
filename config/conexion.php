<?php
    /* Inicio de Sesión en la WebApp */
    session_start();

    class Conectar {
        protected $dbh;

        protected function Conexion() {
            try {
                // Cadena de conexión a la base de datos
                $host = "localhost";
                $dbname = "db_demo";
                $user = "jzamora";
                $password = "Soporte1.";
                $dsn = "mysql:host=$host;dbname=$dbname";

                // Conexión a la base de datos
                $conectar = $this->dbh = new PDO($dsn, $user, $password);
                return $conectar;
            } catch (PDOException $e) {
                throw new Exception("¡Error de conexión a la base de datos!: " . $e->getMessage());
            }
        }

        public function set_names() {
            return $this->dbh->query("SET NAMES 'utf8'");
        }

        public static function ruta() {
            // Ruta del proyecto
            return "http://18.191.189.190/";
        }
    }

