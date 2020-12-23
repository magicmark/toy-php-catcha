<?php

require './captchas.php';

// In real life I assume you sanitize these inputs somehow
$email = $_POST["email"];
$body = $_POST["body"];
$captcha_token = $_POST["captcha_token"];
$captcha_input = $_POST["captcha_input"];

if (!\Captchas\is_token_valid($captcha_token, $captcha_input)) {
    // Return early and die if the captcha is bad
    die('Bad captcha!');
}

// Continue the form sending logic here
?>

<html>

<head>
    <title>Email sent!</title>
</head>

<body>
    Congrats! Email was sent.
</body>

</html>