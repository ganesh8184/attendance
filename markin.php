<?php 
$date=date("Y-m-d");
// echo $date;
if($date == "2024-01-26" || $date == "2024-04-09" ||  $date == "2024-04-11" || $date == "2024-05-01" || $date == "2024-08-15" || $date == "2024-09-17" || $date == "2024-10-02" || $date == "2024-10-31" || $date == "2024-11-01"|| $date == "2024-12-25")
{
    // echo "hi";die;
}
else
{
if(isset($_GET['issleep']))

{

    

}

else

{

  sleep(rand(60,300));

}
//include("/home/timesheetavante/public_html/invoice/connection.php");

session_start();
//get token 
    $username="innoULBjwtclientid";
    $password="ITInnowave20!&MU";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://210.89.42.212:9999/HRMSAPI/oauth/token");
	curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
            "username=innowave&password=Innowave@123$&grant_type=password");
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);  
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$output = curl_exec($ch);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	$json = json_decode($output, true);			
	$access_token = $json['access_token'];
// 	echo $access_token;die;
	
// Mark In 
    $data = array("currentLat"=> "18.5200406","currentLon"=> "73.7785363","hrmsEmpId"=> 119245,"locationName"=> "Bavdhan Office,","ulbId"=> 707,"deviceFrom"=>"I","deviceImeiNoIn"=>"00008110-000135E40A07A01E");

    $postdata = json_encode($data);
    $authorization = "Authorization: Bearer ".$access_token;
    $ch1 = curl_init();
	curl_setopt($ch1, CURLOPT_URL, "http://210.89.42.212:9999/HRMSAPI/hrms/rest/geo/save/emp/attendance/intime_flutter");
	curl_setopt($ch1, CURLOPT_POST, 1);
    curl_setopt($ch1, CURLOPT_POSTFIELDS,$postdata);
    curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
	curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, 1);
	$output1 = curl_exec($ch1);
	echo $output1;
	$http_code1 = curl_getinfo($ch1, CURLINFO_HTTP_CODE);
	curl_close($ch1);
	$json1 = json_decode($output1, true);			
	// $attId=$json1['attId'];
	// $_SESSION["attId"] = $attId;
// 	$sql_insert = "INSERT INTO `attendace_mark`(`a_empid`, `a_attid`, `a_status`, `a_added_date`) VALUES (1004,".$attId.",0,NOW())";
      
//     $conn->exec($sql_insert);
	
// 	$file="/home/timesheetavante/public_html/attId.txt";
//     $fp = fopen($file,"w");
//     fwrite($fp,$attId);
//     fclose($fp);
    
    
    
    
	// echo $attId;
}
?>