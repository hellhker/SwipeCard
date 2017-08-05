<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>工時預警</title>
</head>
<body>
<?

	require_once('PHPMailer/class.phpmailer.php');
	require_once 'getExcel.php';
	include("mysql_config.php");
	include("config.php");
	$date = date('Y/m/d');
	// $date = date('2017/07/30');
	// $date1 = strtotime('-30 days',strtotime($date));
	$date = strtotime('-1 days',strtotime($date));
	// $date1 = strtotime('-30 days',strtotime($date));
	$date = date("Y/m/d",$date);
	// echo $date;
		
	$email_sql="select costid_arr,email from user_data where email is not null ";

	$email_rows = $mysqli->query($email_sql);
	while($row= $email_rows->fetch_assoc()){
		$vip_lv[$row['costid_arr']] = $row['costid_arr'];
		$emails[$row['costid_arr']][] = $row['email'];

	}
	// var_dump($emails['6251']);
	// exit;
	mysqli_free_result($email_rows);
	echo date("Y-m-d H:i:s")."<br>";
	$row1_sql = "SELECT a.prod_line_code, a.cardid, a.name, a.swipecardtime, a.swipecardtime2
			, a.shift, a.WorkshopNo, b.id, b.depname, b.depid,b.costid
		FROM testswipecardtime a INNER JOIN testemployee b ON a.cardid = b.cardid
		WHERE a.swipecardtime >= date_sub('2017-07-30', INTERVAL 30 DAY)
			AND date_format(a.swipecardtime, '%Y/%m/%d') <= '2017/07/30'
			AND a.swipecardtime2 IS NOT NULL
			and b.costid in(6251,6252,9628,9629)
			order by a.cardid,a.swipecardtime desc
			";
		//and b.depid = 'XR-52'
	$test_rows = $mysqli->query($row1_sql);
	while($row= $test_rows->fetch_assoc()){
		$temp2[$row['id']][0][] = $row['depid'];
		$temp2[$row['id']][1][] = $row['depname'];
		$temp2[$row['id']][2][] = $row['id'];
		
		$temp2[$row['id']][3][] = $row['name'];
		$temp2[$row['id']][4][] = $row['swipecardtime'];
		$temp2[$row['id']][5][] = $row['swipecardtime2'];
		$temp2[$row['id']][6][] = $row['shift'];
		$temp2[$row['id']][7][] = date('Y/m/d',strtotime($row['swipecardtime']));
		$temp2[$row['id']][8][] = $row['WorkshopNo'];
		$temp2[$row['id']]['costid'][]  = $row['costid'];

	}
	mysqli_free_result($test_rows);
	echo date("Y-m-d H:i:s")."<br>";
	
	
	$time_inteval_setting = "select * from interval_setting";
	
	
	$setting_rows = $mysqli->query($time_inteval_setting);
	while($row1= $setting_rows->fetch_assoc()){
		$setting[$row1['WorkshopNo']][$row1['weekend']][$row1['Shift']][] = $row1['d_interval1'];
		$setting[$row1['WorkshopNo']][$row1['weekend']][$row1['Shift']][] = $row1['d_interval2'];
		$setting[$row1['WorkshopNo']][$row1['weekend']][$row1['Shift']][] = $row1['d_interval3'];
		$setting[$row1['WorkshopNo']][$row1['weekend']][$row1['Shift']][] = $row1['d_interval4'];
		$setting[$row1['WorkshopNo']][$row1['weekend']][$row1['Shift']][] = $row1['d_interval5'];
		
	}
	mysqli_free_result($setting_rows);
	$con = count($temp1['cardid']);
	$conb = count($temp['cardid']);
	$k = 0;
	foreach($temp2 as $key => $value){
		// echo $key."<br>";
		$i=0;
		$flag=0;
		$temp4[$key]['cont_date']=0;
		$temp4[$key]['con_time']=0;
		// echo "key".$key."<Br>";
		$y =0;
		$y = 10;
		// var_dump($setting['第二車間'][0]['N']);
		foreach($value as $key1 => $value1){
			$sub = (strtotime($value[7][$i])-strtotime($value[7][$i+1]))/86400;
			// echo $value[8][0]."<br>";
			// echo $weekend."<br>";
			// echo $value[6][0]."<br>";
			if($value[6][$i]=='N'){
				$weekend = 0;
			}else{
				$weekend = getWeekend($value[7][$i]);
			}
			if($value[7][0]==$date){
				if($flag==0){
					$temp4[$key]['cont_date']++;
					
					$interval_setting = $setting[$value[8][$i]][$weekend][$value[6][$i]];
					$tempCal = getTime($value[4][$i],$value[5][$i],$value[7][$i],$interval_setting,$value[6][$i],$value[3][$i]);
					$tempCal = getNum($tempCal/3600);
					$temp4[$key]['cont_time'] += $tempCal;
					$y++;
					$flag=1;
				}
			}else{
				$temp4[$key]['cont_date']=0;
				$temp4[$key]['cont_time']= 0;
				break 1;
			}
			
			if($sub==1){
				$temp4[$key]['cont_date']++;
				
				// $weekend = getWeekend($value[7][$i]);
				$interval_setting = $setting[$value[8][$i]][$weekend][$value[6][$i]];
				$tempCal = getTime($value[4][$i],$value[5][$i],$value[7][$i],$interval_setting,$value[6][$i],$value[3][$i]);
				$tempCal = getNum($tempCal/3600);
				$x++;
				// echo $x."_".$tempCal."<br>";
				$temp4[$key]['cont_time'] += $tempCal;
			}else{
				break 1;
			}
			$i++;
			
		}
		$cid = $value['costid'][0];
		$temp3[$cid][$key]['depid'] = $value[0][0];
		$temp3[$cid][$key]['depname'] = $value[1][0];
		$temp3[$cid][$key]['id'] = $value[2][0];
		$temp3[$cid][$key]['name'] = $value[3][0];
		$temp3[$cid][$key]['cont_date'] = $temp4[$key]['cont_date'];
		$temp3[$cid][$key]['cont_time'] = $temp4[$key]['cont_time'];
		$temp3[$cid][$key]['date_interval'] = $value[7][$i]." - ".$value[7][0];
	}

	// var_dump($temp3);
	// exit;
	foreach($temp3 as $cid => $val){
		// echo $cid."<br>";
		foreach($val as $key => $value){
			if($value['cont_time']>=45||$value['cont_date']>=5){
				$depid[] = $value['depid'];
				$depname[] = $value['depname'];
				$id[] = $value['id'];
				$name[] = $value['name'];
				$cont_date[] = $value['cont_date'];
				$cont_time[] = $value['cont_time'];
				$date_interval[] = $value['date_interval'];
			}
		}
	
	
		$data=array($depid,$depname,$id,$name,$cont_date,$cont_time,$date_interval);
		$tempC = count($data[0])."<br>";
		// var_dump($data);
		if($tempC>0){
			$cur_date = Date("Y-m-d");
			$cur_time = Date("H:i:s");
			
			$email = new PHPMailer();
			$email->From      = 'Paul_Qin@foxlink.com.tw';
			$email->FromName  = '工時預警';
			$email->Subject   = $cur_date.' '.$cid.' 工時預警郵件';
			$message = "您好!\n"
						."以下人員已連續上班5天/工時已達45H\n"
						."詳情見附件！\n\n\n";
			$email->Body      = $message;



			foreach($emails[$cid] as $key => $value){
				$email->AddAddress($value);
				$email->AddAddress("Minjing_Zou@foxlink.com.tw");
				// $email->AddAddress("Paul_Qin@foxlink.com.tw");
				// echo $value."<br>";
			}
			
			$email->CharSet="UTF-8";
			//---------  ---------- "../sfc/excel/"
			$fileName = "Hours_Warning_".$cid;
			$headArr = array("部門ID","部門名稱","工號","姓名","連續天數","連續小時","工作日期段");
			$excel_name =  getExcel($fileName,$headArr,$data);
			$file_to_attach=$excel_name;
			$email->AddAttachment($file_to_attach);

			$email->Send();
			echo $cid." Official Report發送郵件成功!!"."<br>";
		}
		
		$temp= array();
		$temp1= array();
		$temp2= array();
		$temp3= array();
		$temp4= array();
		$setting= array();
		$data=array();
		$depid = array();
		$depname = array();
		$id = array();
		$name = array();
		$cont_date = array();
		$cont_time = array();
		$date_interval = array();
		
	}
	echo date("Y-m-d H:i:s")."<br>";
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">setTimeout("window.opener = window.open('','_parent',''); window.close();" ,2000)</script>
</head>
<body></body>
</html>