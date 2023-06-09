<?php

 require('../../storeDB.php');
 session_start();
 $name = isset($_SESSION['admin']) ? $_SESSION['admin'] : null;
 // nese preket back button dhe munohet te kete qasje pa bere login
 if ($name === null ) {
    // User is not logged in or session expired, redirect to the login page
    header('Location: ../../login.php');
    exit();
}
    $length = 10;
		function generate_salt($length){
			$chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			$salt = '';
	
			for($i=0;$i<$length;$i++){
				$index = rand(0,strlen($chars)-1);
				$salt .= $chars[$index];
			}
	
			return $salt;
		}

 if(isset($_POST['registerbtn'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cfpassword = $_POST['cfpassword'];
    $data = date('d/m/Y');

    // RegEx
    $usernameRegex = "/^[a-zA-Z0-9_]{3,20}$/";
		$emailRegex = "/^[a-zA-Z0-9+_.-]+@[a-zA-Z0-9.-]+$/";
		$passwordRegex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
	
		$errors = "";
		$u = "SELECT name from user where name = '$name'";
    $u_query = mysqli_query($conn,$u);
            
    $e = "SELECT email from user where email ='$email'";
    $e_query = mysqli_query($conn,$e);

    if($role='admin'){

      if (!preg_match($usernameRegex, $name)) {
        $errors = "Invalid username format";
      }
      elseif (!preg_match($emailRegex, $email)) {
        $errors = "Invalid email format";
      }
      elseif (!preg_match($passwordRegex, $password)) {
        $errors = "Invalid password format";
      }
      elseif(mysqli_num_rows($u_query)>0){
          $errors = "Username is already used ";
          header('Location: registerAdmin.php');
      }
      elseif(mysqli_num_rows($e_query)>0){
          $errors = "Email is already used";
          header('Location: registerAdmin.php');  
      }
      else{
          if($password == $cfpassword){
                  $salt = generate_salt($length);
                  $hashed = hash('sha256',$password.$salt);
                  $query = "INSERT INTO user(name,role,email,salt,password,datat) 
                  VALUES ('$name','$role','$email','$salt','$hashed','$data')";
                  $query_run = mysqli_query($conn, $query);

                  if($query_run)
                  {
                      // echo "Saved";
                      $errors="Admin Profile added";
                      header('Location: registerAdmin.php');
                  }
                  else 
                  {
                      $errors="Admin Profile not added";
                      header('Location: registerAdmin.php');  
                  }
              }else {
                  $errors = "Password and confirm password should be the same";
                  header('Location: `registerAdmin.php');  
                }
          }
    }

}


?>



<!Doctype HTML>
<html>
<head>
	<title></title>
	<link rel="stylesheet" href="../css/admin.css" type="text/css"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<style>
    @import url('https://fonts.googleapis.com/css?family=PT+Sans');

body{

  font-family: 'PT Sans', sans-serif;
}
.container {
  border: 5px solid;
  margin: auto;
  width: 50%;
  padding: 10px;
}
.card {
    border: 5px solid;
  margin: auto;
  width: 50%;
  padding: 10px;
}
h2{
  padding-top: 1.5rem;
}
a{
  color: #333;
}
a:hover{
  color: #da5767;
  text-decoration: none;
}
.card{
  border: 0.40rem solid #f8f9fa;
  top: 10%;
}
.form-control{
  background-color: #f8f9fa;
  padding: 20px;
  padding: 25px 15px;
  margin-bottom: 1.3rem;
}

.form-control:focus {

    color: #000000;
    background-color: #ffffff;
    border: 3px solid #da5767;
    outline: 0;
    box-shadow: none;

}

.btn{
  padding: 0.6rem 1.2rem;
  background: #da5767;
  border: 2px solid #da5767;
}
.btn-primary:hover {

    
    background-color: #df8c96;
    border-color: #df8c96;
  transition: .3s;

}
</style>

<body>
	
<div id="mySidenav" class="sidenav">
	<p class="logo"><span>Gamics</span></p>
  
  <a href="../users-tab/user.php"class="icon-a"><i class="fa fa-users icons"></i> &nbsp;&nbsp;Users</a>
  <a href="../admins-tab/admins.php"class="icon-a"><i class="fa fa-lock" aria-hidden="true"></i> &nbsp;&nbsp;Admins</a>
  <a href="../products-tab/products.php"class="icon-a"><i class="fa fa-gamepad icons"></i> &nbsp;&nbsp;Products</a>
  <a href="../faq.php"class="icon-a"><i class="fa fa-list-alt icons"></i> &nbsp;&nbsp;Faq</a>
  <a href="../logout.php"class="icon-a"><i class="fa fa-level-down icons"></i> &nbsp;&nbsp;Log Out</a>
  
</div>
  <div id="main">

	<div class="head">
		<div class="col-div-6">
        <span style="font-size:30px;cursor:pointer; color: white;" class="nav"  >&#9776; Admins</span>
    </div>
        
    
    
	
    <div class="col-div-6">
	<div class="profile">

		<img src="..\..\assets\images\admin.png" class="pro-img" />
		<p><?php echo strtoupper($name) ?>
		<span>ADMIN</span></p>
	</div>
  </div>
	<div class="clearfix"></div>
</div>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card">
        <h2 class="card-title text-center" style="color:#ffffff" >Register</h2>
        <div class="card-body py-md-4">
          <form _lpchecked="1" method="POST" >
            <div class="form-group">
              <input type="text" class="form-control" name="name" id="name" placeholder="Name">
            </div>
            <div class="form-group">
              <input type="email" class="form-control" name="email" id="email" placeholder="Email">
            </div>

            <div class="form-group">
              <input type="password" class="form-control" name="password" id="password" placeholder="Password">
            </div>
            <div class="form-group">
              <input type="password" class="form-control" name="cfpassword" id="confirm-password" placeholder="confirm-password">
            </div>
            <?php if(isset($errors)): ?>
              <span><?php echo $errors; ?> </span>
            <?php endif ?>
            <div class="d-flex flex-row align-items-center justify-content-between">
              
              <button type="submit" name="registerbtn" class="btn btn-primary">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<?php  mysqli_close($conn); ?>
</body>


</html>
