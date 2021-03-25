<!DOCTYPE html>
<html>
<head>
	<title>Registro</title>
	<link rel="stylesheet" type="text/css" href="./assets/css/style.css">
	<link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
	<script src="https://kit.fontawesome.com/a81368914c.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	<img class="wave" src="./assets/img/wave.png">
	<div class="container">
		<div class="img">
			<img src="./assets/img/login.svg">
		</div>
		<div class="login-content">
			<form action="" method="post" autocomplete="off" class="sign-up-form">
				
				<h2 class="title">Crear cuenta</h2>
					<div class="input-div one">
						<div class="i">
							<i class="fas fa-user"></i>
						</div>
						<div class="div">
							<h5>Nombre</h5>
							<input type="text" class="input" name="fistName">
						</div>
					</div>

				   <div class="input-div pass">
						<div class="i"> 
								<i class="fas fa-lock"></i>
						</div>
						<div class="div">
								<h5>Apellido</h5>
								<input type="password" class="input" name="LastName">
						</div>
            		</div>
					<div class="input-div pass">
						<div class="i"> 
								<i class="fas fa-lock"></i>
						</div>
						<div class="div">
								<h5>Correo</h5>
								<input type="password" class="input" name="emailSignUp">
						</div>
            		</div>
					<div class="input-div pass">
						<div class="i"> 
								<i class="fas fa-lock"></i>
						</div>
						<div class="div">
								<h5>Password</h5>
								<input type="password" class="input" name="passwordSignUp">
						</div>
					</div>
            	<a href="#">Forgot Password?</a>
            	<input type="submit" class="btn" value="ENTRAR">
            </form>
        </div>
    </div>
    <script type="text/javascript" src="./assets/js/main.js"></script>
</body>

<?php
    if(isset($_POST['emailSignIn'])&& isset($_POST['passwordSignIn']) && $_POST['emailSignIn']!=""&&$_POST['passwordSignIn']!="" ){
      require_once "./controller/controllerLogin.php";
      $login = new controllerLogin();
      echo $login->start_session_controller();
    }
    elseif (isset($_POST['emailSignUp'])) {
      require_once "./controller/controllerSignUp.php";
      $insUser= new controllerSignUp();
  
      if(isset($_POST['emailSignUp'])&& 
          isset($_POST['firstname'])&&
          isset($_POST['lastname'])&&
          isset($_POST['passwordSignUp'])){
              echo $insUser->add_controller_User();
      } else {
        
        // echo $insUser->add_User_incomplete_data();  ERROR EN ESTÁ LINEA OOOOOJOOOOOOOO
      }
    }
  ?>
</html>

