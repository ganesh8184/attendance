<?php 

$date = date("Y-m-d");

$holidays = [
    "2024-01-26", "2024-04-09", "2024-04-11", "2024-05-01",
    "2024-08-15", "2024-09-17", "2024-10-02", "2024-10-31",
    "2024-11-01", "2024-12-25"
];

if (in_array($date, $holidays)) {
    // Holiday - do nothing or exit early
    exit;
}

// if (!isset($_GET['issleep'])) {
//     sleep(rand(60, 300));
// }

session_start();

$username = "innoULBjwtclientid";
$password = "ITInnowave20!&MU";

function getAccessToken($username, $password) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://210.89.42.212:9999/HRMSAPI/oauth/token");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "username=innowave&password=Innowave@123$&grant_type=password");
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $output = curl_exec($ch);
    if (curl_errno($ch)) {
        curl_close($ch);
        throw new Exception("cURL error while fetching token: " . curl_error($ch));
    }

    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) {
        throw new Exception("HTTP error while fetching token: Code $http_code, Response: $output");
    }

    $json = json_decode($output, true);
    if (!isset($json['access_token'])) {
        throw new Exception("Access token not found in response");
    }

    return $json['access_token'];
}

function postCurl($url, $data, $access_token) {
    $postdata = json_encode($data);
    $authorization = "Authorization: Bearer " . $access_token;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', $authorization]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $output = curl_exec($ch);
    if (curl_errno($ch)) {
        curl_close($ch);
        throw new Exception("cURL error while POST: " . curl_error($ch));
    }

    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) {
        throw new Exception("HTTP error while POST: Code $http_code, Response: $output");
    }

    return json_decode($output, true);
}

try {
    // Get access token once
    $access_token = getAccessToken($username, $password);

    // Step 1: Check if timeout is marked and get attid
    $timeoutCheckData = ["attid" => "0", "hrmsEmpId" => 119245, "ulbId" => 707];
    $timeoutCheckUrl = "http://210.89.42.212:9999/HRMSAPI/hrms/rest/geo/emp/attendance/ismark/timeoutV1/det";

    $timeoutResponse = postCurl($timeoutCheckUrl, $timeoutCheckData, $access_token);

    if (!isset($timeoutResponse['attid'])) {
        throw new Exception("Attendance ID not found in timeout check response");
    }

    $attId = $timeoutResponse['attid'];

    // Step 2: Mark Out attendance
    $markOutData = [
        "attid" => $attId,
        "outtimeLat" => "18.5199552",
        "outtimeLon" => "73.778571",
        "hrmsEmpId" => 119245,
        "outtime_locationName" => "NDA Pashan Road, Bavdhan, Pune, Maharashtra, India",
        "ulbId" => 707,
        "deviceFrom" => "I",
        "deviceImeiNoIn" => "00008110-000135E40A07A01E",
        "locationName" => "null"
    ];

    $markOutUrl = "http://210.89.42.212:9999/HRMSAPI/hrms/rest/geo/save/emp/attendance/outtime";

    $markOutResponse = postCurl($markOutUrl, $markOutData, $access_token);

    echo json_encode($markOutResponse);

} catch (Exception $e) {
    echo json_encode([
        "error" => true,
        "message" => $e->getMessage()
    ]);
}

?>
