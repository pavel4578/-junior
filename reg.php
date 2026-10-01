<?php
include 'temp/bd.php';
$fio=$_POST['fio'];
$login=$_POST['login'];
$password=$_POST['password'];
$email=$_POST['email'];
$phone=$_POST['phone'];
$sql = "INSERT INTO `users` (`login`, `password`, `email`, `phone`, `role`, `fio`) VALUES 
('$login', '$password', '$email', '$phone', 'student', '$fio')";
$res=$mysqli->query($sql);
header('Location: formavto.php');
?>
