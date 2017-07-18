<?

//require_once('../SFC/cutycapt.php');
require_once('../SFC/includes/PHPMailer/class.phpmailer.php');
require_once '../backupcode/getExcel.php';

$mysqli = mysqli_connect('192.168.65.230','root','foxlink','swipecard');
mysqli_set_charset($mysqli,'utf8');
$sqlLose ="SELECT 	
		SwipeDate,WorkShopNo,LineNo,CardID,ID,Name
		from 
		lose_employee where State='0'";
$query = mysqli_query($mysqli,$sqlLose);
//TODO 以後要調整成隨部門和時間
$i=0;
$swipeDate=array();
$shopNo=array();
$lineno=array();
$cardID=array();
$id=array();
$name=array();

$countLose = mysqli_num_rows($query);
if(mysqli_num_rows($query)>0){
	while($row = mysqli_fetch_row($query)){
		
		$swipeDate[$i] =  $row[0];
		$shopNo[$i] = $row[1];
		$lineno[$i]= $row[2];
		$cardID[$i]=  $row[3];
		$id[$i]= $row[4];
		$name[$i]= $row[5];
		$i++;
		
	}	
}
$dataLose=array($swipeDate,$shopNo,$lineno,$cardID,$id,$name);
mysqli_free_result($query); 


$sqlReason ="SELECT 	
		ID,Name,overtimeDate,overtimeInterval,overtimeHours,overtimeType,Direct,Reason,LineNo,RC_NO,calHours
		from 
		employee_reason where State='0'";
$query = mysqli_query($mysqli,$sqlReason);
//TODO 以後要調整成隨部門和時間
$i=0;
$id=array();
$name=array();
$overtimeDate=array();
$overtimeInterval=array();
$overtimeHours=array();
$overtimeType=array();
$direct=array();//
$reason=array();
$lineno=array();
$rc_no=array();
$calHours=array();
$countReason = mysqli_num_rows($query);
if(mysqli_num_rows($query)>0){
	while($row = mysqli_fetch_row($query)){
		
		$id[$i] =  $row[0];
		$name[$i] = $row[1];
		$overtimeDate[$i]= $row[2];
		$overtimeInterval[$i]=  $row[3];
		$overtimeHours[$i]= $row[4];
		$overtimeType[$i]= $row[5];
		$direct[$i]= $row[6];
		$reason[$i]=$row[7];
		$lineno[$i]=$row[8];
		$rc_no[$i]=$row[9];
		$calHours[$i]=$row[10];
		$i++;
		
	}	
}
// var_dump($overtimeInterval);
// var_dump($overtimeHours);
// var_dump($overtimeType);
// var_dump($direct);
// var_dump($reason);

$dataReason=array($id,$name,$lineno,$rc_no,$overtimeDate,$overtimeInterval,$calHours,$overtimeHours,$overtimeType,$direct,$reason);
mysqli_free_result($query); 
if($countReason >0){
	$update_sql_reason = "update employee_reason set State='1' where State='0'";
	$update_rows_reason = $mysqli->query($update_sql_reason);
	// $update_rows = mysqli_query($mysqli,$update_sql);
	//mysqli_free_result($update_rows_reason); 
}


if($countLose >0){
	$update_sql_lose = "update lose_employee set State='1' where State='0'";
	$update_rows_lose = $mysqli->query($update_sql_lose);
	// $update_rows = mysqli_query($mysqli,$update_sql);
	//mysqli_free_result($update_rows_lose); 
}

$mysqli->close();

// var_dump($data);
//$project = "N71 E75";
$cur_date = Date("Y-m-d");
$cur_time = Date("H:i:s");

if($cur_time>="07:40:00" && $cur_time<"20:00:00") //執行時間為日班的話，抓取的報表應該為前一日夜班
{
    $shift = "夜班";
    $report_date = date("Y-m-d", strtotime("-1 day", time()));
}
else
{
    $shift = "日班";
    $report_date = $cur_date;
}


if($countReason>0||$countLose>0){//如果已經發送過的郵件就不用再次發送了
	$email = new PHPMailer();
	$email->From      = 'Paul_Qin@foxlink.com.tw';
	// $email->FromName  = '加班人員異常工時修改&刷卡人員丟失異常';
	// $email->Subject   = $report_date.' '.$project.' '.$shift.' 工時異常&刷卡人員丟失異常郵件';
	$title_Reason="加班人員工時修改異常";
	$title_Lose="刷卡人員缺失異常";
	if($countReason>0){
		$title = $title_Reason;
		if($countLose>0){
			$title = $title."&".$title_Lose;
		}
	}else{
		$title=$title_Lose;
	}
	$email->FromName  = $title;
	// $email->Subject   = $report_date.' '.$project.' 工時異常&刷卡人員缺失異常郵件';
	// Dear  All

// 以下信息請知悉，謝謝！

// Warning_ChangeHours_時間.xls
// 此檔案為助理修改工時明細，請確認信息的正確性！

// Warning_EmployeeLose_時間.xls
// 此檔案為新進人員或補辦廠牌無人員資料，系統暫無信息，由線長手動輸入相關信息，請確認信息的正確性！


// (1) 人員加班工時經助理進行過修改，請單位主管參考附件Warning_ChangeHours表單，並確認加班工時資料是否無誤!

// (2) 新進人員或補辦廠牌無人事資料，需助理申請人事簽核流程，將其卡號與人事資料綁定，人員清單請參考附件Warning_EmployeeLose表單!
	$email->Subject   = $report_date.' '.$project.' '.$title;
	// $message_Reason = "以下人員加班工時經助理進行過修改，請確認修改後資料是否無誤!\n"
					// . "excel格式為Warning_ChangeHours_時間.xls\n";
	// $message_Lose = "以下人員為新進人員或補辦廠牌無人員資料，為線長手動輸入，請確認修改後資料是否無誤!\n"
					// . "excel格式為Warning_EmployeeLose_時間.xls\n";
					
	// $message_Reason = "Warning_ChangeHours_時間.xls\n"
					// . "此檔案為助理修改工時明細，請確認信息的正確性！\n\n";
	// $message_Lose = "Warning_EmployeeLose_時間.xls\n"
					// . "此檔案為新進人員或補辦廠牌無人員資料，系統暫無信息，由線長手動輸入相關信息，請確認信息的正確性！";
					
	$message_Reason = "人員加班工時經助理進行過修改，請單位主管參考附件Warning_ChangeHours表單，並確認加班工時資料是否無誤!\n\n";
	$message_Lose ="新進人員或補辦廠牌無人事資料，需助理申請人事簽核流程，將其卡號與人事資料綁定，人員清單請參考附件Warning_EmployeeLose表單!";
	// $message = "您好!\n"
				// ."以下人員加班工時經助理進行過修改，請確認修改後資料是否無誤!\n"
				// ."詳情見附件！\n\n\n"
				// ."Contact Us\n系統整合課\n姓名:蒲秦川\n分機:33461\nE-mail: Paul_Qin@foxlink.com";
				
	$message = "您好: \n\n"
				."以下信息請知悉，謝謝！\n\n"
				.$message_Reason
				.$message_Lose
				."詳情見附件！\n\n\n"
				."Contact Us\n系統整合課\n姓名:蒲秦川\n分機:32910\nE-mail: Paul_Qin@foxlink.com";
				
	$email->Body      = $message;

	
	$email->AddAddress("Paul_Qin@foxlink.com.tw");
	$email->AddAddress("Minjing_Zou@foxlink.com.tw");
	$email->AddAddress("Denil_Chuang@foxlink.com.tw");
	$email->AddAddress("Vic_Pan@foxlink.com.tw");
	$email->AddAddress("Xiaocui_Yan@foxlink.com.tw");
	$email->AddAddress("Canny_Du@foxlink.com.tw");
	$email->AddAddress("Lauren_Juan@foxlink.com.tw");
	
		
	$email->CharSet="UTF-8";
	//---------  ---------- "../sfc/excel/"
	if($countReason>0){
		$fileName = "Warning_ChangeHours";
		$headArr = array("人員ID","人員姓名","線別","指示單號","加班時間","加班時段","系統計算小時","修改後加班小時","加班類型","直間接人員","修改原因");
		// $data = array(array(1,2,3),array(1,3,5),array(5,7,9));
		// getExcel($fileName,$headArr,$data);
		$excel_name =  getExcel($fileName,$headArr,$dataReason);
		$file_to_attach=$excel_name;
		$email->AddAttachment($file_to_attach);
	}
	
	if($countLose>0)
	$fileName = "Warning_EmployeeLose";
	$headArr = array("刷卡日期","工作車間","線別","卡ID","員工工號","姓名");
	// $data = array(array(1,2,3),array(1,3,5),array(5,7,9));
	// getExcel($fileName,$headArr,$data);
	$excel_name =  getExcel($fileName,$headArr,$dataLose);
	$file_to_attach=$excel_name;
	$email->AddAttachment($file_to_attach);

	//擷取HMI Official Report畫面圖檔-------------
	// $imgname = 'Worktime_Warning.png';
	// screenshot('http://localhost:8888/AddDemo/Compute_Hours_Warning.jsp',1200,4800,$imgname);
	// sleep(30);
	// $file_to_attach = '../sfc/img1/'.$imgname;

	// $email->AddAttachment($file_to_attach);
	

	$email->Send();
	echo $report_date." ".$project." ".$shift."Official Report發送郵件成功!!";
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="javascript">setTimeout("window.opener = window.open('','_parent',''); window.close();" ,730000)</script>
</head>
<body></body>
</html>
