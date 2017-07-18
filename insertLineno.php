<?php 
$str = "";
$temp = 1000;
$cch = "INSERT INTO `lineno` (`id`, `workshopno`, `lineno`, `use`, `lockperson`, `linesize`) VALUES ";
for($i = 1; $i<=44;$i++){
	$sum = $temp+$i;
	if($i <=9){
		$i = "0".$i;
	}
	$tempLine = "M1L-".$i;
	if($i==44){
		$cch .= "(".$sum.", '第一車間', '$tempLine', 0, '', 0)";
	}else{
		$cch .= "(".$sum.", '第一車間', '$tempLine', 0, '', 0),";
	}
	
 
}
echo $cch."<br>";
$sql = "delete from lineno where lineno like 'M1L%'";
echo $sql;

?>