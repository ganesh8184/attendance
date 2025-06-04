<?php 
$date=date("Y-m-d");
// echo $date;die;
if($date == "2024-01-26" || $date == "2024-04-09" || $date == "2024-04-11" ||  $date == "2024-05-01" || $date == "2024-08-15" || $date == "2024-09-17" || $date == "2024-10-02" || $date == "2024-10-31" || $date == "2024-11-01"|| $date == "2024-12-25")
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
 // sleep(rand(60,300));
session_start();
//get token 
    $username="innoULBjwtclientid";
    $password="ITInnowave20!&MU";

	$ch = curl_init();
	//curl_setopt($ch, CURLOPT_URL, "https://hr.avantecodeworx.com/HRMSAPI/oauth/token");
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
	// echo $access_token;die;
	
	
    $data = array("attid"=> "0","hrmsEmpId"=> 119245,"ulbId"=> 707);

    $postdata = json_encode($data);
    $authorization = "Authorization: Bearer ".$access_token;
    $ch1 = curl_init();
	//curl_setopt($ch1, CURLOPT_URL, "https://hr.avantecodeworx.com/HRMSAPI/hrms/rest/geo/emp/attendance/ismark/timeoutV1/det");
	curl_setopt($ch1, CURLOPT_URL, "http://210.89.42.212:9999/HRMSAPI/hrms/rest/geo/emp/attendance/ismark/timeoutV1/det");
	curl_setopt($ch1, CURLOPT_POST, 1);
    curl_setopt($ch1, CURLOPT_POSTFIELDS,$postdata);
    curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
	curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, 1);
	$output1 = curl_exec($ch1);
	$http_code1 = curl_getinfo($ch1, CURLINFO_HTTP_CODE);
	curl_close($ch1);
	$json1 = json_decode($output1, true);
	$attId= $json1['attid'];
	
	
	
	
	
   $ch = curl_init();
	//curl_setopt($ch, CURLOPT_URL, "https://hr.avantecodeworx.com/HRMSAPI/oauth/token");
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
//	echo $access_token;die;
	
// 	$file="/home/timesheetavante/public_html/attId.txt";
// 	$myfile = fopen($file, "r") or die("Unable to open file!");
//     $attId=fread($myfile,filesize($file));
//     fclose($myfile);
    
    
// // Mark Out 
//     if($attId==""||$attId==NULL||$attId==null||$attId="null")
//     {
//         $inv_qry = "SELECT  `a_empid`, `a_attid`, `a_status`, `a_added_date` FROM `attendace_mark` order by a_added_date desc limit 1";
      
//         try {
//             $statement3 = $conn->query($inv_qry);
//             $attendance    = $statement3->fetch(PDO::FETCH_ASSOC);
            
//             } catch (PDOException $ex) {
//             print_r($ex->getMessage());
//             die;
//             } finally{
//             $statement = null;
//             //$conn = null;
//             }
//             $attId=$attendance['a_attid'];
//         }


    $data = array("attid"=>$attId,"outtimeLat"=>"18.5199552","outtimeLon"=>"73.778571","hrmsEmpId"=> 119245,"outtime_locationName"=> "NDA Pashan Road, Bavdhan, Pune, Maharashtra, India","ulbId"=> 707,"deviceFrom"=>"I","deviceImeiNoIn"=>"00008110-000135E40A07A01E","locationName"=>"null");

    $postdata = json_encode($data);
    // echo $postdata;die;
    $authorization = "Authorization: Bearer ".$access_token;
    $ch1 = curl_init();
	//curl_setopt($ch1, CURLOPT_URL, "https://hr.avantecodeworx.com/HRMSAPI/hrms/rest/geo/save/emp/attendance/outtime");
	curl_setopt($ch1, CURLOPT_URL, "http://210.89.42.212:9999/HRMSAPI/hrms/rest/geo/save/emp/attendance/outtime");
	curl_setopt($ch1, CURLOPT_POST, 1);
    curl_setopt($ch1, CURLOPT_POSTFIELDS,$postdata);
    curl_setopt($ch1, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
	curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, 1);
	$output1 = curl_exec($ch1);
	$http_code1 = curl_getinfo($ch1, CURLINFO_HTTP_CODE);
	curl_close($ch1);
	$json1 = json_decode($output1, true);			
// 	$attId=$json1['attId'];
// 	$_SESSION["attId"] = $attId;
echo $output1;

}
?>