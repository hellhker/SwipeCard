<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>工時預警</title>
<link href="css/bootstrap.css" rel="stylesheet">
</head>
<body>

<?php 
	$sql = "select a.prod_line_code,b.id,b.name,b.depname,a.swipecardtime,a.swipecardtime2 from testswipecardtime a,testemployee b WHERE a.cardid=b.cardid and swipecardtime > date_sub(curdate(),interval 60 day) ORDER BY id, `swipecardtime` DESC";
	
	$MYSQL_LOGIN = "root";
	$MYSQL_PASSWORD = "foxlink";
	$MYSQL_HOST = "192.168.65.230";

	$mysqli = new mysqli($MYSQL_HOST,$MYSQL_LOGIN,$MYSQL_PASSWORD,"swipecard");
	$mysqli->query("SET NAMES 'utf8'");	 
	$mysqli->query('SET CHARACTER_SET_CLIENT=utf8');
	$mysqli->query('SET CHARACTER_SET_RESULTS=utf8'); 
	
	$base_rows = $mysqli->query($sql); 
	$i=0;
	while($row = $base_rows->fetch_row()){
		$temp[0][$i] = $row[0];
		$temp[1][$i] = $row[1];
		$temp[2][$i] = $row[2];
		$temp[3][$i] = $row[3];
		$temp[4][$i] = $row[4];
		$temp[5][$i] = $row[5];
		$i++;
	}
	for($j=0;$j<$i;$j++){
		if(strlen($temp[5][$j])==0){
			$temp[5][$j]="NULL";
			$temp[6][$j]=0;
		} else{
			$Hi = strtotime(date("H:i",strtotime($temp[5][$j])));
			$tempHi = strtotime("17:30");
			$calHi = $Hi-$tempHi;//假如刷卡
			
			if($calHi>0){
				$temp[6][$j] = strtotime($temp[5][$j]) - strtotime($temp[4][$j])-7200;
			}else{
				$temp[6][$j] = strtotime($temp[5][$j]) - strtotime($temp[4][$j]);
			}
		}
		
		// echo $j." ".$temp[4][$j]." ".$temp[5][$j]." ".$temp[6][$j]." <br>";
		// echo $j." ".$temp[6][$j]." <br>";
	}
	// echo date("H:i",strtotime($temp[5][20]))."<br>";
	// echo strtotime($temp[5][20]) - strtotime($temp[4][20]);
	for($j=0;$j<$i;$j++){//TODO
		$temp[7][$j] = date('Y-m-d',strtotime($temp[4][$j]));
		$temp[8][$j] = date('Y-m-d',strtotime($temp[5][$j]));
	}
	// echo strtotime(0)."<br>";
	for($j=0;$j<$i;$j++){
		if(strlen($temp[5][$j])>0){
			// $temp[6][$j]
		}
		// echo strtotime(0)."<br>";
		// echo $j." ".$temp[4][$j]." ".$temp[5][$j]." <br>";
		// echo $j." ".$temp[7][$j]." ".$temp[8][$j]."<br>";
	}
	$contiDay = 0;
	$sumHour = 0;
	$flag = 0;
	// $Date={1,2,3,4};
	// for($j=0;$j<4;$j++){
			// if(($j+1)==$i){
				// $arr[$flag][0] = $temp[1][$j];
				// $arr[$flag][1] = $temp[2][$j];
				// $arr[$flag][2] = $temp[3][$j];
				// $arr[$flag][3] = $contiDay;	
				// $Hour = $sumHour + $temp[6][$j];	
				// $front = floor($Hour/3600);
				// $surplus = $Hour/3600 - $front;
				// if ($surplus < 0.25) {
					// $surplus = 0;
				// } else if ($surplus > 0.25 && $surplus < 0.5) {
					// $surplus = 0.25;
				// } else if ($surplus>=0.5 && $surplus < 0.75) {
					// $surplus = 0.5;
				// }else if($surplus >=0.75 && $surplus < 1 ){
					// $surplus = 0.75;
				// }
				// $arr[$flag][4] = $front+ $surplus;
				// return;
			// }else if($temp[1][$j]==$temp[1][$j+1]){//假如第一行與第二行相等
				// if($temp[6][$j]!=0){
					// $contiDay++;
					// $sumHour = $sumHour + $temp[6][$j];
				// }else{
					// $contiDay =0;
					// $sumHour = 0;
				// }
				// echo $sumHour."<br>";
			// }else if($temp[1][$j]!=$temp[1][$j+1]){
				// $arr[$flag][0] = $temp[1][$j];
				// $arr[$flag][1] = $temp[2][$j];
				// $arr[$flag][2] = $temp[3][$j];
				// $arr[$flag][3] = $contiDay;	
				// $front = floor($sumHour/3600);
				// $surplus = $sumHour/3600 - $front;
				// if ($surplus < 0.25) {
					// $surplus = 0;
				// } else if ($surplus > 0.25 && $surplus < 0.5) {
					// $surplus = 0.25;
				// } else if ($surplus>=0.5 && $surplus < 0.75) {
					// $surplus = 0.5;
				// }else if($surplus >=0.75 && $surplus < 1 ){
					// $surplus = 0.75;
				// }
				// $arr[$flag][4] = $front+ $surplus;
				// $sumHour = 0;
				// $contiDay = 0;
				// $flag++;
			// }
	// }
	
	
	for($j=0;$j<4;$j++){
		if($temp[6][$j]!=0){
			
			if($temp[1][$j]==$temp[1][$j+1]){
				
			}else if($temp[1][$j]!=$temp[1][$j+1]){
				
			}
		}else{
			$contiDay =0;
			$sumHour = 0;
		}
		
		
	}
	
	
	
	// for($k=0;$k<$x;$k++){
		// for($j=0;$j<5;$j++){
			// if($j==4)
				// echo $arr[$j][$k]."<br>";
			// else
				// echo $arr[$j][$k]."\t";
		// }
	// }
	
?>
	
</body>
</html>