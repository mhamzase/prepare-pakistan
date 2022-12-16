<?php 

session_start();
include "database/connection.php";

if(isset($_SESSION['loggedIn']))
{

	$session_email = $_SESSION['loggedIn'];

	$user_query = "SELECT * FROM users WHERE email = '$session_email'";
	$user_result = mysqli_query($conn,$user_query);

	$user_data = mysqli_fetch_assoc($user_result);
	$db_type = $user_data['type'];
	
		if($dp_type == 0)
		{
			header("Location: admin-dashboard.php");
		}
		if($db_type == 1){
			header("Location: user-dashboard.php");
		}
}


	if(isset($_POST['register']))
	{	
		$username = mysqli_real_escape_string($conn, $_POST['username']);
		$email = mysqli_real_escape_string($conn, $_POST['email']);

		$temp_password = mysqli_real_escape_string($conn, $_POST['password']);
		$password = password_hash($temp_password,PASSWORD_BCRYPT);
		
		$type = 1;

    $token = md5(uniqid(rand(), true));
    
		
		$sql = "SELECT * FROM users WHERE username = '$username'";
      	$result = mysqli_query($conn,$sql);
		$count_username = mysqli_num_rows($result);

		if($count_username)
		{
			header("Location: registration-form.php?error=username already exists !");
		}
		else{
			$sql1 = "SELECT * FROM users WHERE email = '$email'";
      		$result1 = mysqli_query($conn,$sql1);
			$count_email = mysqli_num_rows($result1);

			if($count_email)
			{
				header("Location: registration-form.php?error=email already exists !");
			}
			else{
				$sql = "INSERT INTO `users`(`username`, `email`, `password`, `type`,`token`) VALUES ('$username','$email','$password','$type','$token')";

				if(mysqli_query($conn,$sql))
				{
					header("Location: registration-form.php?success=You are registered successfully !!! <br/> Redirecting to Login Page...");
					$_POST['username'] = "";
					$_POST['email'] = "";
					$_POST['password'] = "";	
					$_POST['cpassword'] = "";
                    
				}
				

				
			}
		}
	}

	mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registration</title>
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/register.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

</head>

<body>
  <!-- include header file -->
  <?php include "includes/header.php" ?>

  <div class="container mb-5">
    <div class="card border-primary register-form">
      <div class="card-header text-center">
        <h4 class="display-4">Registration</h4>
      </div>

      <!-- Error messages -->
      <span class="badge bg-danger p-3 m-3" id="message"></span>

      <?php
      if (isset($_GET['success'])) {
      ?>
        <span class='badge bg-success p-3 m-3'><?php echo $_GET['success']; ?></span>
      <?php
      header("Refresh:2 , url=login-form.php");
      }
      if (isset($_GET['error'])) {
      ?>
        <span class='badge bg-danger p-3 m-3'><?php echo $_GET['error']; ?></span>
      <?php
      }
      ?>


      <form action="" method="post" onsubmit="return validateRegistrationForm()">
        <div class="card-body">
          <div class="form-group">
            <input type="text" id="username" name="username" class="form-control" id="exampleInputUsername" placeholder="Username*" />
          </div>
          <div class="form-group">
            <input type="email" id="email" name="email" class="form-control mt-3" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email*" />
            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
          </div>
          <div class="form-group">
            <input type="password" id="password" name="password" class="form-control mt-3" id="exampleInputPassword1" placeholder="Password*" />
          </div>
          <div class="form-group">
            <input type="password" id="cpassword" name="cpassword" class="form-control mt-3" id="exampleInputPassword1" placeholder="Confirm Password*" />
          </div>

          <button type="submit" name="register" class="btn btn-primary mt-5 w-100">Register</button>
        </div>
      </form>
    </div>
  </div>

  <?php include "includes/footer.php" ?>

  <script src="js/register.js"></script>
</body>

</html>