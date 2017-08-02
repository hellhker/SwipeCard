<?php
	include("mysql_config.php");
	$date = date('Y-m-d');
	
	$ORACLE_LOGIN="SCBG_BI";
	$ORACLE_PASSWORD="SCBG_BIPWD";
	$ORACLE_HOST="fldpdb02-vip.foxlink.com.tw:1589/CUFOX";
	$conn = oci_new_connect($ORACLE_LOGIN,$ORACLE_PASSWORD,$ORACLE_HOST,'AL32UTF8');
	
	
	$sql = "select RC_NO, PRIMARY_ITEM_NO,STD_MAN_POWER,PROD_LINE_CODE,to_char(creation_date,'YYYY-MM-DD HH24:MI:SS') " . "from APPS.FL_RC_LINES_V " . "where creation_date >= (to_date('$date','YYYY-MM-DD')-10) order by creation_date"; // SQL语句
	// $sql = "select RC_NO, PRIMARY_ITEM_NO,STD_MAN_POWER,PROD_LINE_CODE,to_char(creation_date,'YYYY-MM-DD HH24:MI:SS') " . "from APPS.FL_RC_LINES_V " . "where creation_date >= (to_date('$date','YYYY-MM-DD')) order by creation_date"; // SQL语句
	$result_RC_document_number = oci_parse($conn,$sql);
	oci_execute($result_RC_document_number, OCI_DEFAULT);//缺省模式;
		
	while($row = oci_fetch_array($result_RC_document_number, OCI_BOTH)) {
		$a1[] = $row[0];
		$a2[$row[0]] = $row[1];
		$a3[$row[0]] = $row[2];
		$a4[$row[0]] = $row[3];
		$a5[$row[0]] = $row[4];
	}
	oci_free_statement($result_RC_document_number);
	oci_close($conn);
	
	// var_dump($a2);
	// echo $sql;
	// exit;
	// date_format()
	
	
	// $sql = "select RC_NO, PRIMARY_ITEM_NO,STD_MAN_POWER,PROD_LINE_CODE,CUR_DATE from testrcline where CUR_DATE >= date_sub('$date',interval 10 day) order by CUR_DATE";
	// $mysqltest_row = $mysqli->query($sql);
	// while($row = $mysqltest_row->fetch_row()){
		 // $a1[] = $row[0];
		// $a2[$row[0]] = $row[1];
		// $a3[$row[0]] = $row[2];
		// $a4[$row[0]] = $row[3];
		// $a5[$row[0]] = $row[4];
	// }
	// echo $sql."<br>";
	// echo 
	// var_dump($a1);
	// exit;
	// CUR_DATE > date_sub(curdate(),interval 10 day)
	$sql = "select RC_NO, PRIMARY_ITEM_NO,STD_MAN_POWER,PROD_LINE_CODE,CUR_DATE from testrcline where CUR_DATE >= date_sub('$date',interval 10 day) order by CUR_DATE";
	// $sql = "select RC_NO, PRIMARY_ITEM_NO,STD_MAN_POWER,PROD_LINE_CODE,CUR_DATE from testrcline where CUR_DATE >= '$date' order by CUR_DATE";
	$mysql_row = $mysqli->query($sql);
	while($row = $mysql_row->fetch_row()){
		$b1[] = $row[0];
		$b2[$row[0]] = $row[1];
		$b3[$row[0]] = $row[2];
		$b4[$row[0]] = $row[3];
		$b5[$row[0]] = $row[4];
	}
	mysqli_free_result($mysql_row);
	// var_dump($b2);
	// exit;
	$i=0;
	$j=0;
	echo "counta: ".count($a1)."<br>";
	echo "countb: ".count($b1)."<br>";
	echo date("Y-m-d H:i:s")."<br>";
	foreach ($a1 as $k => $v) {
		if(in_array($v,$b1,TRUE)){
			if ($a2[$v] != $b2[$v] || $a3[$v] != $b3[$v] || $a4[$v] != $b4[$v] || $a5[$v] != $b5[$v]) {
				$update_sql = "update testrcline set PRIMARY_ITEM_NO='$a2[$v]', STD_MAN_POWER='$a3[$v]'  , PROD_LINE_CODE='$a4[$v]' , CUR_DATE='$a5[$v]' where RC_NO = '$v'";
				$i++;
				$update_rows = $mysqli->query($update_sql);
				// break 1;
				// echo $a2[$v]." ".$b2[$v1];
				// echo $a2[$v]." - ".$b2[$v]."<br>";
				// echo $a3[$v]." - ".$b3[$v]."<br>";
				// echo $a4[$v]." - ".$b4[$v]."<br>";
				// echo $a5[$v]." - ".$b5[$v]."<br>";
				// echo $update_sql."<br>";
				// break ;
			}else{
				
			}
		}else{
			$insert_sql = "insert into testrcline (RC_NO, PRIMARY_ITEM_NO, STD_MAN_POWER, PROD_LINE_CODE,CUR_DATE) values('$v','$a2[$v]','$a3[$v]','$a4[$v]','$a5[$v]')";
			$insert_rows =$mysqli->query($insert_sql);
			$j++;
			// break 1;
			// echo $insert_sql."<br>";
		}
		// echo $j
		// echo $i."<br>";
	}
	
	echo $i."<br>";
	echo $j."<br>";
	echo date("Y-m-d H:i:s")."<br>";
	echo "Success!";
?>

 <script type="text/javascript">setTimeout("window.opener = null;window.open('','_self');window.close();",2000)</script>