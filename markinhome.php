<?php

// Your PHP code adapted slightly for GitHub Action environment

$date = date("Y-m-d");

$holidays = [
    "2024-01-26",
    "2024-04-09",
    "2024-04-11",
    "2024-05-01",
    "2024-08-15",
    "2024-09-17",
    "2024-10-02",
    "2024-10-31",
    "2024-11-01",
    "2024-12-25"
];

if (in_array($date, $holidays)) {
    echo "Today is a holiday, skipping job.\n";
    exit(0);
}
// Sleep for random seconds from 0 to 120 (2 minutes) to spread execution randomly between 10:28 and 10:30
// sleep(rand(0, 120));

// Get token

$username = "innoULBjwtclientid";
$password = "ITInnowave20!&MU";

try {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "http://210.89.42.212:9999/HRMSAPI/oauth/token");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "username=innowave&password=Innowave@123$&grant_type=password");
    curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Accept: application/json",
        "User-Agent: PostmanRuntime/7.44.0",
        "Postman-Token: " . uniqid()
    ]);

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

    echo "Access Token: " . $access_token . "\n";

} catch (Exception $e) {
    echo "Error occurred: " . $e->getMessage() . "\n";
    exit(1);
}

// Prepare attendance data

$data = [
    "currentLat" => "18.574711",
    "currentLon" => "74.023659",
    "hrmsEmpId" => 119245,
    "locationName" => "NH 548DD, Haveli, Pune, Maharashtra, India ,",
    "ulbId" => 707,
    "deviceFrom" => "I",
    "deviceImeiNoIn" => "00008110-000135E40A07A01E"
];

$postdata = json_encode($data);

$authorization = "Authorization: Bearer " . $access_token;

$ch1 = curl_init();

curl_setopt($ch1, CURLOPT_URL, "http://210.89.42.212:9999/HRMSAPI/hrms/rest/geo/save/emp/attendance/intime_flutter");
curl_setopt($ch1, CURLOPT_POST, 1);
curl_setopt($ch1, CURLOPT_POSTFIELDS, $postdata);
curl_setopt($ch1, CURLOPT_HTTPHEADER, ['Content-Type: application/json', $authorization]);
curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch1, CURLOPT_FOLLOWLOCATION, 1);

$output1 = curl_exec($ch1);

if (curl_errno($ch1)) {
    echo "Error on attendance post: " . curl_error($ch1) . "\n";
    exit(1);
}

$http_code1 = curl_getinfo($ch1, CURLINFO_HTTP_CODE);
curl_close($ch1);

echo "Attendance API Response (HTTP code $http_code1): " . $output1 . "\n";

if ($http_code1 !== 200) {
    exit(1);
}

?>
