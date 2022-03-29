<?php
require "functions.php";
$url = "http://payway.bubileg.cz/api/echo";
date_default_timezone_set("Europe/Prague");
$signature = "";
$date = date("YmdHis");
$priv_key = load_priv_key("keys/56a8d0ad-private-key.key");
$pub_key = load_pub_key("keys/56a8d0ad-public-key.pub");
$banka_pub_key = load_pub_key("keys/banka/payway-public-key.pub");
$data = array(
    'merchantId' => "56a8d0ad",
    'dttm' => $date
);
openssl_sign($data["merchantId"] . "|" . $data["dttm"], $signature, $priv_key);
$data["signature"] = base64_encode($signature);

$ok = openssl_verify($data["merchantId"] . "|" . $data["dttm"], $signature, $pub_key);
echo "check #1: ";
if ($ok == 1) {
    echo "signature ok\n";
} elseif ($ok == 0) {
    echo "bad (there's something wrong)\n";
} else {
    echo "ugly, error checking signature\n";
}
echo "<br>\n";
echo "<br>\n";
$payload = json_encode($data);
echo "\"" . $payload . "\"";
echo "<br>\n";

$ch = curl_init($url);
# Setup request to send json via POST.
$payload = json_encode($data);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
# Return response instead of printing.
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
# Send request.
$result = curl_exec($ch);
curl_close($ch);

echo $result;
echo "<br>\n";
echo "<br>\n";

$result = json_decode($result, true);
$ok = openssl_verify($result["resultCode"] . "|" . $result["resultMessage"] . "|" . $result["dttm"], base64_decode($result["signature"]), $banka_pub_key);
echo "check #2: ";
if ($ok == 1) {
    echo "signature ok\n";
} elseif ($ok == 0) {
    echo "bad (there's something wrong)\n";
} else {
    echo "ugly, error checking signature\n";
}
