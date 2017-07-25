<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>工時預警</title>
<link href="css/bootstrap.css" rel="stylesheet">
</head>
<body>

<?php 
	
	// $MYSQL_LOGIN = "root";
	// $MYSQL_PASSWORD = "foxlink";
	// $MYSQL_HOST = "192.168.65.230";

	// $mysqli = new mysqli($MYSQL_HOST,$MYSQL_LOGIN,$MYSQL_PASSWORD,"swipecard");
	// $mysqli->query("SET NAMES 'utf8'");	 
	// $mysqli->query('SET CHARACTER_SET_CLIENT=utf8');
	// $mysqli->query('SET CHARACTER_SET_RESULTS=utf8'); 
	include("mysql_config.php");
	include("config.php");
	$date = date('2017/07/22');
	// $date1 = strtotime('-30 days',strtotime($date));
	$date = strtotime('-1 days',strtotime($date));
	// $date1 = strtotime('-30 days',strtotime($date));
	$date = date("Y/m/d",$date);
	// $date1 = date("Y/m/d",$date1);
	// echo $date1;
	// $sql = "select a.prod_line_code,b.id,b.name,b.depname,a.swipecardtime,a.swipecardtime2 from testswipecardtime a,testemployee b WHERE a.cardid=b.cardid and swipecardtime > date_sub(curdate(),interval 30 day) ";
	
	// $employee_sql = "select b.cardid,b.id,b.depname,b.depid from (select cardid from testswipecardtime group by cardid) a,testemployee b where a.cardid = b.cardid and b.cardid = '0087670656'";
	
	$employee_sql = "select b.cardid,b.id,b.depname,b.depid from (select cardid from testswipecardtime group by cardid) a,testemployee b where a.cardid = b.cardid and depid='XR-54' order by b.cardid";
	
	// $time_sql = "select prod_line_code,cardid,name,swipecardtime,swipecardtime2,shift,workshopno from testswipecardtime where swipecardtime > date_sub(curdate(),interval 30 day) and swipecardtime2 is not null order by cardid,swipecardtime desc";
	
	$time_sql = "select prod_line_code,cardid,name,swipecardtime,swipecardtime2,shift,WorkshopNo from testswipecardtime where swipecardtime >= date_sub('$date',interval 30 day) and date_format(swipecardtime,'%Y/%m/%d') <= '$date' and swipecardtime2 is not null order by cardid,swipecardtime desc";
	// echo $time_sql."<br>";
	
	
	
	// $time_sql = "select prod_line_code,cardid,name,swipecardtime,swipecardtime2,shift from testswipecardtime where swipecardtime > date_sub(curdate(),interval 30 day) and swipecardtime2 is not null  order by cardid,swipecardtime desc";
	
	// $time_inteval_setting = "select * from interval_setting where WorkshopNo='第八車間' and weekend = 0 and Shift = 'D'";
	// $interval_sql = "select * from interval_setting where WorkshopNo='$WorkshopNo' and weekend = '$weekend' and Shift = '$Shift'";
	$time_inteval_setting = "select * from interval_setting";
	// echo $time_inteval_setting;
	// exit;
	// $base_rows = $mysqli->query($sql); 
	// $i=0;
	
	
	$base_rows = $mysqli->query($employee_sql);
	while($row= $base_rows->fetch_assoc()){
		$temp['cardid'][] = $row['cardid'];
		$temp['id'][] = $row['id'];
		$temp['depid'][] = $row['depid'];
		$temp['depname'][] = $row['depname'];
		
	}
	
	
	$time_rows = $mysqli->query($time_sql);
	while($row= $time_rows->fetch_assoc()){
		$temp1['cardid'][] = $row['cardid'];
		$temp1['name'][] = $row['name'];
		$temp1['swipecardtime'][] = $row['swipecardtime'];
		$temp1['swipecardtime2'][] = $row['swipecardtime2'];
		$temp1['shift'][] = $row['shift'];
		$temp1['WorkshopNo'][] = $row['WorkshopNo'];
	}
	
	
	$setting_rows = $mysqli->query($time_inteval_setting);
	while($row1= $setting_rows->fetch_assoc()){
		$setting[$row1['WorkshopNo']][$row1['weekend']][$row1['Shift']][] = $row1['d_interval1'];
		$setting[$row1['WorkshopNo']][$row1['weekend']][$row1['Shift']][] = $row1['d_interval2'];
		$setting[$row1['WorkshopNo']][$row1['weekend']][$row1['Shift']][] = $row1['d_interval3'];
		$setting[$row1['WorkshopNo']][$row1['weekend']][$row1['Shift']][] = $row1['d_interval4'];
		$setting[$row1['WorkshopNo']][$row1['weekend']][$row1['Shift']][] = $row1['d_interval5'];
		
	}
	// var_dump($setting);
	// var_dump($setting['第八車間']['0']['D']);
	// exit;
	// 第八車間


	// $tempInterval = split("-",$setting[0]);
	// var_dump($setting);
	// exit;
	
	// var_dump($setting);
		// exit;
	// foreach($temp1 as )\
	$con = count($temp1['cardid']);
	$conb = count($temp['cardid']);
	$temp2 =array();
	// echo $con;
	$k = 0;
	for($i = 0;$i<$con; $i++){
		for($j=0;$j<$conb;$j++){
			$k++;
			if($temp1['cardid'][$i]==$temp['cardid'][$j]){
				// $temp2[0][$i] = $temp[2][$j];
				// $temp2[1][$i] = $temp[3][$j];
				// $temp2[2][$i] = $temp[1][$j];
				// $temp2[3][$i] = $temp1[1][$i];
				
				// $temp2[4][$i] = $temp1[2][$i];
				// $temp2[5][$i] = $temp1[3][$i];
				// $temp2[6][$i] = $temp1[4][$i];
				
				$temp2[$temp['id'][$j]][0][] = $temp['depid'][$j];
				$temp2[$temp['id'][$j]][1][] = $temp['depname'][$j];
				$temp2[$temp['id'][$j]][2][] = $temp['id'][$j];
				
				$temp2[$temp['id'][$j]][3][] = $temp1['name'][$i];
				$temp2[$temp['id'][$j]][4][] = $temp1['swipecardtime'][$i];
				$temp2[$temp['id'][$j]][5][] = $temp1['swipecardtime2'][$i];
				$temp2[$temp['id'][$j]][6][] = $temp1['shift'][$i];
				$temp2[$temp['id'][$j]][7][] = date('Y/m/d',strtotime($temp1['swipecardtime'][$i]));
				$temp2[$temp['id'][$j]][8][] = $temp1['WorkshopNo'][$i];
				// $temp2[$temp[1][$j]][8][] = date('Y-m-d',strtotime($temp1[3][$i]));
				// echo $i."<br>";
				// echo "k: ".$k."<br>";
				break 1;
			}
		}
		// echo "i: ".$i."<bR>";
	}
	// echo $k;
	// date('Y-m-d',strtotime($temp[4][$j]));
	
	// var_dump($temp2);
	// exit;
	// $i = count($temp2[]);
	// for($j=0;$j<$i;$j++){//TODO
		// $temp2[7][$j] = date('Y-m-d',strtotime($temp2[4][$j]));
		// $temp2[8][$j] = date('Y-m-d',strtotime($temp2[5][$j]));
	// }
	// $date = date_create(date('Y-m-d'));
	
	// date('Y-m-d');
	// date_sub($date,date_interval_create_from_date_string("1 days"));
	// $date = date('2017/07/22');
	// $date = strtotime('-1 days',strtotime($date));
	// $date = date("Y/m/d",$date);
	// echo $date;
	// exit;

	
	foreach($temp2 as $key => $value){
		// echo $key."<br>";
		$i=0;
		$flag=0;
		$temp4[$key]['cont_date']=0;
		$temp4[$key]['con_time']=0;
		// echo "key".$key."<Br>";
		foreach($value as $key1 => $value1){
			$sub = (strtotime($value[7][$i])-strtotime($value[7][$i+1]))/86400;
			// echo $value[7][0];
			if($value[7][0]==$date){
				if($flag==0){
					$temp4[$key]['cont_date']++;
					// echo $value[4][$i]."<br>";
					// echo $value[5][$i]."<br>";
					// echo $value[6][$i]."<br>";
					// echo $value[7][$i]."<br>";
					
					// $temp2[$temp[1][$j]][6][]
					// $tempCal = getTime($value[4][$i],$value[5][$i],$value[7][$i]);
					// echo "tempCal: ".$tempCal;
					// $value[4][$i]=date_create($value[4][$i]);
					// $value[5][$i]=date_create($value[5][$i]);
					$weekend = getWeekend($value[7][$i]);
					$interval_setting = $setting[$value[8][$i]][$weekend][$value[6][$i]];
					$tempCal = getTime($value[4][$i],$value[5][$i],$value[7][$i],$interval_setting,$value[6][$i],$value[3][$i]);
					$tempCal = getNum($tempCal/3600);
					$temp4[$key]['cont_time'] += $tempCal;
					$flag=1;
					// echo $value[8][$i]."<br>";
					// echo $weekend."<br>";
					// echo $value[6][$i]."<br>";
					// echo "123";
				}
			}else{
				$temp4[$key]['cont_date']=0;
				$temp4[$key]['cont_time']= 0;
				// echo "123";
				break 1;
			}
			
			if($sub==1){
				$temp4[$key]['cont_date']++;
				
				$weekend = getWeekend($value[7][$i]);
				$interval_setting = $setting[$value[8][$i]][$weekend][$value[6][$i]];
				// var_dump($interval_setting);
				
				// $value[8][$i]
				
				$tempCal = getTime($value[4][$i],$value[5][$i],$value[7][$i],$interval_setting,$value[6][$i],$value[3][$i]);
				$tempCal = getNum($tempCal/3600);
				$temp4[$key]['cont_time'] += $tempCal;
			}else{
				break 1;
			}
			// echo $value[4][$i]." ".$value[5][$i]." ".$tempCal."<br>";
			// echo $i."<br>";
			$i++;
			
			// var_dump($value[4][$i]);
		}
		$temp3[$key]['depid'] = $value[0][0];
		$temp3[$key]['depname'] = $value[1][0];
		$temp3[$key]['id'] = $value[2][0];
		$temp3[$key]['name'] = $value[3][0];
		$temp3[$key]['cont_date'] = $temp4[$key]['cont_date'];
		$temp3[$key]['cont_time'] = $temp4[$key]['cont_time'];
		$temp3[$key]['date_interval'] = $value[7][$i]." - ".$value[7][0];
		// $value[7][0]
		// echo $value[7][$i];
		// $temp3[$key][0][] = $temp[2][$j];
		// $temp3[$key][1][] = $temp[3][$j];
		// $temp3[$key][2][] = $temp[1][$j];
		
		// $temp3[$key][3][] = $temp1[1][$i];
		// $temp3[$key][4][] = $temp1[2][$i];
		// $temp3[$key][5][] = $temp1[3][$i];
		// $temp3[$key][6][] = $temp1[4][$i];
		// $temp3[$key][7][] = date('Y-m-d',strtotime($temp1[2][$i]));
		
		// $i=0;
		// $tempa =  count($value[0]);
		// for($i=0;$i<$tempa;$i++){
			// $temp2[$key][]
		// }
		
	}	
	 // var_dump($temp3);
	// exit;
	?>
	<div class="panel-body" style="border: 1px solid #e1e3e6;">
		<table class="table table-striped">
			<tr>
				<th>部門代碼</th>
				<th>部門</th>
				<th>工號</th>
				<th>名字</th>
				<th>连续天数</th>
				<th>连续工時</th>
				<th>工作日期</th>
			</tr>
			<?php 
				foreach($temp3 as $key=>$value){
					if($value['cont_time']>=45||$value['cont_date']>=5){
			?>
					<tr>
						<td><? echo $value['depid'] ?></td>
						<td><? echo $value['depname'] ?></td>
						<td><? echo $value['id'] ?></td>
						<td><? echo $value['name'] ?></td>
						<td><? echo $value['cont_date'] ?></td>
						<td><? echo $value['cont_time'] ?></td>
						<td><? echo $value['date_interval'] ?></td>
					</tr>
			<?
					}
				}
			
			?>
			
		</table>
	</div>
	
	
</body>
</html>