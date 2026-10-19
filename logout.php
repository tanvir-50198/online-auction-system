<?php
session_start();
session_destroy();
header("Location: views/login.php?logout=1");
exit();
?>