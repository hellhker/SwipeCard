<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>正在修改密码</title>
</head>
<body>
	<?php
	
	include("mysql_config.php");
	session_start ();
	$username = $_REQUEST ["username"];
	$oldpassword = $_REQUEST ["oldpassword"];
	$newpassword = $_REQUEST ["newpassword"];
	
	
	
	$dbusername = null;
	$dbpassword = null;
	$result_sql = "select UserID,Password from user_data where UserID ='$username'";
	echo $result_sql."<br>";
	
	$result = $mysqli->query($result_sql);
	while($row = $result->fetch_row()){
		$dbusername = $row [0];
		$dbpassword = $row [1];
	}
	mysqli_free_result($result);
	// echo $dbusername."<br>";
	if (is_null ( $dbusername )) {
		?>
	<script type="text/javascript">
		alert("用户名不存在");
		window.location.href="xgmm.php";
	</script>	
	<?php
	}
	if ($oldpassword != $dbpassword) {
		?>
	<script type="text/javascript">
		alert("密码错误");
		window.location.href="xgmm.php";
	</script>
	<?php
	}
	$update_sql = "update  user_data set Password='$newpassword' where UserID='$username'";
	$upRow = $mysqli->query($update_sql);
		// echo $update_sql;

	mysqli_free_result($upRow);
	?>


	<script type="text/javascript">
		alert("密码修改成功");
		window.location.href="xgmm.php";
	</script>
</body>
</html>