<?php
require './captchas.php';
$captcha = \Captchas\create_token();
?>

<html>

<head>
    <title>Send Andrew an Email!</title>
</head>

<body>
    <h1>Send Andrew an Email!</h1>
    <form method="POST" action="/send-email.php">
        Your email:
        <input type="email" name="from" placeholder="your email address" />
        <br /><br />
        <textarea name="body" rows="5" cols="33">Type your well wishes here</textarea>
        <br /><br />
        Solve this puzzle to prove you are not a robot! <b><?= $captcha[1] ?></b>
        <input type="number" name="captcha_input" />
        <br /><br />

        <input type="hidden" name="captcha_token" value="<?= $captcha[0] ?>" />
        <input type="submit" value="Send Email">
    </form>
</body>

</html>