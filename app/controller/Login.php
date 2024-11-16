<?php
    
    require_once '../config/conexion.php';
    session_start();

    class Login extends Conexion{
        private function crear_sesion($datos){
            $_SESSION['usuario'] = $datos;            
        }
        public function cerrar_sesion(){
            session_unset();
            session_destroy();
            echo json_encode([1,"Sesion finalizada!"]);
        }
        public function iniciar_sesion(){
            $usuario = $_POST['usuario'];
            $password = $_POST['password'];
            $consulta = $this->obtener_conexion()->prepare("SELECT * FROM t_usuario WHERE usuario = :usuario");
            $consulta->bindParam(":usuario",$usuario);
            $consulta->execute();
            $datos = $consulta->fetch(PDO::FETCH_ASSOC);
            if($datos){
                if(password_verify($password,$datos['password'])){
                    Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'SEsion inicada con exito',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        $this->crear_sesion($datos);
                        window.location.href = 'login.php';
                    });


                }else{
                    Swal.fire({
                        icon: 'error',
                        title: '¡Error!',
                        text: 'Error en credenciales de acceso',
                        confirmButtonText: 'Aceptar'
                    });
                }
            }else{
                Swal.fire({
                    icon: 'error',
                    title: '¡Error!',
                    text: 'Error al buscar informacion',
                    confirmButtonText: 'Aceptar'
                });
            }
        }
    }

    $consulta = new Login();
    $metodo = $_POST['metodo'];
    $consulta->$metodo();
?>