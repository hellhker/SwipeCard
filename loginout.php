<?php 
session_start();
session_unset();
session_destroy();
$url="Login.php"; 
echo "<SCRIPT LANGUAGE=javascript>"; 
echo "location.href='$url'"; 
echo "</SCRIPT>"; 

?>
