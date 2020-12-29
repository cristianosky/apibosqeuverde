<?php
    require_once 'respuestas.class.php';
    require_once "conexion/conexion.php";
    header("Access-Control-Allow-Origin: *");
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");


    class planta extends conexion {

        private $table = "plantas";
        private $nombre = "";
        private $precio = "";
        private $descripcion = "";
        private $categoria = "";
        private $imagen = "";
        private $id = "";
        private $token = "";

        public function listPlantas($pagina = 1){
            $inicio  = 0 ;
            $cantidad = 100;
            if($pagina > 1){
                $inicio = ($cantidad * ($pagina - 1)) +1 ;
                $cantidad = $cantidad * $pagina;
            }
            $query = "SELECT * FROM " . $this->table . " ORDER BY id DESC limit $inicio,$cantidad";
            $datos = parent::obtenerDatos($query);
            return ($datos);
        }

        public function obtenerPlanta($id){
            $query = "SELECT * FROM " . $this->table . " WHERE id = '$id'";
            return parent::obtenerDatos($query);
        }

        public function post($json){
            $_respuestas = new respuestas;
            $datos = json_decode($json,true);               

            if(!isset($datos['nombre']) || !isset($datos['descripcion']) || !isset($datos['imagen'])){
                return $_respuestas->error_400();
            }else{
                $this->nombre = $datos['nombre'];
                $this->descripcion = $datos['descripcion'];
                $this->imagen = $datos['imagen'];
                if(isset($datos['precio'])) { $this->precio = $datos['precio']; }
                if(isset($datos['categoria'])) { $this->categoria = $datos['categoria']; }
                $resp = $this->insertar();
                if($resp){
                    $respuesta = $_respuestas->response;
                    $respuesta["result"] = array(
                        "plantaId" => $resp
                        );
                    return $respuesta;
                }else{
                    return $_respuestas->error_500();
                }
            }
        }

        private function insertar(){
            $query = "INSERT INTO " . $this->table . " (nombre,precio,descripcion,categoria,imagen)
            values
            ('" . $this->nombre . "','" . $this->precio . "','" . $this->descripcion ."','" . $this->categoria . "','"  . $this->imagen . "')"; 
            $resp = parent::nonQueryId($query);
            if($resp){
                 return $resp;
            }else{
                return 0;
            }
        }

        public function put($json){
            $_respuestas = new respuestas;
            $datos = json_decode($json,true);

            if(!isset($datos['id'])){
                return $_respuestas->error_400();
            }else{
                $this->id = $datos['id'];
                if(isset($datos['nombre'])) { $this->nombre = $datos['nombre']; }
                if(isset($datos['precio'])) { $this->precio = $datos['precio']; }
                if(isset($datos['descripcion'])) { $this->descripcion = $datos['descripcion']; }
                if(isset($datos['categoria'])) { $this->categoria = $datos['categoria']; }
                if(isset($datos['imagen'])) { $this->imagen = $datos['imagen']; }
    
                $resp = $this->update();
                if($resp){
                    $respuesta = $_respuestas->response;
                    $respuesta["result"] = array(
                        "id" => $this->id
                    );
                    return $respuesta;
                }else{
                    return $_respuestas->error_500();
                }
            }
        }

        private function update(){
            $query = "UPDATE " . $this->table . " SET nombre ='" . $this->nombre . "',precio = '" . $this->precio . "',descripcion = '" . $this->descripcion . "',categoria = '" .
            $this->categoria . "',imagen = '" . $this->imagen . "' WHERE id = '" . $this->id . "'"; 
            $resp = parent::nonQuery($query);
            if($resp){
                 return $resp;
            }else{
                return 0;
            }

        }

        public function eliminarPlanta($id){

            $query = "DELETE FROM " . $this->table . " WHERE id = '$id'";
            return parent::nonQuery($query);
        }

        private function buscarToken(){
            $query = "SELECT  TokenId,UsuarioId,Estado from usuarios_token WHERE Token = '" . $this->token . "' AND Estado = 'Activo'";
            $resp = parent::obtenerDatos($query);
            if($resp){
                return $resp;
            }else{
                return 0;
            }
        }

        private function actualizarToken($tokenid){
            $date = date("Y-m-d H:i");
            $query = "UPDATE usuarios_token SET Fecha = '$date' WHERE TokenId = '$tokenid' ";
            $resp = parent::nonQuery($query);
            if($resp >= 1){
                return $resp;
            }else{
                return 0;
            }
        }

        public function buscarPlanta($nombre){
            $query = "SELECT * FROM " . $this->table . " WHERE nombre LIKE '$nombre%'";
            return parent::obtenerDatos($query);
        }

        public function buscarPlantaCate($categoria){
            $query = "SELECT * FROM " . $this->table . " WHERE categoria = '$categoria'";
            return parent::obtenerDatos($query);
        }

    }
?>