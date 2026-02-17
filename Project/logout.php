<?php
session_start();
session_destroy();//destroy your session
header("Location: login.php");
exit();
?>

