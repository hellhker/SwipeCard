<?php 
	session_start();
	$access = $_SESSION["permission"];
?>
<html>

<head>
<!-- Bootstrap stylesheets (included template modifications) -->
<link href="assets/css/bootstrap.css" rel="stylesheet">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>查詢頁面</title>
</head>
<body>

<input type="text" id="employee_id" />
<input type="button" id="commit" value="添加" />
<table class="table table-striped" id="tbl">
	<tr>
		<th class="per5"><input name="checkbox1" type="checkbox"
			id="inlineCheckbox1" value="option1" onclick="allCheck(this)">
		</th>
		<th>序號</th>
		<th>工號</th>
		<th>名字</th>
		<th>部門代碼</th>
		
		<th>費用代碼</th>
		<th>直間接人員</th>
	</tr>
	<?php 
		$over_rows = $mysqli->query($employee_overtime_sql);
		$cch = '';
		$j=1;
		while($row = $over_rows->fetch_row()){
			echo"<tr id =\"".$j."\">"
			  . "<input type=\"hidden\" id=\"depname\" value=\"".$row[3]."\"/>"
			  . "<input type=\"hidden\" name=\"stime\" value=\"".$row[11]."\" />"
			  . "<input type=\"hidden\" name=\"etime\" value=\"".$row[12]."\" />"
			  . "<td><input id=\"checkValue\" name=\"checkbox\" type=\"checkbox\" value=\"".$row[8]."_".$j."\"></td>"
			  . "<td>".$j."</td>"
			  . "<td><input type=\"hidden\" name=\"testid\" value=\"".$row[0]."\" />".$row[0]."</td>"
			  . "<td><input type=\"hidden\" name=\"name\" value=\"".$row[1]."\" />".$row[1]."</td>"
			  . "<td><input type=\"hidden\" name=\"depid\" value=\"".$row[2]."\" />".$row[2]."</td>"
			  . "<td><input type=\"hidden\" name=\"costid\" value=\"".$row[5]."\" />".$row[5]."</td>"
			  . "<td><input type=\"hidden\" name=\"direct\" value=\"".$row[4]."\" />".$row[4]."</td>"
			  . "<td><input type=\"hidden\" name=\"yd\" value=\"".$row[6]."\" />".$row[6]."</td>"
			  . "<td id=\"tck_".$row[8]."\"></td>"
			  
			  ."</tr>";
			 $j++;
		}
		// echo $cch;
		
	?>
</table>
<input type="button" id="delete" value="刪除" />
	
</body>
</html>