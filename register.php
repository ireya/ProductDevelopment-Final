<?php
  include('config/connect.php');
  include('templates/header.php');
  $name=$email=$password=$cpassword='';
  $errors = array('name'=>'','email' => '','password' => '','cpassword'=>'');
  $insertTrue=false;
if(isset($_POST['submit']))
{
  if(empty($_POST['username'])) {
    $errors['name'] = 'Username is required !';
  }
  else {
    $name = $_POST['username'];
    if(!preg_match('/^[a-zA-Z\s]+$/', $name)){
      $errors['name'] = 'Username must be letters and spaces only !';
    }
  }

  if(empty($_POST['email'])) {
    $errors['email'] = 'Email is required !';
  }
  else {
    $email = $_POST['email'];
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
      $errors['email'] = 'Email must be a valid email address !';
    }
  }

  if(empty($_POST['password'])) {
    $errors['password'] = 'Password is required !';
  }
  else if(strlen($_POST['password'])<8)
  {
    $errors['password']='Password must contain atleast 8 characters !';
  }
  else {
    $password = $_POST['password'];
  }

  if(empty($_POST['cpassword'])) {
    $errors['cpassword'] = 'Confirm Password is required!!!';
  }
  else if($_POST['cpassword']!==$_POST['password'])
  {
    $errors['cpassword']='Password mismatch';
  }
  else {
    $cpassword = $_POST['password'];
  }

  $sql = "SELECT * FROM user_details WHERE email = '$email' ";
  $result = mysqli_query($conn,$sql);
  $rowcount = mysqli_num_rows($result);

  if($rowcount>0)
  {
    $errors['email'] = 'Email already exists!!';
  }

  if (array_filter($errors))
  {
  
  }
  else {
    $name = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5(mysqli_real_escape_string($conn, $_POST['password']));
    $insert = "INSERT INTO user_details(name,email,password) VALUES('$name','$email','$password')";
    $insertTrue = mysqli_query($conn,$insert);
    if($insertTrue) {
      header("Location: home.php");
    }
     else {
    echo 'query error: '. mysqli_error($conn);
   }

  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Memory Journal Application</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Agbalumo&display=swap');
    body{
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family:'Times New Roman', Times, serif ;
      overflow: hidden;
      
    }

    #navbar{
      background-color: rgb(237, 144, 144); 
      position: sticky;
      top: 0;
      left: 0;
      z-index: 100;
      padding: 0.5rem ;
      box-shadow: 5px 5px 20px rgba(0,0,0,0.5);
    }
    .navbar .navbar-brand{
      font-size: 25px;
      font-weight: 800;
    }
    #navbarSupportedContent a{
      font-size: 20px;
      color: white;
      border-bottom: 2px solid transparent;
    }
    #navbarSupportedContent a:hover{
      border-bottom: 2px solid black;
    }
    #navbarSupportedContentbtn button{
    background-color: aliceblue;
    width: 6rem;
    height: 38px;
    border-radius: 10px;
    margin-right: 8px;
    }

    .form{
      width: 400px;
      height: 490px;
      position: absolute;
      float: left;
      left: 700px;

      top: 50px;
      border: 2px solid black;
      margin-top: 5%;
      flex-direction: column;
      background: rgba(255,255,255,0.06);
      box-shadow: 0 8px 32px 0 rgba(31,38,135,.37);
      border-radius: 30px;
      border: 1px solid rgba(255,255,255,.3);
      border-top: 1px solid rgba(255,255,255,.3);
      background-attachment: fixed;  
    }
    label{
      font-size: 25px;
      color: rgb(23, 21, 21);  
      margin-left: 10%;
      text-align: left;
      opacity: .8;
      background-attachment: fixed;
    }
    h2{
      font-size: 50px;
      text-align: center;
      color: rgb(237, 144, 144);
      letter-spacing: 3px;
      margin-bottom:5%;
    }

    .form .btn{
      width: 30%;
      margin:2% auto;
      display :flex;
      font-size: larger;
      font-weight: bold;
      margin-top: 5%;
      color: rgb(14, 13, 13);
      font-size: 15px;
      opacity: .7;
      background: rgba(255,255,255,0.06);
      padding: 10px 30px;
      box-shadow: 3px 3px 5px rgba(237,144,144,.37);
      border: none;
    }
    p{
      font-size: 20px;
      margin-left: 38px;
    width: 70%;

    }
    .home{
      width: 100%;
      height: 100hv;
    }
    .form-control {
    margin-left: 38px;
    width: 73%;
    }
    .register-link a{
      color: rgb(237, 144, 144);
    }
    .timeline {
            display: flex;
            overflow-x: auto;
        }
    .entry {
        border: 1px solid #ccc;
            margin: 10px;
            padding: 10px;
            max-width: 300px;
            text-align: center;
        }
        .entry img {
            max-width: 100%;
            max-height: 150px;
            object-fit: cover;
            margin-bottom: 10px;
        }
  </style>
</head>
<body>
  <div class="header">
    <nav p-5 class="navbar navbar-expand-lg navbar-dark " id="navbar">
      <a class="navbar-brand" href="#">Memory Journal Application</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"  aria-expanded="false">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mx-auto">
          <li class="nav-item ">
            <a class="nav-link" href="home.php">Home </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="timeline.php">Timeline </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="contact.php">Contact Us </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="home.php">Home </a>
          </li>
        </ul>
      </div>
      <div id="navbarSupportedContentbtn">
        <a href="register.php"><button>Sign Up</button></a>
        <a href="login.php"><button>Login</button></a>
      </div>
    </nav>
  </div>
  <div>
    <img src="background.jpg">
  </div>
  <div class="form">
    <h2>SIGN UP</h2>
    <form action="register.php" method="POST">
      <div class="form-group">
      <label for="Name">Name</label>
        <input type="text" name="username" class="form-control" id="username" value="<?php echo htmlspecialchars($name)?>" autocomplete="off" placeholder="User Name">
        <div class="error"><?php echo $errors['name']; ?></div>
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="text" name="email" class="form-control" id="email" value="<?php echo htmlspecialchars($email)?>" autocomplete="off" placeholder="Email">
        <div class="error"><?php echo $errors['email']; ?></div>
      </div>
      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" class="form-control" id="password" value="<?php echo htmlspecialchars($password)?>" autocomplete="off" placeholder="Password">
        <div class="error"><?php echo $errors['password']; ?></div>
      </div>
      <div class="form-group">
        <label for="password">Confirm Password</label>
        <input type="password" name="cpassword" class="form-control" id="cpassword" value="<?php echo htmlspecialchars($password)?>" autocomplete="off" placeholder="Confirm Password">
        <div class="error"><?php echo $errors['cpassword']; ?></div>
      </div>
      <input type="submit" name="submit" class="btn" value="submit" id="sub1">
    </form>
  </div>
</body>
</html>