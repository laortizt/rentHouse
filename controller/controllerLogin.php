<?php

    if($petitionAjax){
        require_once "../models/modelLogin.php";
    }else{
        // si la eticion ajax es fale aceder a la configuración DB
        require_once "./models/modelLogin.php";
    }

    class controllerLogin extends modelLogin{
        // Funcion para iniciar sesion

        public function start_session_controller(){
            //se limpian los espacios que se piden en login por si traian partes de cadena anterior 
            //y luego se usa la nueva cadena en las variables

            $email=mainModel::clean_string($_POST['emailSignIn']);
            $password=mainModel::clean_string($_POST['passwordSignIn']);

            //se encripta la contraseña
            $password=mainModel::encryption($password);

            //se pasan los datos del login a una variable para usarlos en el modelo
            $logindata=[
                "email"=>$email,
                "password"=>$password,
            ];

            //se pasan los datos del login al modelo
            $accountdata= modelLogin::start_session_model($logindata);
            
            if($accountdata->rowCount()==1){
                $userrow=$accountdata->fetch();

                session_start(['name'=>'SK']);
                
                $_SESSION['email_sk']=$userrow['accountEmail'];
                $_SESSION['code_sk']=$userrow['accountCode'];
                
                if ($userrow['accountRole'] == 1) {
                    $_SESSION['role_sk']="Administrador";
                } else if ($userrow['accountRole'] == 2) {
                    $_SESSION['role_sk']="Instructor";
                } else {
                    $_SESSION['role_sk']="Usuario";
                }

                // Identificador único de la sesión, se usa al cerrar la sesión
                $_SESSION['token_sk']=md5(uniqid(mt_rand(),true));    

                //Se agrega este código para acceder a las vistasdependiendo el tipo de usuario
                if($_SESSION['role_sk']==="Administrador"){
                    $url=SERVERURL."admin";
                }else{
                    $url=SERVERURL."class";
                }

                return '<script> window.location= " '.$url.'" </script>';
            } else{
                $alert=[
                    "alert"=>"simple",
                    "title"=>"Ocurrió un error inesperado",
                    "text"=>"El nombre de usuario y contraseña no son correctos o su cuenta puede estar deshabilitada",
                    "type"=>"error"
                ];
                return mainModel::sweet_alert($alert);
            }
        }

        public function close_session_controller() {
            session_start(['name'=>'SK']);
            
            $token=mainModel::decryption($_GET['token']);

            $datos=[
                "email"=>$_SESSION['email_sk'],
                "token_s"=>$_SESSION['token_sk'],
                "token"=>$token
            ];

            return modelLogin::close_session_model($datos);
        }

        // función para forzar cerrar sesión
        public function force_logout(){
            session_destroy();
            return header("Location: ".SERVERURL."login");
        }
    }
?>
