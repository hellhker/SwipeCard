<?php 
	session_start();
	$access = $_SESSION["permission"];
	$depid = $_SESSION['depid'];
	$assistant_id = $_SESSION['assistant_id'];
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!-- 

<script src="assets/js/jquery-1.8.3.min.js"></script>
<link href="assets/css/bootstrap.css" rel="stylesheet">
<!-- Bootstrap stylesheets (included template modifications) -->

<link rel="stylesheet" type="text/css" href="easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="easyui/themes/icon.css">
<link rel="stylesheet" type="text/css" href="easyui/demo/demo.css">

<script type="text/javascript" src="easyui/jquery.min.js"></script>
<script type="text/javascript" src="easyui/jquery.easyui.min.js"></script>
<script src="Button_Plugins.js"></script>
<script src="ext_function.js"></script>

<title>線長審核</title>
</head>
<body class="pace-done">
	<?php 
		// $MYSQL_LOGIN = "root";
		// $MYSQL_PASSWORD = "foxlink";
		// $MYSQL_HOST = "192.168.65.230";

		// $mysqli = new mysqli($MYSQL_HOST,$MYSQL_LOGIN,$MYSQL_PASSWORD,"swipecard");
		// $mysqli->query("SET NAMES 'utf8'");	 
		// $mysqli->query('SET CHARACTER_SET_CLIENT=utf8');
		// $mysqli->query('SET CHARACTER_SET_RESULTS=utf8'); 
		include("mysql_config.php");
	
		$SDate = $_POST['SDate'];
		$WorkshopNo = $_POST['WorkshopNo'];
		$lineno = $_POST['LineNo'];
		$RC_NO = $_POST['rc_no'];
		$Item_No = $_POST['item_no'];
		$Shift = $_POST['Shift'];
		$w=date('w',strtotime($SDate));
		if($w==6||$w==0){
			$weekend = 1;
		}else{
			$weekend = 0;//TODO
		}
		if($Shift=="D"){
			$employee_overtime_sql = 
				"SELECT a.id, 
					   a.NAME, 
					   a.depid,
					   a.depname,
					   a.direct,
					   a.costid,
					   Date_format(b.swipecardtime2, '%Y-%m-%d')                       AS yd,
					   b.checkstate,
					   b.recordid,
					   b.overtimeCal,
					   b.overtimeType,
					   b.swipecardtime,
					   b.swipecardtime2
				FROM   testemployee AS a, 
					   testswipecardtime AS b 
				WHERE  a.cardid = b.cardid 
						and DATE_FORMAT(b.swipecardtime, '%Y-%m-%d') = '".$SDate."'
						and swipecardtime2 is not null
						and shift = 'D'
					   AND prod_line_code = '".$lineno."'
					   AND a.depid = '".$depid."'
						AND RC_NO = '".$RC_NO."'
					   and checkstate in('0','9') ";
		}else if($Shift=="N"){
			$employee_overtime_sql = 
				"SELECT a.id, 
					   a.NAME, 
					   a.depid,
					   a.depname,
					   a.direct,
					   a.costid,
					   date_format(date_sub(swipecardtime2,interval 12 hour),'%Y-%m-%d') as yd,
					   b.checkstate,
					   b.recordid,
					   b.overtimeCal,
					   b.overtimeType,
					   b.swipecardtime,
					   b.swipecardtime2
				FROM   testemployee AS a, 
					   testswipecardtime AS b 
				WHERE  a.cardid = b.cardid 
						and DATE_FORMAT(b.swipecardtime, '%Y-%m-%d') = '".$SDate."'
						and shift = 'N'
						and swipecardtime2 is not null
					   AND prod_line_code = '".$lineno."'
					   AND a.depid = '".$depid."'
						AND RC_NO = '".$RC_NO."'
					   and checkstate in('0','9') ";
		}
		// echo $employee_overtime_sql;
		if($Shift=="D"){
			$interval_sql = "select * from interval_setting where WorkshopNo='$WorkshopNo' and weekend = '$weekend' and Shift = '$Shift'";
		}else{
			$interval_sql = "select * from interval_setting where WorkshopNo='$WorkshopNo'  and Shift = '$Shift'";
		}
		
		$timeset_row = $mysqli->query($interval_sql);
		$temp = array();
		// echo $interval_sql.'<br>';
		while($row1 = $timeset_row->fetch_assoc()){
			$temp[] = $row1['d_interval1'];
			$temp[] = $row1['d_interval2'];
			$temp[] = $row1['d_interval3'];
			$temp[] = $row1['d_interval4'];
			$temp[] = $row1['d_interval5'];
		}
		foreach($temp as $key => $value){
			if($value==end($temp)){
				$cch_t_set.= $value;
			}else{
				$cch_t_set.= $value."*";
			}
		}	
			   
		$person_sql = "select * from assistant_data where application_id='$assistant_id'";
		// echo $person_sql."<Br>";
		$zhuli_rows = $mysqli->query($person_sql);
		while($row = $zhuli_rows->fetch_assoc()){
			$application_person = $row['application_person'];
			$application_id = $row['application_id'];
			$application_dep = $row['application_dep'];
			$application_tel = $row['application_tel'];
		}
		
		mysqli_free_result($zhuli_rows);	   
		if(strcmp($application_person,"")<=0){
			echo "當前對應助理信息缺失，不能提交，請嘗試更換電腦再重新嘗試。";
		}
	?>
	<div>
	
	</div>
	<div class="panel-body" style="border: 1px solid #e1e3e6;">
		時間： <select id="overtimeCal" onclick="getValueB()">
			<option value="0">待選</option>
			<option value="1">正常班</option>
			<option value="2">假日班</option>

		</select> 加班類型： <select id="overtimeType" onclick="setOverType()">
			<option value="0">待選</option>
			<option value="1">加班1</option>
			<option value="2">加班2</option>
			<option value="3">加班3</option>
		</select>
		當前對應助理為：<?php echo $application_person;?>
		<?php 
			if($RC_NO==NULL){
				echo"工作內容：<input type=\"text\" id=\"workcontent\" />" ."";
			}
		?>
		<table class="table table-striped" id="tbl">
			<tr>
				<th class="per5"><input name="checkbox1" type="checkbox"
					id="inlineCheckbox1" value="option1" onclick="allCheck(this)">
				</th>
				<th>工號</th>
				<th>名字</th>
				<th>部門代碼</th>
				
				<th>費用代碼</th>
				<th>直間接人員</th>
				
				<th>加班日期</th>
				<th>加班時段</th>
				<th>加班小時</th>
				<th>加班類型</th>
				<th>審核狀態</th>
			</tr>
			<?php 
			// echo $employee_overtime_sql;
				$over_rows = $mysqli->query($employee_overtime_sql);
				// echo mysqli_num_rows($over_rows);
				$cch = '';
				$j=1;
				while($row = $over_rows->fetch_row()){
					
					// echo $row['ids'];
					// echo $row[3];
					if($row[7]==0){
						$checkState="未審核";
					}else if($row[7]==9){
						$checkState="退回";
					}
					
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
					  
					  ."<td id=\"cal_".$row[8]."\" class=\"changeStatus\" >"
					  . "<input type=\"text\" class=\"textBoxtest\" style=\"width:50px;height:32px\" value=\"\" readonly />"
					  . "<input class=\"easyui-switchbutton\" name=\"stButton\" id=\"but_".$row[8]."\" value=\"\"  /> "
					  . "</td>"
					  . "<td id=\"type_".$row[8]."\"></td>"
					  . "<td>".$checkState."</td>"
					  . "<td><input class=\"easyui-textbox\" id=\"reason_".$row[8]."\" style=\"width:100%;height:32px\" readonly /></td>"
					  
					  ."</tr>";
					 $j++;
				}
				// echo $cch;
				
			?>
		</table>
		<?
			if(strcmp($application_person,"")>0){
					echo "<input class=\"btn btn-primary\" name=\"\" type=\"button\"\n";
					echo "		onclick=\"check()\" value=\"提交\" />\n";
			}
		?>
	</div>
	<div>
		<input type="hidden" id="LineNo" value="<?php echo $lineno?>"/>
		<input type="hidden" id="RC_NO" value="<?php echo $RC_NO?>" />
		<input type="hidden" id="Item_No" value="<?php echo $Item_No?>" />
		<input type="hidden" id="Shift" value="<?php echo $Shift?>" />
		<input type="hidden" id="WorkshopNo" value="<?php echo $WorkshopNo?>" />
		<input type="hidden" id="Interval_Setting" value="<?php echo $cch_t_set //TODO_List ?>" />
	</div>
	<!-- 
	<input name="" type="button" onclick="location.href = 'index_test.jsp'"		value="返回" />
	 -->
</body>
</html>


