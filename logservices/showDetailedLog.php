<?php
/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 01.05.2016
 * Time: 13:02
 */
require_once __DIR__ . '/models/GameLog.php';
require_once __DIR__ . '/repo/GameLogRepo.php';
require_once __DIR__ . '/repo/GameStepRepo.php';
require_once __DIR__ . '/../sessionservices/SessionFactory.php';

use vindinium\logservices\models\GameLog;
use vindinium\logservices\repos\GameLogRepo;
use vindinium\logservices\repos\GameStepRepo;
use vindinium\sessionservices\SessionFactory;

//Session ...
$sf = new SessionFactory();
if (!$sf->validate("auth")) {
    die('Access denied!');
}

//Get Data
$glREPO = new GameLogRepo();
$gsREPO = new GameStepRepo();
$states = $gsREPO->getStates();

if (isset($_GET["gameLogId"]) && !empty($_GET["gameLogId"])) $gameLogId = $_GET["gameLogId"];
elseif (isset($_POST["gameLogId"]) && !empty($_POST["gameLogId"])) $gameLogId = $_POST["gameLogId"];
else $gameLogId = 1;
$gameLog = $glREPO->findGameLog($gameLogId);
$gameSteps = $gsREPO->findAllGameSteps($gameLogId);
?>
<!DOCTYPE html>
<html>
<head>
    <Title>Vindinium Project - Detailed Log</Title>
    <style>
        table, th, td {
            border: 1px solid #666;
            border-collapse: collapse;
        }

        table {
            background-color: #333;
        }

        th, td {
            color: #999;
            padding: 5px;
        }
    </style>
</head>
<body bgcolor="#383b32">
<div id="data_form">
    <h2><span style="color: #fff;">Choose:</span></h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <?php
        foreach ($states as $name => $value) {
            ?>
            <input type="submit" value="<?php echo $value; ?>" name="<?php echo $name; ?>"/>
            <input type="hidden" name="gameLogId" value="<?php echo $gameLogId; ?>"/>
            <?php
        }
        ?>
        <span></span>
    </form>
    <br/>
</div>
<div>
    <?php
    $paramSet = false;
    $paramName = 'all';
    foreach ($states as $name => $value) {
        if (isset($_POST[$name])) {
            $paramSet = true;
            $paramName = $name;
        }
    }

    if ($paramName === 'all') {
        ?>
        <div style="float: left;">
            <table>
                <tr>
                    <th>ID</th>
                    <th>Best Action</th>
                    <th>Chosen Action</th>
                    <th>Old Q-Value</th>
                    <th>New Q-Value</th>
                    <th>Reward</th>
                    <th>State</th>
                </tr>
                <?php
                foreach ($gameSteps as $gs) { ?>
                    <tr>
                        <td><?php echo $gs->getGameStepId(); ?></td>
                        <td><?php echo $gs->getBestActionThen() ?></td>
                        <td><?php echo $gs->getChosenAction() ?></td>
                        <td><?php echo $gs->getOldQval() ?></td>
                        <td><?php echo $gs->getNewQval() ?></td>
                        <td><?php echo $gs->getReward() ?></td>
                        <td><?php echo $gs->getStateStateId() ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <div style="float: right;">
            <iframe src="<?php echo $gameLog->getGameURL(); ?>"
                    style="width: 1000px; height: 865px; border: none;"></iframe>
        </div>
        <?php
    }
    ?>
</div>
</body>
</html>
