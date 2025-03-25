<?php
session_start();
$server="localhost";
$db_user="root";
$db_pass="";
$db_name="utilisateurs";
$conn="";
$conn=mysqli_connect($server,$db_user,$db_pass,$db_name);
$username=$_POST['username'];
$email=$_POST['email'];
$password=password_hash($_POST['password'],PASSWORD_DEFAULT);
$SQL="SELECT * FROM users where username=? or email=?";
$stmt=$conn->prepare($SQL);
$stmt->bind_param("ss",$username,$email);
$stmt->execute();
$result=$stmt->get_result();
if($result->num_rows >0){
    echo "email déja utilisé";
    header("refresh:1; URL=login.php");
    exit();
}
else{
    $sql="INSERT INTO users(username,email,password_hash,created_at) VALUES(?,?,?,CURRENT_TIMESTAMP)";
    $stmt=$conn->prepare($sql);
$stmt->bind_param("sss",$username,$email,$password);
$stmt->execute();
$_SESSION['username']=$username;
$_SESSION['email']=$email;
header("location: SB.php");
}
?>