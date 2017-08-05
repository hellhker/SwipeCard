<?php 
session_start();

// $MYSQL_LOGIN = "root";
// $MYSQL_PASSWORD = "foxlink";
// $MYSQL_HOST = "192.168.65.230";

// $mysqli = new mysqli($MYSQL_HOST,$MYSQL_LOGIN,$MYSQL_PASSWORD,"swipecard");
// $mysqli->query("SET NAMES 'utf8'");	 
// $mysqli->query('SET CHARACTER_SET_CLIENT=utf8');
// $mysqli->query('SET CHARACTER_SET_RESULTS=utf8'); 

include("mysql_config.php");

$userName = $_POST["username"];
$password = $_POST["password"];



$user_sql = "select swipe_system_chief,DepartmentCode,costid,assistant_id from user_data where userid = '".$userName."' and password = '".$password."'";
$user_rows = $mysqli->query($user_sql);
$user_num = $user_rows->num_rows;
// echo $user_sql;
// exit;
while($rows = $user_rows->fetch_row()){
	$level  = $rows[0];
	$depid = $rows[1];
	$costid = $rows[2];
	$assistant_id = $rows[3];
}
	if($user_num > 0 && $level>=2){
		$_SESSION['userName']=$userName;
		$_SESSION['password']=$password;
		$_SESSION['costid'] = $costid;
		$_SESSION['assistant_id'] = $assistant_id;
		$url="index.php"; 
		echo "<SCRIPT LANGUAGE=javascript>"; 
		echo "location.href='$url'"; 
		echo "</SCRIPT>"; 
		session_write_close();
		mysqli_free_result($user_rows);	
		$mysqli->close();
	}else if($user_num > 0 && $level==1){
		$_SESSION['userName']=$userName;
		$_SESSION['password']=$password;
		$_SESSION['depid'] = $depid;
		$_SESSION['assistant_id'] = $assistant_id;
		// $_SESSION['assistant_id'] = "K4511";
		$url="index_lineleader.php"; 
		echo "<SCRIPT LANGUAGE=javascript>"; 
		echo "location.href='$url'"; 
		echo "</SCRIPT>"; 
		session_write_close();
		mysqli_free_result($user_rows);	
		$mysqli->close();
	} else{
		$mysqli->close();
		echo "<meta http-equiv='content-type' content='text/html; charset=UTF-8'>";
	        echo "<SCRIPT Language=javascript type=\"text/javascript\">"; 
            echo "window.alert('用戶名錯誤或密碼錯誤!!!')";  
            echo "</SCRIPT>";
            echo "<script language=\"javascript\">"; 
            echo "top.location.href='http://".$_SERVER['HTTP_HOST']."/SwipeCard/Login.php'";
            echo "</script>"; 
	}
?>

<head id="Head1">
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
</head>
<body class="easyui-layout" style="overflow-y: hidden" scroll="no">
</body>
</html>