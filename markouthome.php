<?php

$date = date("Y-m-d");

// List of holiday dates to skip
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
//     // Random sleep between 60 to 300 seconds (1 to 5 minutes)
//     sleep(rand(60, 300));
// }

session_start();

$username = "innoULBjwtclientid";
$password = "ITInnowave20!&MU";

function getAccessToken($username, $password): string
{
    try {
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
            throw new Exception("cURL error (getAccessToken): " . curl_error($ch));
        }
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code !== 200) {
            throw new Exception("HTTP error (getAccessToken): Code $http_code, Response: $output");
        }

        $json = json_decode($output, true);
        if (!isset($json['access_token'])) {
            throw new Exception("Access token not found in response: $output");
        }

        return $json['access_token'];
    } catch (Exception $e) {
        throw new Exception("Failed to get access token: " . $e->getMessage());
    }
}

function postCurl($url, $data, $access_token)
{
    try {
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
            throw new Exception("cURL error (postCurl): " . curl_error($ch));
        }

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code !== 200) {
            throw new Exception("HTTP error (postCurl): Code $http_code, Response: $output");
        }

        return json_decode($output, true);
    } catch (Exception $e) {
        throw new Exception("Failed to POST data: " . $e->getMessage());
    }
}

try {
    // Step 1: Get access token
    $access_token = getAccessToken($username, $password);

    // Step 2: Mark In - check if timeout is marked and get attid
    $postDataIn = [
        "attid" => "0",
        "hrmsEmpId" => 119245,
        "ulbId" => 707
    ];
    $urlCheckTimeout = "http://210.89.42.212:9999/HRMSAPI/hrms/rest/geo/emp/attendance/ismark/timeoutV1/det";

    $responseIn = postCurl($urlCheckTimeout, $postDataIn, $access_token);

    if (!isset($responseIn['attid'])) {
        throw new Exception("Attendance ID (attid) missing from response");
    }
    $attId = $responseIn['attid'];

    // Step 3: Mark Out
    $postDataOut = [
        "attid" => $attId,
        "outtimeLat" => "18.574698",
        "outtimeLon" => "74.023668",
        "hrmsEmpId" => 119245,
        "outtime_locationName" => "NH 548DD, Haveli, Pune, Maharashtra, India",
        "locationName" => "null",
        "deviceFrom" => "I",
        "deviceImeiNoIn" => "00008110-000135E40A07A01E",
        "ulbId" => 707
    ];
    $urlMarkOut = "http://210.89.42.212:9999/HRMSAPI/hrms/rest/geo/save/emp/attendance/outtime";

    $responseOut = postCurl($urlMarkOut, $postDataOut, $access_token);

    echo json_encode($responseOut);

} catch (Exception $e) {
    // Log or display error message
    echo json_encode([
        "error" => true,
        "message" => $e->getMessage()
    ]);
}

?>
