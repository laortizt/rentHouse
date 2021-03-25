<?php

    if($petitionAjax){
        require_once "../config/mainModel.php";
    }else{
        // si la eticion ajax es fale aceder a la configuración DB
        require_once "./config/mainmodel.php";
    }
    class modelLogin extends mainModel{
        //funcion que recibe los  datos del login para verificrlos con la base de datos
        protected function start_session_model($datos){
            //comparcion de los datos recibidos en el login y los datos guardados en la base de datos
            $sql=mainModel::connect()->prepare("SELECT * FROM accounts WHERE accountEmail=:Email AND accountPassword=:Password AND accountState = 1");
            $sql->bindParam(':Email',$datos['email']);
            $sql->bindParam(':Password',$datos['password']);
            $sql->execute();
            return $sql;
        }

        protected function close_session_model($datos){
            if($datos['email']!="" && $datos['token_s'] == $datos['token']){
                session_unset();
                session_destroy();
                $answer="true";
            }else{
                $answer="false";
            }
            return $answer;
        }
    }
    
