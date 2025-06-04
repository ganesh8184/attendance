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

$username = "innoULBjwtclientid";
$password = "ITInnowave20!&MU";

try {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "http://210.89.42.212:9999/HRMSAPI/oauth/token");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "username=innowave&password=Innowave@123$&grant_type=password");
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
    
    // Add custom headers
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Accept: application/json",
        "User-Agent: PostmanRuntime/7.44.0",
         "Postman-Token: " . uniqid()
    ]);

    // Disable SSL verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $output = curl_exec($ch);

    if (curl_errno($ch)) {
        throw new Exception("cURL Error: " . curl_error($ch));
    }

    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) {
        throw new Exception("HTTP Error: " . $http_code . " - Response: " . $output);
    }

    $json = json_decode($output, true);

    if (!isset($json['access_token'])) {
        throw new Exception("Invalid response or access token not found: " . $output);
    }

    $access_token = $json['access_token'];

    echo "Access Token: " . $access_token;

} catch (Exception $e) {
    echo "Error occurred: " . $e->getMessage();
}


 // echo $access_token;die;
// w
  

// Mark In 

    $data = array("currentLat"=> "18.574711","currentLon"=> "74.023659","hrmsEmpId"=> 119245,"locationName"=> "NH 548DD, Haveli, Pune, Maharashtra, India ,","ulbId"=> 707,"deviceFrom"=>"I","deviceImeiNoIn"=>"00008110-000135E40A07A01E");


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

//  $sql_insert = "INSERT INTO `attendace_mark`(`a_empid`, `a_attid`, `a_status`, `a_added_date`) VALUES (1004,".$attId.",0,NOW())";

      

//     $conn->exec($sql_insert);

  

//  $file="/home/timesheetavante/public_html/attId.txt";

//     $fp = fopen($file,"w");

//     fwrite($fp,$attId);

//     fclose($fp);

    

    

    

    

  // echo $attId;

}

?>