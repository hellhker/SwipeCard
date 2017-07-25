<?php
	include("mysql_config.php");

	$ORACLE_LOGIN="SCBG_BI";
	$ORACLE_PASSWORD="SCBG_BIPWD";
	$ORACLE_HOST="fldpdb02-vip.foxlink.com.tw:1589/CUFOX";
	$conn = oci_new_connect($ORACLE_LOGIN,$ORACLE_PASSWORD,$ORACLE_HOST,'AL32UTF8');
	
	$date = date('Y-m-d');
	// echo $date;
	// exit;
	$sql = "select RC_NO, PRIMARY_ITEM_NO,STD_MAN_POWER,PROD_LINE_CODE,to_char(creation_date,'YYYY-MM-DD HH24:MI:SS') " . "from APPS.FL_RC_LINES_V " . "where creation_date >= (to_date('$date','YYYY-MM-DD')-10) order by creation_date"; // SQL语句
	// echo $sql;
	// exit;
	$result_RC_document_number = oci_parse($conn,$sql);
	oci_execute($result_RC_document_number, OCI_DEFAULT);//缺省模式;
		
	while($row = oci_fetch_array($result_RC_document_number, OCI_BOTH)) {
		$a1[] = $row[0];
		$a2[$row[0]] = $row[1];
		$a3[$row[0]] = $row[2];
		$a4[$row[0]] = $row[3];
		$a5[$row[0]] = $row[4];
	}
	// var_dump($a5);
	// exit;
	// date_format()
	
	// CUR_DATE > date_sub(curdate(),interval 10 day)
	$sql = "select RC_NO, PRIMARY_ITEM_NO,STD_MAN_POWER,PROD_LINE_CODE,CUR_DATE from testrcline1 where CUR_DATE >= date_sub('$date',interval 10 day) order by CUR_DATE";
	$mysql_row = $mysqli->query($sql);
	// echo $interval_sql.'<br>';
	while($row = $mysql_row->fetch_assoc()){
		$b1[] = $row[0];
		$b2[$row[0]] = $row[1];
		$b3[$row[0]] = $row[2];
		$b4[$row[0]] = $row[3];
		$b5[$row[0]] = $row[4];
	}
	mysqli_free_result($mysql_row);
	// var_dump($a1);
	// exit;
	$i=0;
	$j=0;
	foreach ($a1 as $k => $v) {
		foreach ($b1 as $k1 => $v1) {
			// $i++;
			if ($v == $v1) {
				if ($a2[$v] != $b2[$v1] || $a3[$v] != $b3[$v1] || $a4[$v] != $b4[$v1] || $a5[$v] != $b5[$v1]) {
					$update_sql = "update testrcline1 set PRIMARY_ITEM_NO='$a2[$v]' and STD_MAN_POWER='$a3[$v]'  and PROD_LINE_CODE='$a4[$v]' and CUR_DATE='$a5[$v]' where RC_NO = '$v'";
					// echo $update_sql."<br>";
					$update_rows = $mysqli->query($update_sql);
					// $i++;
					break 1;
				}
			} else if() {
				$insert_sql = "insert into testrcline1 (RC_NO, PRIMARY_ITEM_NO, STD_MAN_POWER, PROD_LINE_CODE,CUR_DATE) values('$v','$a2[$v]','$a3[$v]','$a4[$v]','$a5[$v]')";
				// echo $insert_sql."<br>";
				$insert_rows =$mysqli->query($insert_sql);
				// $i++;
				break 1;
			}
			
		}
		// echo $j
		// echo $i."<br>";
	}
	echo "Success!";
?>