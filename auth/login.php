<?php

 include('../config/db_connect.php');

 error_reporting(E_ALL ^ E_NOTICE);
 session_start();

 $email = $password = '';
 $errors = ['email' => '', 'password' => ''];
 $resErr = ['message' => ''];

 if (isset($_POST['submit'])) {
   
   if (empty($_POST['email'])) {
     $errors['email'] = 'Required*';

   } else {

     $email = $_POST['email'];

     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
       $errors['email'] = 'email must be valid email address';

     }
   }
   if (empty($_POST['password'])) {
     $errors['password'] = 'Required*';

   } else {

     $password = $_POST['password'];

     if (strlen($password) < 6) {
       $errors['password'] = 'password not long enough';

     } else {

       if (!preg_match('/^([a-zA-Z])+([0-9])+$/', $password)) {
       $errors['password'] = 'password must contain a number';

      }
     }

   }

   if (!array_filter($errors)) {

     $email = mysqli_real_escape_string($conn, $_POST['email']);
     $password = mysqli_real_escape_string($conn, $_POST['password']);
     $hash_password = md5($password);

     $sql = "SELECT * FROM account WHERE emailaddress = '$email' AND psw = '$hash_password'";
     $result = mysqli_query($conn, $sql);
     $count = mysqli_num_rows($result);


     if ($count > 0) {

       $user = mysqli_fetch_assoc($result);

       $_SESSION['id'] = $user['id'];
       $_SESSION['firstname'] = $user['firstname'];
       $_SESSION['lastname'] = $user['lastname'];
       $_SESSION['email'] = $user['emailaddress'];

       header('location: ../dashboard.php');
     } else {

        $res['message'] = 'Wrong Email Address or Password';
     }
   }

 }



?>

<head>
  <title>Estorex/login</title>
  <link rel="stylesheet" href="../assets/styles/login.css">
</head>

<div class="signin_container">
  <div class="signin_img_holder">
    <div id="signin_content">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Ipsam voluptas a cumque expedita obcaecati! Doloribus, culpa perspiciatis! Facere, reprehenderit tempore.</div>
    <div id="signin_img_wrapper"><img src="../assets/images/login.png" alt="login-illustratrion"></div>
  </div>


  <div id="signin_contact_holder">
    <h1><a href="../index.php" title="Estorex - home">Estorex</a></h1>
    <p><i>Login to your user account</i></p>
    <p id="errResponseText"><?php echo $res['message'] ?></p>
    <form method="POST" id="signin">
      <label for="email">Email Address</label>
      <input type="email" name="email" id="email" placeholder="email address" value="<?php echo htmlspecialchars($email); ?>">
      <p id="emailFeedBack"><?php echo $errors['email']; ?></p>
      <label for="psw">Password</label>
      <input type="password" name="password" id="psw" placeholder="password" value="<?php echo htmlspecialchars($password); ?>">
      <p id="pswFeedBack"><?php echo $errors['password']; ?></p>
      <p id="pswMe"></p>
      <button type="submit" name="submit">login</button><br>
      <p class="signin-content">Don't have an account <a href="signup.php"> sigup here</a></p>
    </form>
  </div>
</div>

<script src="../assets/js/login.js"></script>
