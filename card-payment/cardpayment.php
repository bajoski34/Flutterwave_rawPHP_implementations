<?php
$ch = curl_init();
// get the test cards to test PIN and noauth payments
//https://developer.flutterwave.com/docs/test-cards

//PIN
/* Test MasterCard PIN authentication
5531 8866 5214 2950
cvv 564
Expiry: 09/22
Pin 3310
otp 12345 */

//NO_Auth
/* Test VisaCard (Address Verification)
4556 0527 0417 2643
cvv: 899
Expiry: 01/21
*/

$data = array(
  "PBFPubKey"=> "FLWPUBK_TEST-647f6289f74aba024f10cc12f71bd6a2-X",
  "cardno"=> "4556052704172643",
  "cvv"=>"899",
  "expirymonth"=> "01",
  "expiryyear"=> "21",
  "currency"=> "NGN",
  "country"=> "NG",
  "amount"=> "10",
  "email"=> "user@gmail.com",
  "phonenumber"=> "0902620185",
  "firstname"=> "temi",
  "lastname"=> "desola",
  "IP"=> "355426087298442",
  "txRef"=>"MC-".uniqid(),// your unique merchant reference
  "meta"=> array(
      array("metaname"=> "flightID", "metavalue"=> "123949494DC")
    ),
  "redirect_url"=> "https://rave-webhook.herokuapp.com/receivepayment",
  "device_fingerprint"=> "69e6b7f0b72037aa8428b70fbe03986c"
);

// this is the getKey function that generates an encryption Key for you by passing your Secret Key as a parameter.
function getKey($seckey){
    $hashedkey = md5($seckey);
    $hashedkeylast12 = substr($hashedkey, -12);
  
    $seckeyadjusted = str_replace("FLWSECK-", "", $seckey);
    $seckeyadjustedfirst12 = substr($seckeyadjusted, 0, 12);
  
    $encryptionkey = $seckeyadjustedfirst12.$hashedkeylast12;
    return $encryptionkey;
  
  }
  
  
  
  function encrypt3Des($data, $key)
   {
    $encData = openssl_encrypt($data, 'DES-EDE3', $key, OPENSSL_RAW_DATA);
          return base64_encode($encData);
   }

   $SecKey = 'FLWSECK_TEST-1609ba49bee599841c9a590a97984685-X';
    
    $key = getKey($SecKey); 
    
    $dataReq = json_encode($data);
    
    $post_enc = encrypt3Des( $dataReq, $key );

  

    $postdata = array(
        'PBFPubKey' => 'FLWPUBK_TEST-647f6289f74aba024f10cc12f71bd6a2-X',
        'client' => $post_enc,
        'alg' => '3DES-24'
    );
       
function initiateCharge($postdata,$ch){
     
curl_setopt($ch, CURLOPT_URL, "https://api.ravepay.co/flwv3-pug/getpaidx/api/charge");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata)); //Post Fields
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 200);
curl_setopt($ch, CURLOPT_TIMEOUT, 200);


$headers = array('Content-Type: application/json');

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    return curl_exec($ch);
}

$result = initiateCharge($postdata, $ch);
$result = json_decode($result, true);
print_r($result);

echo $result['data']['suggested_auth'];

echo "<br /><br />";

//Using a Local Mastercard/verve i.e. card issued in Nigeria

//if suggested_auth returns pin, add pin and suggested_auth to your payload again, re-encrypt
// {
//     "status": "success",
//     "message": "AUTH_SUGGESTION",
//     "data": {
//       "suggested_auth": "PIN"
//     }
//   }

if($result['data']['suggested_auth'] === 'PIN'){
    $data['pin'] = '3310';
    $data["suggested_auth"] = "PIN";
}
//add the cards billing address details and suggested_auth to your payload again, re-encrypt it
if($result['data']['suggested_auth'] === 'NOAUTH_INTERNATIONAL' ||
$result['data']['suggested_auth'] === 'AVS_VBVSECURECODE'){

  $data['suggested_auth'] = 'AVS_VBVSECURECODE/NOAUTH_INTERNATIONAL';
  $data['billingzip'] = '07205';
  $data['billingcity'] = 'Hillside';
  $data['billingaddress'] = '470 Mundet PI';
  $data['billingstate'] = 'NJ';
  $data['billingcountry'] = 'US';
}

$dataReq = json_encode($data);
    
$post_enc = encrypt3Des( $dataReq, $key );

$postdata = array(
    'PBFPubKey' => 'FLWPUBK_TEST-647f6289f74aba024f10cc12f71bd6a2-X',
    'client' => $post_enc,
    'alg' => '3DES-24'
);

$result = initiateCharge($postdata, $ch);
$result = json_decode($result, true);
print_r($result);
curl_close($ch);





























