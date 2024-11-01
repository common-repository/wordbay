<?php

function urldecrypt($string) {

   return base64_decode($string);
}

$newBuyUrl = $_GET['buyurl']; /* Get the "encrypted" url passed from the affiliate link */
$newBuyUrl = explode('___', $newBuyUrl);
$newBuyUrl = urldecrypt($newBuyUrl[1]);
header("Location:$newBuyUrl",TRUE,301);   /* Redirect browser to the new address */
?>