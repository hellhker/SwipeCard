<?php 
	session_start();
	$access = $_SESSION["permission"];
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


<title>SELECT Operation</title>
</head>
<body class="pace-done">
	<?php 
		
		//日期，費用代碼
		// $checkShift = $_POST['checkShift'];
		$SDate = $_POST['SDate'];
		
		// if($checkShift=="D"){
			// $shift = "3";
		// }else if($checkShift="N"){
			// $shift = "11";
		// }
		
		include("mysql_config.php");
		$costID = '6251';
		
		$empInfo_sql = "select depid,id,name from testemployee where direct='D' and costid = '$costID' and isOnWork = 0";
		$empInfo_rows= $mysqli1->query($empInfo_sql);
		while($row = mysqli_fetch_array($empInfo_rows,MYSQL_ASSOC)){
			// $empInfo[$row['depid']][] = $row['id'];
			$empInfo[$row['id']] = $row['depid'];
			$empInfo1[$row['id']] = $row['name'];
			// $empID[] = $row['id'];
		}
		// echo "<PRE>";
		// print_r($empInfo);
		// echo "</PRE>";
		
		$empShift_sql = "select * from emp_class where emp_date='$SDate' and (class_no = 3 or class_no= 11)  ";
		// echo $empShift_sql."<BR>";
		$empShift_rows= $mysqli1->query($empShift_sql);
		while($row = mysqli_fetch_array($empShift_rows,MYSQL_ASSOC)){
			
			if(($row['class_no'])==3){
				$tempShift="D";
			}else if(($row['class_no'])==11){
				$tempShift="N";
			}
			$empShift[$row['ID']] = array(
				'emp_date' => $row['emp_date'],
				'class_no' => $tempShift
			);
		}
		$tempCount = 0;
		foreach($empShift as $id =>$value){
			foreach($empInfo as $aid => $depid){
				if($id==$aid){
					$tempCount++;
					// $planEmp[$depid][$value['shift']]  = $tempCount;
					$planEmp[$depid][$value['class_no']][] = array(
						'id' => $aid
					);
					$allEmp[$depid][$value['class_no']][$aid] = array(
						'id' => $aid,
						'name' =>$empInfo1[$aid]
					);
					break;
				}
			}
		}
		
		$empSwipe_sql = "select id,shift,Date_format(swipecardtime, '%Y-%m-%d') as sDate from testswipecardtime where Date_format(swipecardtime, '%Y-%m-%d') = '$SDate' order by id";
		
		$empSwipe_rows= $mysqli->query($empSwipe_sql);
		while($row = mysqli_fetch_array($empSwipe_rows,MYSQL_ASSOC)){
			$empSwipe[$row['id']] = array(
				'shift' => $row['shift'],
				'sDate' => $row['sDate']
			);
		}
	
		$tempCount = 0;
		foreach($empSwipe as $id =>$value){
			foreach($empInfo as $aid => $depid){
				if($id==$aid){
					$actEmp[$depid][$value['shift']][] = array(
						'id' => $aid
					);
					$inEmp[$depid][$value['shift']][$aid] = $aid;
					break;
				}
			}
		}
		// foreach($actEmp['XR-26']['D'] as $key => $val){
			// echo $val['id'] ."<BR>";
		// }
		
		foreach($planEmp as $dep => $val1){
			foreach($val1 as $shift => $val2){
				$planEmp1[$dep][$shift]  = count($planEmp[$dep][$shift]);
				$sumPlanEmp[$shift] += count($planEmp[$dep][$shift]);
			}
		}
		
		foreach($actEmp as $dep => $val1){
			foreach($val1 as $shift => $val2){
				$actEmp1[$dep][$shift]  = count($actEmp[$dep][$shift]);
				$sumActEmp[$shift] += count($actEmp[$dep][$shift]);
			}
		}
		
		$aShift = array("D","N");
		
		
		$newEmp = array();
		$newEmp = $allEmp;
		
		$count1=0;
		foreach($allEmp as $dep => $val1){
			foreach($val1 as $shift => $val2){
				// echo $val2."<BR>";
				
				foreach($val2 as $id => $val3){
					if($id==$inEmp[$dep][$shift][$id]){
						 // echo $val3['id']."<BR>";
						 // echo $id."<BR>";
						 unset($newEmp[$dep][$shift][$id]);
						 // $count1++;
					}
					// echo $id."<BR>";
					// echo $val3['id']."<BR>";
					// echo $inEmp[$dep][$shift]['id'];
				}
			}
		}
		
		foreach($newEmp as $dep => $val1){
			foreach($val1 as $shift => $val2){
				if(count($val2)==0){
					unset($newEmp[$dep][$shift]);
				}
			}
		}
		
		// echo "<PRE>";
		// print_r($actEmp['XR-05']);
		// echo "</PRE>";
		// exit;
		function getCeils($flag,$cch1,$val2){
			if($flag==1){
				$cch1 .= "<tr>"
				  . "<th>".$val2['id']."</th>"
				  . "<th>".$val2['name']."</th>"
				  . "</tr>";
			}else{
				$cch1 .= "<th>".$val2['id']."</th>"
				  . "<th>".$val2['name']."</th>";
			}
			return $cch1;
		}
		
		function getShift($shift){
			if($shift== "D"){
				$tempshift = "日";
				
			}else if($shift=="N"){
				$tempshift = "夜";
			}
			return $tempshift;
		}		
		// $newEmp1['XR-52'] = $newEmp['XR-52'];
		
		// unset($newEmp);
		// $newEmp = $newEmp1;
		// echo "<PRE>";
		// print_r($newEmp);
		// echo "</PRE>";
		// exit;
		
	
	
	?>

	
	
	<div class="panel-body" style="border: 1px solid #e1e3e6;">
		<center><h2>各線人力明細</h2>
		<table border="1">
			<tr>
				<th>線  別</th>
				<th>班別</th>
				<th>組裝人數</th>
				<th>生產人數</th>
			</tr>
			<?php 
				foreach($planEmp1 as $depid => $val){
					$cch .= "<tr>"
						 . "<th rowspan=\"2\" width=\"300px\">$depid</th>";
					foreach($aShift as $key => $shift){
						if($shift == "D"){
							$shift1="日";
							$cch .= "<th>".$shift1."</th>"
							 . "<th>".$planEmp1[$depid][$shift]."</th>"
							 . "<th>".$actEmp1[$depid][$shift]."</th>"
							 ."</tr>";
						}else if($shift=="N"){
							$shift1="夜";
							$cch .= "<tr><th>".$shift1."</th>"
							 . "<th>".$planEmp1[$depid][$shift]."</th>"
							 . "<th>".$actEmp1[$depid][$shift]."</th>"
							 ."</tr>";
						}
					}
				}
				
				$cch .= "<tr>"
						 . "<th rowspan=\"2\" width=\"300px\">匯總</th>";
					foreach($aShift as $key => $shift){
						if($shift == "D"){
							$shift1="日";
							$cch .= "<th>".$shift1."</th>"
							 . "<th>".$sumPlanEmp[$shift]."</th>"
							 . "<th>".$sumActEmp[$shift]."</th>"
							 ."</tr>";
						}else if($shift=="N"){
							$shift1="夜";
							$cch .= "<tr><th>".$shift1."</th>"
							 . "<th>".$sumPlanEmp[$shift]."</th>"
							 . "<th>".$sumActEmp[$shift]."</th>"
							 ."</tr>";
						}
					}
				echo $cch;
			?>
		</table>
		
		<HR>
		
		<center><h2>缺席人員名單</h2></center>
		<table border="1">
			<tr>
				<th>線  別</th>
				<th>班別</th>
				<th>工號</th>
				<th>姓名</th>
			</tr>
			<?php 
				foreach($newEmp as $depid => $val){
					// print_r(count($newEmp[$depid]['D']));
					$allCount = count($newEmp[$depid]['D'])+count($newEmp[$depid]['N']);
					// echo $allCount."<BR>";
					$cch1 .= "<tr>"
						 . "<th rowspan=\"$allCount\" width=\"300px\">$depid</th>";
						 // foreach($)
					// $cch1 .= "</tr>";
					foreach($val as $shift => $val1){
						$flag=0;
						$shiftCount = count($newEmp[$depid][$shift]);
						$tempshift = getShift($shift);
						$cch1 .= "<th rowspan=\"".$shiftCount."\" width=\"300px\">".$tempshift."</th>";
						// echo count($val)."<BR>";
						if(count($val)==2){
							if($shift=='D'){
								foreach($val1 as $id =>$val2){
									// echo $flag."<BR>";
									if($flag>=1){
										$cch1 .= "<tr>"
											  . "<th>".$val2['id']."</th>"
											  . "<th>".$val2['name']."</th>"
											  . "</tr>";
									}else if($flag<=0){
										$cch1 .= "<th>".$val2['id']."</th>"
										  . "<th>".$val2['name']."</th>"
										  ."</tr>";
									}
									$flag++;
								}
								$cch1 .= "</tr>";
							}else if($shift == "N"){
								foreach($val1 as $id =>$val2){
									if($flag>=1){
										$cch1 .= "<tr>"
											  . "<th>".$val2['id']."</th>"
											  . "<th>".$val2['name']."</th>"
											  . "</tr>";
									}else if($flag<=0){
										$cch1 .= "<th>".$val2['id']."</th>"
										  . "<th>".$val2['name']."</th>"
										  ."</tr>";
									}
									$flag++;
									
								}
								$cch1 .= "</tr>";
							}
							
						}else if(count($val)==1){
							foreach($val1 as $id => $val2){
								if($flag>=1){
									$cch1 .= "<tr>"
										  . "<th>".$val2['id']."</th>"
										  . "<th>".$val2['name']."</th>"
										  . "</tr>";
								}else if($flag<=0){
									$cch1 .= "<th>".$val2['id']."</th>"
									  . "<th>".$val2['name']."</th>"
									  ."</tr>";
								}
								$flag++;
							}
							$cch1 .= "</tr>";
						}
					}
				}
				
				echo $cch1;
			?>
		</table>
		</center>
		
		
	</div>
</body>
</html>

