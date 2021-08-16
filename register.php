<?php session_start();?>
<?php
    include "config.php"; 

    //echo'<pre>';print_r($_POST['email']);exit;
	if(isset($_POST['register'])){
		$username = $_POST['username'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		$user_type = $_POST['user_type'];

		$query="INSERT INTO `user_tbl` (`username`,`email`, `password`,`user_type`) VALUES ('$username','$email', '$password','$user_type')";
			
		$result=mysqli_query($link,$query);


		if($result){
			// Fetch result rows as an associative array
			echo("<script>location.href = 'index.php';</script>");
		} else{
			
			$_SESSION['msg_reg']="Not Registered";
			echo("<script>location.href = 'register.php';</script>");

		}
	}
    


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Plagiarism</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="images/img-01.png" alt="IMG">
				</div>

				<form method="POST" action="#" class="login100-form validate-form">
					<span class="login100-form-title">
						Register
					</span>
					<?php 
					if(isset($_SESSION)){?>
						<div class="text-center p-b-12">
							<span style="color:#a20202;" class="txt2">
								<?php
								if(isset($_SESSION['msg_reg'])){
									echo $_SESSION['msg_reg'];
								} ?>
							</span>
						</div>
					<?php } ?>
					
					<div class="wrap-input100 validate-input" data-validate = "Username is required">
						<input class="input100" type="text" name="username" placeholder="Username">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Valid email is required: ex@abc.xyz">
						<input required class="input100" type="text" name="email" placeholder="Email">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Password is required">
						<input required class="input100" type="password" name="password" placeholder="Password">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "User Type is required">
						<select class="input100" name="user_type">
							<option value="">User Type</option>
							<option value="admin">Admin</option>
							<option value="user">User</option>
						</select>
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user" aria-hidden="true"></i>
						</span>
					</div>
					
					<div class="container-login100-form-btn">
						<button type="submit" name="register" class="login100-form-btn">
							Register
						</button>
					</div>

					<div class="text-center p-t-12">
						<span class="txt1">
							Forgot
						</span>
						<a class="txt2" href="#">
							Username / Password?
						</a>
					</div>

					<div class="text-center p-t-136">
                        <span class="txt1">
                            Already Registered?
						</span>
						<a class="txt2" href="index.php">
							Login
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	

	
<!--===============================================================================================-->	
	<script src="https://code.jquery.com/jquery-3.6.0.js"></script>

<!--===============================================================================================-->
	
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<!--===============================================================================================-->



</body>
</html>