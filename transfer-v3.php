<?php


function postCURL(string $url, array $data, string $seckey) {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); //Post Fields
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 200);
    curl_setopt($ch, CURLOPT_TIMEOUT, 200);
    $token = 'Bearer '.$seckey;
    $headers = array('Content-Type: application/json','Authorization:'.$token);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $request = curl_exec($ch);
    $result = json_decode($request, true);

    return $result;
}

$postdata = [
    "account_bank" => "044",
    "account_number" => "0690000040",
    "amount" => 5500,
    "narration" => "Akhlm Pstmn Trnsfr xx007",
    "currency" => "NGN",
    "reference" => "akhlm-pstmnpyt-rfxx44344007_PMCKDU_1",
    "callback_url" => "https://webhook.site/b3e505b0-fe02-430e-a538-22bbbce8ce0d",
    "debit_currency" => "NGN"
];

print_r(postCURL("https://api.flutterwave.com/v3/transfers", $postdata, "your secret key"));





?>
