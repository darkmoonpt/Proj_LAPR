<?php
require "core/init.php";

if(!isset($_SESSION['id'])) {
    header('Location:'.'login.php');
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="refresh" content="1.5; url=login.php"/>
        <title>Takemore.com</title>

        <!-- Latest compiled and minified CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />

        <!-- Optional theme -->
        <link href="css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />

        <!-- Manuel change -->
        <link href="css/login_logout.css" rel="stylesheet" type="text/css" />
    </head>

    <body>

    <div class="login">
        <img src="img/takemore_lg.png"/>
        <h4>Não tem permissões.</h4>
        <h4>Obrigado pela Visita</h4>
    </div>
</body>

<!-- Jquery  CDN -->
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>

<!-- Latest compiled and minified JavaScript -->
<script src="js/bootstrap.min.js"></script>
</html>
