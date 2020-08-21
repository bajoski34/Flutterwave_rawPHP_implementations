<?php
//data required. You can check the doc for more options to add....
$postdata = array(
        "tx_ref"=>uniqid().uniqid(),
        "amount"=>"100",
        "currency"=>"NGN",
        "redirect_url"=>"https://webhook.site/9d0b00ba-9a69-44fa-a43d-a82c33c36fdc",
        "payment_options"=>"card",
        "meta"=> array(
          "consumer_id"=>23,
          "consumer_mac"=>"92a3-912ba-1192a"
        ),
        "customer"=> array(
         "email"=>"user@gmail.com",
          "phonenumber"=>"080****4528",
          "name"=>"Yemi Desola"
        ),
        "subaccounts"=> array(
            array(
            "id" => "RS_1D784FBB1A7E6EA6AC86F76CEEA03880"
            );
        )
       "customizations"=> array(
          "title"=>"Pied Piper Payments",
          "description"=>"Middleout isn't free. Pay the price",
          "logo"=>"https://assets.piedpiper.com/logo.png"
            )
);

//making a call to the endpoint....
$ch = curl_init();
    
curl_setopt($ch, CURLOPT_URL, "https://api.flutterwave.com/v3/payments");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata)); //Post Fields
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 200);
curl_setopt($ch, CURLOPT_TIMEOUT, 200);

$secKey = 'FLWSECK_TEST-XXXXXXXXXXXXXXXXXXXXXXXX-X';
$token = 'Bearer '.$secKey;
$headers = array('Content-Type: application/json', 'Authorization:'.$token);

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$request = curl_exec($ch);
$result = json_decode($request, true);
print_r($result);

echo $result['data']['link'];
header('location:'.$result['data']['link']);


// if ($request) {
//     $result = json_decode($request, true);
//     echo "<pre>";
//     print_r($result);
// }else{
//     if(curl_error($ch))
//     {
//         echo 'error:' . curl_error($ch);
//     }
// }

curl_close($ch);
