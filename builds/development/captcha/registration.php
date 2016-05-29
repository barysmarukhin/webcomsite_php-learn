<?php
session_start();
$result='';
if ($_SERVER['REQUEST_METHOD']=='POST'){
    if ($_SESSION['randStr']){
        if ($_POST['str']==$_SESSION['randStr']){
            $result='Хорошо';
        }else{
            $result='Плохо';
        }
    }else{
        $result='Включи графику!!!';
    }
    header("Location: registration.php?result=$result");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Регистрация</title>
</head>
<body>
<h1>Регистрация</h1>
<form action="" method="post">
	<div>
		<img src="noise-picture.php">
	</div>
	<div>
		<label>Введите строку</label>
		<input type="text" name="str" size="6">
	</div>
	<input type="submit" value="OK">
</form>
<?php 
echo $_GET['result'];
session_destroy();
?>
</body>
</html>
