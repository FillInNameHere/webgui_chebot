<?php
require_once __DIR__ . '/../../sessionservices/SessionFactory.php';
require_once __DIR__ . '/models/Vars.php';
require_once __DIR__ . '/repo/VarRepo.php';

use vindinium\config\models\Vars;
use vindinium\config\repo\VarRepo;
use vindinium\sessionservices\SessionFactory;

//Session ...
$sf = new SessionFactory();
if (!$sf->validate("auth")) {
    die('Access denied!');
}

$varREPO = new VarRepo();

if (isset($_POST["submit"])) {
    $newVars = new Vars();
    foreach ($newVars as $key => $value) {
        $newVars->$key = $_POST[$key];
    }
    $newVars->refactor();
    $varREPO->setVars($newVars);
}

$vars = $varREPO->getVars();
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
        <title>Vindinium Projekt - CHEBot 2 - Config</title>
    </head>
    <body style="background-color:#3277b3; overflow-y: scroll;">
        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" style="border-bottom: 1px solid #000; margin: 0;">
            <div class="container">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="../chebot2/">CHEBot 2</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a href="../chebot2/">GameOverview</a></li>
                        <li><a href="../chebot2/config.php">Config</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Choose Bot-Version <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="../chebot1/">CHEBot 1</a></li>
                                <li><a href="../chebot2/">CHEBot 2</a></li>
				<li><a href="../chebot3/">CHEBot 3</a></li>
				<li><a href="../chebot4/">CHEBot 4</a></li>

                            </ul>
                        </li>
                    </ul>
                </div><!-- navbar-collapse -->
            </div>
        </nav>

	<div class="container" style="width: 50%; margin: auto; margin-top: 10px;">
            <div class="jumbotron">
	        <div id="data_form">
                    <h2>Configuration</h2><br />
	            <form action="/logservices/chebot2/config.php" method="POST">
                        <?php foreach ($vars as $key => $value) { ?>
                        <label><?php echo $key; ?></label>
                        <input type="text" name="<?php echo $key; ?>" value="<?php echo $value; ?>"/><br />
                        <?php } ?><br />
			<button type="submit" name="submit" class="btn btn-default" >&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Save &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</button>
	            </form><br/>
                </div>
            </div>
     	</div>
    </body>
</html>