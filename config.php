<?php 

// date_default_timezone_set('PRC');
// include("mysql_config.php");
// $time_inteval_setting = "select * from interval_setting where WorkshopNo='第四車間' and weekend = 0 and Shift = 'D'";
	// $setting_rows = $mysqli->query($time_inteval_setting);
	// while($row1= $setting_rows->fetch_assoc()){
		// $setting[] = $row1['d_interval1'];
		// $setting[] = $row1['d_interval2'];
		// $setting[] = $row1['d_interval3'];
		// $setting[] = $row1['d_interval4'];
		// $setting[] = $row1['d_interval5'];
		
	// }
	
	
	function getTime($stime,$edtime,$date,$setting,$shift){
		$minus = 0;
		$length =count($setting);
		// $stime = date_format($stime,"Y-m-d H:i:s");
		// $edtime = date_format($edtime,"Y-m-d H:i:s");
		// echo $date."<br>";
		$date = date_create($date);
		// echo $stime."<br>";
		// echo $edtime."<br>";
		for($j=0;$j<count($setting);$j++){
		// for($j=0;$j<1;$j++){
			// $date = date_create($date);
			if($shift=="D"){
				if($j<$length-1){
					$tempInterval = split("-",$setting[$j]);
					$tempHour1 = split(":",$tempInterval[0]);
					$tempHour2 = split(":",$tempInterval[1]);
				}else{
					$tempHour1 = split(":",$setting[$j]);
				}
			}else if($shift=="N"){
				$tempInterval = split("-",$setting[$j]);
				$tempHour1 = split(":",$tempInterval[0]);
				$tempHour2 = split(":",$tempInterval[1]);
			}
			
			
			if($shift=="D"){
				$tempTime1 = $date;
				$tempTime1->setTime($tempHour1[0],$tempHour1[1]);
				$tempTime1 = date_format($tempTime1,"Y/m/d H:i:s");
				
				$tempTime2 = $edtime;
				$tempTime2 = date_create($tempTime2);
				if($j<($length-1)){
					$tempTime2->setTime($tempHour2[0],$tempHour2[1]);
				}
				$tempTime2 = date_format($tempTime2,"Y/m/d H:i:s");
				
				// echo $tempTime1." ".$tempTime2."<br>";
			}else if($shift=="N"){
				$tempTime1 = $date;
				if($tempHour1[0]>0&&$tempHour1[0]<12){
					$tempTime1 = date_format($tempTime1,"Y/m/d H:i:s");
					$tempTime1 = strtotime('+1 days',strtotime($tempTime1));
					$tempTime1=date_create(date("Y-m-d H:i:s",$tempTime1));
					
					date_time_set($tempTime1,$tempHour1[0],$tempHour1[1]);
				}else{
					// $tempTime1->setTime($tempHour1[0],$tempHour1[1]);
					date_time_set($tempTime1,$tempHour1[0],$tempHour1[1]);
				}
				$tempTime1 = date_format($tempTime1,"Y/m/d H:i:s");
				// echo $tempTime1."<br>";
				$tempTime2 = $date;
				if($tempHour2[0] >= 0 && $tempHour2[0]<12){
					$tempTime2 = date_format($tempTime2,"Y/m/d H:i:s");
					$tempTime2 = strtotime('+1 days',strtotime($tempTime2));
					$tempTime2=date_create(date("Y-m-d H:i:s",$tempTime2));
					
					date_time_set($tempTime2,$tempHour2[0],$tempHour2[1]);
				}else{
					date_time_set($tempTime2,$tempHour2[0],$tempHour2[1]);
					// $tempTime2->setTime($tempHour2[0],$tempHour2[1]);
				}
				// echo $tempTime3."<br>";
				
				$tempTime2 = date_format($tempTime2,"Y/m/d H:i:s");
				// echo $tempTime2."<br>";
			}
			
			$calStart = strtotime($stime)-strtotime($tempTime1);
			$calEnd   = strtotime($edtime)-strtotime($tempTime2);
			if($calEnd>0){
				$tempEnd = $tempTime2;//09:30
				if($calStart>0){
					$tempStart = $stime;//08:30
				}else{
					$tempStart = $tempTime1;//07:40
				}
			}else{
				$tempStart = $tempTime1;//09:40
				$tempEnd = $edtime;//10:30
				$minus += strtotime($tempEnd) - strtotime($tempStart);
				// echo $tempStart." ".$tempEnd."<br.";
				break;
			}
			// echo $tempStart." ".$tempEnd."<br.";
			$minus += strtotime($tempEnd) - strtotime($tempStart);
		}
		return $minus;
	}
	// $stime = date_create("2017-07-21 07:31:59");
	// $etime = date_create("2017-07-21 19:31:59");
	// $date = date('2017-07-21');
	// $shift="D";
	// $ee = getTime($stime,$etime,$date,$setting,$shift);
	// echo $ee;

	function getWeekend($SDate){
		$w=date('w',strtotime($SDate));
		if($w==6){
			$weekend = 1;
		}else{
			$weekend = 0;//TODO
		}
		return $weekend;
	}
	
	
