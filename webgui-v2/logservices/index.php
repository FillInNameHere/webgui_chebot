<?php
    require_once __DIR__ . '/../sessionservices/SessionFactory.php';

    use vindinium\sessionservices\SessionFactory;

    //Session
    $sf = new SessionFactory();
    if (!$sf->validate("auth")) {
        die('Access denied!');
    }
?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <!-- Language settings -->
        <meta charset="utf-8" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" />
        <!-- Optional theme -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css" />
        <!-- Latest compiled and minified JQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <title>Vindinium Projekt</title>
    </head>
    <body style="background-color:#3277b3; overflow-y: scroll;">
        <!-- Headline -->
        <h1 class="pager"><span style="color: #fff">Choose Bot (Version)</span></h1>

        <div class="row">
            <div class="col-lg-3 col-md-4">&nbsp;</div>

            <!-- CHEBot v1.0 -->
            <div class="col-lg-3 col-md-4">
                <div class="thumbnail">
                    <a href="chebot1/index.php"><img src="get/chebot1.png" alt="CHEBot v1.0"></a>
                    <div class="caption">
                        <h3 class="pager">CHEBot v1.0</h3>
                        <p class="pager"><a href="chebot1/" class="btn btn-primary" role="button">Go to CHEBot v1.0</a></p>
                    </div>
                </div>
            </div>

            <!-- CHEBot v2.0 -->
            <div class="col-lg-3 col-md-4">
                <div class="thumbnail">
                    <a href="chebot2/index.php"><img src="get/chebot2.png" alt="CHEBot v2.0"></a>
                    <div class="caption">
                        <h3 class="pager">CHEBot v2.0</h3>
                        <p class="pager"><a href="chebot2/" class="btn btn-primary" role="button">Go to CHEBot v2.0</a></p>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-4">&nbsp;</div>
        </div>
    </body>
</html>