<?php
    if($petitionAjax){
        require_once "../config/configApp.php";
    }else{
        // si la peticion ajax es false aceder a la configuración DB
        require_once "./config/configApp.php";
    }

    //modelo principal
    class mainModel{

        //nos permite idenficar las peticiones 
        protected function connect(){
            $link = new PDO(SGBD,USER,PASS);
            return $link;
        }

        //ejecutar consula simple
        protected function run_simple_query($query){
            //recibe la consulta
            $answer=self::connect()->prepare($query);
            $answer->execute();

            return $answer;
        }

        //crear cuenta
        protected function add_account($datos) {
            $sql=self::connect()->prepare("INSERT INTO accounts 
                (accountCode, accountEmail, accountPassword, accountRole, accountState, accountFirstName, accountLastName)
                VALUES (:Code, :Email, :Password, :Role, :State, :FirstName, :LastName)");
            $sql->bindParam(":Code",$datos['Code']);
            $sql->bindParam(":Email",$datos['Email']);
            $sql->bindParam(":Password",$datos['Password']);
            $sql->bindParam(":Role",$datos['Role']);
            $sql->bindParam(":State",$datos['State']);
            $sql->bindParam(":FirstName",$datos['FirstName']);
            $sql->bindParam(":LastName",$datos['LastName']);
            
            $sql->execute();

            return $sql;
        }

        //eliminar cuenta
        protected function delete_account ($code){
            $sql=self::connect()->prepare("DELETE FROM accounts WHERE accountCode=:Code");
            $sql->bindParam(":Code",$code);
            $sql->execute();

            return $sql;
        }

        //encriptar strings
        public function encryption($string){
            $output=FALSE;
            $key=hash('sha256',SECRET_KEY);
            $iv=substr(hash('sha256',SECRET_IV), 0, 16);
            $output=openssl_encrypt($string, METHOD, $key, 0, $iv);
            $output=base64_encode($output);
            return $output;
        }

        //desencriptar strings
        public function decryption($string){
            $key=hash('sha256',SECRET_KEY);
            $iv=substr(hash('sha256',SECRET_IV), 0, 16);
            $output=openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
            return $output;
        }

        //generar codigo aleatorío
        protected function generate_random_code($letra, $longitud, $num){
            for($i=1; $i<=$longitud; $i++){
                $numero = rand(0,9);
                $letra.=$numero;
            }

            return $letra.$num;
        }

        //Limpiar cadenas de texto
        protected function clean_string($string){
            //trim elimina espacios en blanco
            $string=trim($string);
            //strip quita las baras de un string con comillas
            $string=stripslashes($string);
            //sobreescribir un string
            $string=str_ireplace("<script>", "", $string);
            $string=str_ireplace("</script>", "", $string);
            $string=str_ireplace("<script src", "", $string);
            $string=str_ireplace("<script type=", "", $string);
            $string=str_ireplace("SELECT * FROM", "", $string);
            $string=str_ireplace("DELETE FROM", "", $string);
            $string=str_ireplace("INSERT INT", "", $string);
            $string=str_ireplace("--", "", $string);
            $string=str_ireplace("^", "", $string);
            $string=str_ireplace("[", "", $string);
            $string=str_ireplace("]", "", $string);
            $string=str_ireplace("==", "",$string);

            return $string;
        }

        //función para mostrar alertas
        protected function sweet_alert($datos){
            if ($datos['alert']=="simple"){
                $alert="
                <script>
                swal( 
                    '".$datos['title']."',
                    '".$datos['text']."',
                    '".$datos['type']."',
                    ); 
                </script>
                ";
            }elseif($datos['alert']=="recargar"){
                $alert="
                <script>
                    swal({ 
                        title: '".$datos['title']."',
                        text: '".$datos['text']."',
                        type: '".$datos['type']."',
                        confirmButtonText: 'Aceptar'
                    }).then(function(){
                        $('.formulario-ajax')[0].reset();
                    }); 
                </script>
                ";
            }elseif($datos['alert']=="limpiar"){
                $alert="
                <script>
                    swal({ 
                        title: '".$datos['title']."',
                        text: '".$datos['text']."',
                        type: '".$datos['type']."',
                        confirmButtonText: 'Aceptar'
                    }).then(function(){
                        $('.formulario-ajax')[0].reset();
                    }); 
                </script>
                ";
            }

            return $alert;
        }
    }
