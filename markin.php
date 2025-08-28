<?php

$date = date("Y-m-d");

$holidays = [
    "2025-10-02", "2025-10-20", "2025-10-21", "2025-10-22",
    "2025-10-23", "2025-12-25"
];

if (in_array($date, $holidays)) {
    // It's a holiday - exit or do nothing
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
        $err = curl_error($ch);
        curl_close($ch);
        throw new Exception("Error getting access token: $err");
    }

    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) {
        throw new Exception("Failed to get token, HTTP code: $http_code, Response: $output");
    }

    $json = json_decode($output, true);
    if (!isset($json['access_token'])) {
        throw new Exception("Access token not found in response");
    }

    return $json['access_token'];
}

function postCurlRequest($url, $data, $access_token) {
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
        $err = curl_error($ch);
        curl_close($ch);
        throw new Exception("cURL POST error: $err");
    }

    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) {
        throw new Exception("HTTP error during POST: Code $http_code, Response: $output");
    }

    return json_decode($output, true);
}

try {
    $access_token = getAccessToken($username, $password);

    $data = [
        "currentLat" => "18.5200406",
        "currentLon" => "73.7785363",
        "hrmsEmpId" => 119245,
        "locationName" => "Bavdhan Office,",
        "ulbId" => 707,
        "deviceFrom" => "I",
        "deviceImeiNoIn" => "00008110-000135E40A07A01E"
    ];

    $url = "http://210.89.42.212:9999/HRMSAPI/hrms/rest/geo/save/emp/attendance/intime_flutter";

    $response = postCurlRequest($url, $data, $access_token);

    echo json_encode($response);

} catch (Exception $e) {
    echo json_encode([
        "error" => true,
        "message" => $e->getMessage()
    ]);
}
