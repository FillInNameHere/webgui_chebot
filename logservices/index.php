<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 26.04.2016
 * Time: 23:14
 */
require_once __DIR__ . '/models/GameLog.php';
require_once __DIR__ . '/repo/GameLogRepo.php';
require_once __DIR__ . '/repo/StateRepo.php';
require_once __DIR__ . '/../sessionservices/SessionFactory.php';

use vindinium\logservices\models\GameLog;
use vindinium\logservices\repos\GameLogRepo;
use vindinium\logservices\repos\StateRepo;
use vindinium\sessionservices\SessionFactory;

//Session ...
$sf = new SessionFactory();
if (!$sf->validate("auth")) {
    die('Access denied!');
}

//Get Data
$glREPO = new GameLogRepo();
$stateREPO = new StateRepo();

$states = $glREPO->getStates();
$learnedStates = $stateREPO->findAllStates();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Vindinium Project - Logs</title>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        th, td {
            padding: 5px;
        }
    </style>
</head>
<body>
<div>
    <div id="data_form">
        <h2>Choose:</h2>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <?php
            foreach ($states as $name => $value) {
                ?>
                <input type="submit" value="<?php echo $value; ?>" name="<?php echo $name; ?>"/>
                <?php
            }
            ?>
            <span></span>
        </form>
        <br/>
    </div>
    <div style="float: left; width: 49%">
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
            <table>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>URL</th>
                    <th>Win</th>
                    <th>Not Finished</th>
                    <th>Detailed Log</th>
                </tr>
                <?php
                foreach ($gls as $gl) { ?>
                    <tr bgcolor="<?php if($gl->getWin() == 1){ echo "#12c000";} elseif ($gl->getCrashed() == 1){echo "#ffd800";} else { echo "ff0000"; }?>">
                        <td bgcolor="white"><?php echo $gl->getGameLogId(); ?></td>
                        <td><?php echo $gl->getStartingTime()?></td>
                        <td><a href="<?php echo $gl->getGameURL(); ?>"
                               target="_blank"><?php echo $gl->getGameURL(); ?></a>
                        </td>
                        <td><?php echo $gl->getWin() . ""; ?></td>
                        <td><?php echo $gl->getCrashed() . ""; ?></td>
                        <td><a href="showDetailedLog.php?gameLogId=<?php echo $gl->getGameLogId() ?>"
                               target="_blank">Show</td>
                    </tr>
                <?php } ?>
            </table>
        <?php } ?>
    </div>
    <div style="width:49%; float: right; border: solid #27a598; padding-left: 5pt">
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
            <p>Learned States: <?php echo count($learnedStates);?> / 102.400</p>
            <h3> Note: Crashed (Unfinished) games do not count as losses</h3>
        </div>
    </div>
</div>
</body>
</html>