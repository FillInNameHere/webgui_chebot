<?php
require_once __DIR__ . '../../models/GameLog.php';
require_once __DIR__ . '../../repo/GameLogRepo.php';
require_once __DIR__ . '../../../sessionservices/SessionFactory.php';

use vindinium\logservices\models\GameLog;
use vindinium\logservices\repos\GameLogRepo;
use vindinium\sessionservices\SessionFactory;

//Session ...
$sf = new SessionFactory();
if (!$sf->validate("auth")) {
    die('Access denied!');
}

//Get Data
$glREPO = new GameLogRepo();

$states = $glREPO->getStates();
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
        <title>Vindinium Projekt - CHEBot v1.0 - GameOverview</title>
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
                    <a class="navbar-brand" href="../chebot1/">CHEBot v1.0</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a href="../chebot1/">GameOverview</a></li>
                        <li><a href="../chebot1/config.php">Config</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Choose Bot-Version <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="../chebot1/">CHEBot v1.0</a></li>
                                <li><a href="../chebot2/">CHEBot v2.0</a></li>
                            </ul>
                        </li>
                    </ul>
                </div><!-- navbar-collapse -->
            </div>
        </nav>

        <!-- Content -->
        <div class="container" style="margin-bottom: 0; margin-top: 10px; min-height: 30px; position: relative;">
            <div>
                <div id="data_form" style="float: left; width: 50%;">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                        <?php
                        foreach ($states as $name => $value) {
                    ?>
                        <button type="submit" class="btn btn-default" <?php echo $value; ?>" name="<?php echo $name; ?>"><?php echo $value; ?></button>
                    <?php
                    }
                        ?>
                    </form>
                    <br/>
                </div>

                <div style="float: left; width: 50%;">
                    <!-- Links: GameOverview -->
                    <?php
                        $paramSet = false;
                        $paramName = null;
                        foreach ($states as $name => $value) {
                            if (isset($_POST[$name])) {
                                $paramSet = true;
                                $paramName = $name;
                            }
                        }

                        if ($paramSet) {
                            $gls = $glREPO->getGLArray($paramName);
                    ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead bgcolor="#fff">
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>URL</th>
                                    <th>Win</th>
                                    <th>Not Finished</th>
                                    <th>Detailed Log</th>
                                </tr>
                            </thead>
                            </tbody>
                                <?php
                                foreach ($gls as $gl) { ?>
                                    <tr class="<?php if($gl->getWin() == 1){ echo "success";} elseif ($gl->getCrashed() == 1){echo "warning";} else { echo "danger"; }?>">
                                        <td><b><?php echo $gl->getGameLogId(); ?></b></td>
                                        <td><?php echo $gl->getStartingTime()?></td>
                                        <td><a href="<?php echo $gl->getGameURL(); ?>" target="_blank"><?php echo $gl->getGameURL(); ?></a></td>
                                        <td><?php echo $gl->getWin() . ""; ?></td>
                                        <td><?php echo $gl->getCrashed() . ""; ?></td>
                                        <td><a href="showDetailedLog.php?gameLogId=<?php echo $gl->getGameLogId() ?>" target="_blank">Show</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php } ?>
                </div>

                <!-- Rechts: Statistics -->
                <div class="jumbotron" style="width:39%; float: right; position: fixed; right: 10%; padding-top: 10px; padding-left: 10px">
                    <h1>Statistics</h1>
                    <h2>Winrate</h2>
                    <div style="width: auto">
                        <?php
                        $noOfWins = count($glREPO->findWinningGameLogs());
                        $noOfGames = count($glREPO->findAllGameLogs());
                        $noOfCrashed = count($glREPO->findByCrash(1));
                        $noOfLoses = count($glREPO->findLostGameLogs())-$noOfCrashed;
                        $winrate = ($noOfWins / ($noOfGames - $noOfCrashed)) * 100;
                        ?>
                        <p>Total <?php echo $noOfGames ?> games</p>
                        <p><?php echo $noOfCrashed ?> unfinished games</p>
                        <p>And <?php echo $noOfLoses?> losses</p>
                        <p>Total <?php echo $noOfWins;
                            if ($noOfWins > 1) {
                                echo " wins";
                            } else {
                                echo " win";
                            } ?></p>
                        <p>Winrate: <?php echo number_format($winrate, 2) . "%" ?></p>
                        <h3> Note: Crashed (Unfinished) games do not count as losses</h3>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>