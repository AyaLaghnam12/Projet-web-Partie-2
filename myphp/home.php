<?php
session_start();
$server="localhost";
$db_user="root";
$db_pass="";
$db_name="utilisateurs";
$conn="";
$conn=mysqli_connect($server,$db_user,$db_pass,$db_name);
$email=$_POST['email'];
$password=$_POST['password'];
$SQL="SELECT username,email,password_hash FROM users where email=?";
$stmt=$conn->prepare($SQL);
$stmt->bind_param("s",$email);
$stmt->execute();
$result=$stmt->get_result();
if($result->num_rows >0){
   $user = $result->fetch_assoc();
    $hashed_password = $user['password_hash'];
    $username=$user['username'];
    if(password_verify($password,$hashed_password)){
        $_SESSION['username']=$username;
        $_SESSION['email']=$email;
header("location:SB.php");
    }
}
else{
    echo "mot de passe ou email incorrect";
    header("refresh:1; URL=login.php");
    exit();
}
?>