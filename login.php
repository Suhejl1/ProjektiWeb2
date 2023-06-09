
<?php
    require('storeDB.php');

    session_start();
	$error = '';
	$salt = '';
        if(isset($_POST['submit'])){
            $email = $_POST['email'];
            $password = $_POST['password'];
            $salt_query = "SELECT `salt` FROM user WHERE email = '$email' ";
			
			$result = @mysqli_query($conn,$salt_query);
			if(mysqli_num_rows($result)==1){
				$row = mysqli_fetch_assoc($result);
				$salt = $row['salt'];
			}
			 

            $hashed = hash('sha256',$password.$salt);
            $hashed = substr($hashed,0,20);
			 
            
            $select = "SELECT * FROM user WHERE email= '$email' &&
                        password = '$hashed' ";
            
            $check = mysqli_query($conn,$select);



            if(mysqli_num_rows($check)==1){
                 
                $row = mysqli_fetch_array($check);
                
                if($row['role'] == 'admin'){
					 
					$name = $row['name'];
                    $_SESSION['admin'] = $name;
					$adminId = $row['id'];  
					$_SESSION['admin_id'] = $adminId;
                    header("Location:admin/users-tab/user.php", TRUE, 301);

            	}
                else if($row['role'] == 'user'){
					$name = $row['name'];
                    $_SESSION['user'] = $name;
					$id = $row['id'];
					$_SESSION['user_id'] = $id;
                    header("Location:user/user-page.php", TRUE, 301);
           	 	}	

			}	
			else{
                $error = "Account or password incorrect";
            }  
        }

    

?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
	<link rel="stylesheet" href="login.css">
	
</head>
<body>
   <div class="container" id="container">
	
	<div class="form-container sign-in-container">
		<form action="" method="post">
			<h1>Sign in</h1>
			
		
			<input type="email" name="email" placeholder="Email" />
			<input type="password" name="password" placeholder="Password" />
			<?php if(!empty($error)): ?>
                <span style="color: red;"><?php echo $error; ?></span>
            <?php endif; ?>
			<input type="submit" name="submit" value="Submit">
		</form>
	</div>
	<div class="overlay-container">
		<div class="overlay">
			<div class="overlay-panel overlay-left">
				<h1>Welcome Back!</h1>
				<p>To keep connected with us please login with your personal info</p>
				<button class="ghost" id="signIn">Sign In</button>
			</div>
			<<div class="overlay-panel overlay-right">
    <h1>Hello, Friend!</h1>
    <p>Enter your personal details and start the journey with us</p>
    <button class="ghost" id="signUp" onclick="window.location.href='signup.php'">Sign Up</button>
</div>
		</div>
	</div>
</div>
<script src="login.js"></script>
<?php  mysqli_close($conn); ?>
</body>
</html>