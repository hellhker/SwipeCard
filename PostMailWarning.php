<?

require_once('../SFC/cutycapt.php');
require_once('../SFC/includes/PHPMailer/class.phpmailer.php');
require_once '../backupcode/getExcel.php';

$mysqli = mysqli_connect('192.168.65.230','root','foxlink','swipecard');
mysqli_set_charset($mysqli,'utf8');
$sql ="SELECT b.depid, 
		       b.depname, 
		       a.prod_line_code, 
		       a.name, 
		       b.id, 
		       Sum(a.dif_second)                  STime, 
		       Date_format(a.swipecardtime, '%v') Weeks 
		FROM   (SELECT ( Time_to_sec(Date_format(swipecardtime2, '%H:%i:%s')) 
		                 - Time_to_sec( 
		                                Date_format( 
		                                swipecardtime 
		               , '%H:%i:%s')) ) /3600  AS dif_second, 
		               name, 
		               swipecardtime, 
		               prod_line_code,cardid
		        FROM   testswipecardtime 
		        WHERE  1) a, 
		       testemployee b 
		WHERE a.cardid=b.cardid and swipecardtime > date_sub(curdate(),interval 10 day)
		GROUP  BY Date_format(a.swipecardtime, '%v'), 
		          a.name";
$query = mysqli_query($mysqli,$sql);
//TODO 以後要調整成隨部門和時間
$i=0;

$depid=array();
$depname=array();
$lineno=array();
$id=array();
$name=array();
$calHour=array();
$weeks=array();//

$count = mysqli_num_rows($query);
if(mysqli_num_rows($query)>0){
	while($row = mysqli_fetch_row($query)){
		if($row[5]>=48){
			$depid[$i] =  $row[0];
			$depname[$i] = $row[1];
			$lineno[$i]= $row[2];
			$id[$i]=  $row[3];
			$name[$i]= $row[4];
			$calHour[$i]= $row[5];
			$weeks[$i]= $row[6];
			$i++;
		}
	}	
}
// var_dump($overtimeInterval);
// var_dump($overtimeHours);
// var_dump($overtimeType);
// var_dump($direct);
// var_dump($weeks);

$data=array($depid,$depname,$lineno,$id,$name,$calHour,$weeks);
mysqli_free_result($query); 



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
if($count>0){//如果已經發送過的郵件就不用再次發送了
	$email = new PHPMailer();
	$email->From      = 'Paul_Qin@foxlink.com.tw';
	$email->FromName  = '加班人員工時預警';
	$email->Subject   = $report_date.' '.$project.'  工時預警郵件';
	// $email->Subject   = $report_date.' '.$project.' '.$shift.' 工時預警郵件';
	$message = "您好!\n"
				."以下人員加班工時經助理進行過修改，請確認修改後資料是否無誤!\n"
				."詳情見附件！\n\n\n"
				."Contact Us\n系統整合課\n姓名:蒲秦川\n分機:33461\nE-mail: Paul_Qin@foxlink.com";
	$email->Body      = $message;

	$email->AddAddress("Paul_Qin@foxlink.com.tw");
	$email->AddAddress("Minjing_Zou@foxlink.com.tw");
	// $email->AddAddress("Denil_Chuang@foxlink.com.tw");
	// $email->AddAddress("Vic_Pan@foxlink.com.tw");
	// $email->AddAddress("Xiaocui_Yan@foxlink.com.tw");
	// $email->AddAddress("Canny_Du@foxlink.com.tw");
	// $email->AddAddress("Lauren_Juan@foxlink.com.tw");
	 
	// $mysqli->close();
		
	$email->CharSet="UTF-8";
	//---------  ---------- "../sfc/excel/"
	$fileName = "Warning_OverTime";
	$headArr = array("部門","部門名稱","線別","姓名","工號","加工總工時","加班日期");
	// $data = array(array(1,2,3),array(1,3,5),array(5,7,9));
	// getExcel($fileName,$headArr,$data);
	$excel_name =  getExcel($fileName,$headArr,$data);
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