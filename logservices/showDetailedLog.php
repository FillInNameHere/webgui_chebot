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
if (isset($_POST["currentStepIndex"])) $currentStepIndex = $_POST["currentStepIndex"];
else $currentStepIndex = 0;
$gameLog = $glREPO->findGameLog($gameLogId);
$gameSteps = $gsREPO->findAllGameSteps($gameLogId);
$currentStep = $gameSteps[$currentStepIndex];

$paramSet = false;
$paramName = 'all';
foreach ($states as $name => $value) {
    if (isset($_POST[$name])) {
        $paramSet = true;
        $paramName = $name;
    }
}
if ($paramName === "first") {
    $currentStepIndex = 0;
} elseif ($paramName === "last") {
    $currentStepIndex = count($gameSteps) - 1;
} elseif ($paramName === "next") {
    if ($currentStepIndex >= count($gameSteps) - 1) {
        $currentStepIndex = 0;
    } else {
        $currentStepIndex++;
    }
} elseif ($paramName === "previous") {
    if ($currentStepIndex <= 0) {
        $currentStepIndex = count($gameSteps) - 1;
    } else {
        $currentStepIndex--;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <Title>Vindinium Project - Detailed Log</Title>
    <!--<style>
        A:link {
            text-decoration: none;
            color: #72c9dc;
        }

        A:visited {
            text-decoration: none;
            color: #72c9dc;
        }

        A:active {
            text-decoration: none;
            color: #72c9dc;
        }

        A:hover {
            text-decoration: none;
            color: #72c9dc;
        }

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
    </style>-->
    <style>
        table, th, td {
            border: 1px solid #666;
            border-collapse: collapse;
        }

        table {
            background-color: white;
        }

        th, td {
            color: black;
            padding: 5px;
        }
    </style>
</head>
<body bgcolor="white">
<!--<body bgcolor="#383b32">-->
<h2><span style="">Gamelink: </span><a href="<?php echo $gameLog->getGameURL(); ?>"
                                                   target="_blank"><?php echo $gameLog->getGameURL(); ?></a></h2>
<div style="float: right;">
    <iframe src="<?php echo $gameLog->getGameURL(); ?>"
            style="width: 1000px; height: 920px; border: none;"></iframe>
    <div style="border: #27a598">
        <h1>Information</h1>
        <p><?php
            echo "The game was ";
            if ($gameLog->getWin() == 1) echo "won.<br />";
            else echo "lost.<br />";
            echo "The game did ";
            if ($gameLog->getCrashed() == 1) echo "not ";
            echo "end normally. ";
            if ($gameLog->getCrashed() == 1) echo "the last message was " . $gameLog->getEndMessage();
            ?></p>
        <p><?php ?></p>
    </div>

</div>
<div id="data_form">
    <h2><span style="">Choose:</span></h2>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <?php
        foreach ($states as $name => $value) {
            ?>
            <input type="submit" value="<?php echo $value; ?>" name="<?php echo $name; ?>"/>
            <input type="hidden" name="gameLogId" value="<?php echo $gameLogId; ?>"/>
            <input type="hidden" name="currentStepIndex" value="<?php echo $currentStepIndex; ?>"/>
            <?php
        }
        ?>
        <span></span>
    </form>
</div>

<div>
    <?php
    if ($paramName === 'all') {
        ?>
        <div style="float: left;">
            <table style="width: 100%">
                <tr>
                    <th>ID</th>
                    <th>Turn</th>
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
                        <td><?php echo $gs->getTurn(); ?></td>
                        <td><?php
                            if ($gs->getBestActionThen() == 0) {echo "<img src=\"tavern.png\" height=\"16px\" width=\"12px\" />"; echo "Tavern";}
                            elseif ($gs->getBestActionThen() == 1) {echo "<img src=\"mine.png\" height=\"16px\" width=\"10px\" />"; echo "Mine";}
                            elseif ($gs->getBestActionThen() == 2) {echo "<img src=\"fight.png\" height=\"16px\" width=\"16px\" />"; echo "Fight";}
                            else echo "Continue"; ?></td>
                        <td><?php if ($gs->getChosenAction() == 0) {echo "<img src=\"tavern.png\" height=\"16px\" width=\"12px\" />"; echo "Tavern";}
                            elseif ($gs->getChosenAction() == 1) {echo "<img src=\"mine.png\" height=\"16px\" width=\"10px\" />"; echo "Mine";}
                            elseif ($gs->getChosenAction() == 2) {echo "<img src=\"fight.png\" height=\"16px\" width=\"16px\" />"; echo "Fight";}
                            else echo "Continue"; ?></td>
                        <td><?php echo $gs->getOldQval() ?></td>
                        <td><?php echo $gs->getNewQval() ?></td>
                        <td><?php echo $gs->getReward() ?></td>
                        <td><?php echo $gs->getStateStateId() ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <?php
    }
    if ($paramName != "all") {
        $currentStep = $gameSteps[$currentStepIndex];
        ?>
        <div style="float: left;">
            <table style="width: 100%">
                <tr>
                    <th>ID</th>
                    <th>Turn</th>
                    <th>Best Action</th>
                    <th>Chosen Action</th>
                    <th>Old Q-Value</th>
                    <th>New Q-Value</th>
                    <th>Reward</th>
                    <th>State</th>
                </tr>
                <tr>
                    <td><?php echo $currentStep->getGameStepId(); ?></td>
                    <td><?php echo $currentStep->getTurn(); ?></td>
                    <td><?php
                        if ($currentStep->getBestActionThen() == 0) {echo "<img src=\"tavern.png\" height=\"16px\" width=\"12px\" />"; echo "Tavern";}
                        elseif ($currentStep->getBestActionThen() == 1) {echo "<img src=\"mine.png\" height=\"16px\" width=\"10px\" />"; echo "Mine";}
                        elseif ($currentStep->getBestActionThen() == 2) {echo "<img src=\"fight.png\" height=\"16px\" width=\"16px\" />"; echo "Fight";}
                        else echo "Continue"; ?></td>
                    <td><?php if ($currentStep->getChosenAction() == 0) {echo "<img src=\"tavern.png\" height=\"16px\" width=\"12px\" />"; echo "Tavern";}
                        elseif ($currentStep->getChosenAction() == 1) {echo "<img src=\"mine.png\" height=\"16px\" width=\"10px\" />"; echo "Mine";}
                        elseif ($currentStep->getChosenAction() == 2) {echo "<img src=\"fight.png\" height=\"16px\" width=\"16px\" />"; echo "Fight";}
                        else echo "Continue"; ?></td>
                    <td><?php echo $currentStep->getOldQval() ?></td>
                    <td><?php echo $currentStep->getNewQval() ?></td>
                    <td><?php echo $currentStep->getReward() ?></td>
                    <td><?php echo $currentStep->getStateStateId() ?></td>
                </tr>
            </table>
        </div>
        <?php if ($currentStep->getChosenAction() != 3) { ?>
            <div style="float: left;">
                <table style="width: 100%">
                    <tr>
                        <th>Reward</th>
                        <th>Explanation</th>
                        <th>State-Part</th>
                        <th>Description</th>
                    </tr>
                    <?php
                    $reward = $currentStep->getReward();
                    $state = $currentStep->getStateStateId();
                    $chosenAction = $currentStep->getChosenAction();
                    $action = "error";
                    if ($chosenAction == 0) $action = "going to the next tavern";
                    if ($chosenAction == 1) $action = "going to the next mine";
                    if ($chosenAction == 2) $action = "engaging the closest enemy";

                    $timeRangeFactor = 0.125;
                    $calcReward = 0;
                    $rewardKat1 = -200;
                    $rewardKat2 = -75;
                    $rewardKat3 = -25;
                    $rewardKat4 = -2;
                    $rewardKat5 = 13;
                    $rewardKat6 = 50;
                    $rewardKat7 = 75;

                    $place = intval(substr($state, 0, 1));
                    $ownLife = intval(substr($state, 1, 1));
                    $ownMines = intval(substr($state, 2, 1));
                    $distanceBiggerFour = intval(substr($state, 3, 1));
                    $enemyMines = intval(substr($state, 4, 1));
                    $enemyLife = intval(substr($state, 5, 1));
                    $timeRange = intval(substr($state, 6, 1));
                    $minTwoGold = intval(substr($state, 7, 1));


                    // Kategorie 1 (-200)
                    if ($chosenAction == 0 && $minTwoGold == 0) {
                        $calcReward += $rewardKat1;
                        echo "<tr>
                        <td>$rewardKat1</td>
                        <td>For $action and less than 2 Gold</td>
                        <td>$place$ownLife$ownMines$distanceBiggerFour$enemyMines$enemyLife$timeRange<mark>$minTwoGold</mark></td>
                        <td>Bot has more than 2 gold</td>
                      </tr>";
                    }

                    if ($chosenAction == 0 && $ownLife == 9) {
                        $calcReward += $rewardKat1;
                        echo "<tr>
                        <td>$rewardKat1</td>
                        <td>For $action with " . (1 + $ownLife) * 10 . " health</td>
                        <td>$place<mark>$ownLife</mark>$ownMines$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</td>
                        <td>Own health</td>
                      </tr>";
                    }

                    if ($chosenAction == 1 && $ownLife <= 1) {
                        $calcReward += $rewardKat1;
                        echo "<tr>
                        <td>$rewardKat1</td>
                        <td>For $action with " . (1 + $ownLife) * 10 . " health</td>
                        <td>$place<mark>$ownLife</mark>$ownMines$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</td>
                        <td>Own health</td>
                      </tr>";
                    }

                    if ($chosenAction == 2 && $enemyMines == 0) {
                        $calcReward += $rewardKat1;
                        echo "<tr>
                        <td>$rewardKat1</td>
                        <td>For $action while closest enemy has no mines</td>
                        <td>$place$ownLife$ownMines$distanceBiggerFour<mark>$enemyMines</mark>$enemyLife$timeRange$minTwoGold</td>
                        <td>Closest enemy's mines</td>
                      </tr>";
                    }

                    if ($chosenAction == 2 && $distanceBiggerFour == 1) {
                        $calcReward += $rewardKat1;
                        echo "<tr>
                        <td>$rewardKat1</td>
                        <td>For $action while enemy is far away</td>
                        <td>$place$ownLife$ownMines<mark>$distanceBiggerFour</mark>$enemyMines$enemyLife$timeRange$minTwoGold</td>
                        <td>Enemy is further away than 4 cells</td>
                      </tr>";
                    }


                    // Kategorie 2 (-75)
                    if ($chosenAction == 0 && $ownLife >= 7 && $ownLife <= 8) {
                        $calcReward += $rewardKat2;
                        echo "<tr>
                        <td>$rewardKat2</td>
                        <td>For $action with " . (1 + $ownLife) * 10 . " health</td>
                        <td>$place<mark>$ownLife</mark>$ownMines$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</td>
                        <td>Own health</td>
                      </tr>";
                    }

                    if ($chosenAction == 1 && $ownLife == 2) {
                        $calcReward += $rewardKat2;
                        echo "<tr>
                        <td>$rewardKat2</td>
                        <td>For $action with " . (1 + $ownLife) * 10 . " health</td>
                        <td>$place<mark>$ownLife</mark>$ownMines$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</td>
                        <td>Own health</td>
                      </tr>";
                    }

                    if ($chosenAction == 2 && $ownLife <= 1) {
                        $calcReward += $rewardKat2;
                        echo "<tr>
                        <td>$rewardKat2</td>
                        <td>For $action with " . (1 + $ownLife) * 10 . " health</td>
                        <td>$place<mark>$ownLife</mark>$ownMines$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</td>
                        <td>Own health</td>
                      </tr>";
                    }

                    if ($place >= 3 && $place <= 4) {
                        $calcReward += $rewardKat2;
                        echo "<tr>
                        <td>$rewardKat2</td>
                        <td>For being $place.</td>
                        <td><mark>$place</mark>$ownLife$ownMines$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</td>
                        <td>Own bot ranking</td>
                      </tr>";
                    }


                    // Kategorie 3 (-25)
                    if ($chosenAction == 0 && $ownLife == 6) {
                        $calcReward += $rewardKat3;
                        echo "<tr>
                        <td>$rewardKat3</td>
                        <td>For $action with " . (1 + $ownLife) * 10 . " health</td>
                        <td>$place<mark>$ownLife</mark>$ownMines$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</td>
                        <td>Own health</td>
                      </tr>";
                    }

                    if ($chosenAction == 2 && $ownMines == 3) {
                        $calcReward += $rewardKat3;
                        echo "<tr>
                        <td>$rewardKat3</td>
                        <td>$place$ownLife<mark>$ownMines</mark>$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</td>
                        <td>Bot has >=8 mines</td>
                      </tr>";
                    }

                    if ($chosenAction == 2 && $enemyMines == 1) {
                        $calcReward += $rewardKat3;
                        echo "<tr>
                        <td>$rewardKat3</td>
                        <td>For $action while enemy has $enemyMines</td>
                        <td>$place$ownLife$ownMines$distanceBiggerFour<mark>$enemyMines</mark>$enemyLife$timeRange$minTwoGold</td>
                        <td>Enemy has 1-3 mines</td>
                      </tr>";
                    }

                    if ($chosenAction == 2 && $enemyLife > $ownLife) {
                        $calcReward += $rewardKat3;
                        echo "<tr>
                        <td>$rewardKat3</td>
                        <td>For $action with " . (1 + $ownLife) * 10 . " health while enemy has " . (1 + $enemyLife) * 10 . "</td>
                        <td>$place<mark>$ownLife</mark>$ownMines$distanceBiggerFour$enemyMines<mark>$enemyLife</mark>$timeRange$minTwoGold</td>
                        <td>1. Own health, 2. Closest enemy's health</td>
                      </tr>";
                    }

                    if ($place == 2) {
                        $calcReward += $rewardKat3;
                        echo "<tr>
                        <td>$rewardKat3</td>
                        <td>For being Second</td>
                        <td><mark>$place</mark>$ownLife$ownMines$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</td>
                        <td>Own rank</td>
                      </tr>";
                    }


                    // Kategorie 4 (-2)
                    if ($chosenAction == 0 && $ownLife == 5) {
                        $calcReward += $rewardKat4;
                        echo "<tr>
                        <td>$rewardKat4</td>
                        <td>For $action with " . (1 + $ownLife) * 10 . " health</td>
                        <td>$place<mark>$ownLife</mark>$ownMines$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</td>
                        <td>" . (1 + $ownLife) * 10 . " health</td>
                      </tr>";
                    }

                    if ($chosenAction == 2 && $ownMines == 2) {
                        $calcReward += $rewardKat4;
                        echo "<tr>
                        <td>$rewardKat4</td>
                        <td>For $action with 4-7 owned mines</td>
                        <td>$place$ownLife<mark>$ownMines</mark>$distanceBiggerFour$enemyMines<mark>$enemyLife</mark>$timeRange$minTwoGold</td>
                        <td>Bot's minecount (Abstract)</td>
                      </tr>";
                    }

                    if ($chosenAction == 2 && $enemyLife == $ownLife) {
                        $calcReward += $rewardKat4;
                        echo "<tr>
                        <td>$rewardKat4</td>
                        <td>For $action with " . (1 + $ownLife) * 10 . " health while enemy has (1+$enemyLife)*10</td>
                        <td>$place<mark>$ownLife</mark>$ownMines$distanceBiggerFour$enemyMines<mark>$enemyLife</mark>$timeRange$minTwoGold</td>
                        <td>1. Own health, 2. Closest enemy's health</td>
                      </tr>";
                    }


                    // Kategorie 5 (+13)
                    if ($chosenAction == 0 && $ownLife >= 3 && $ownLife <= 4) {
                        $calcReward += $rewardKat5;
                        echo "<tr>
                        <td>$rewardKat5</td>
                        <td>For $action with " . (1 + $ownLife) * 10 . " health</td>
                        <td>$place<mark>$ownLife</mark>$ownMines$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</td>
                        <td>Own health</td>
                      </tr>";
                    }

                    if ($chosenAction == 2 && $ownMines <= 1) {
                        $calcReward += $rewardKat5;
                        echo "<tr>
                        <td>$rewardKat5</td> 
                        <td>For $action with 0-3 Mines</td>
                        <td>$place$ownLife<mark>$ownMines</mark>$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</td>
                        <td>Own minecount (Abstract)</td>
                      </tr>";
                    }

                    if ($chosenAction == 2 && $distanceBiggerFour == 0) {
                        $calcReward += $rewardKat5;
                        echo "<tr>
                        <td>$rewardKat5</td>
                        <td>For $action while closest enemy is closer than 4 cells</td>
                        <td>$place$ownLife$ownMines<mark>$distanceBiggerFour</mark>$enemyMines$enemyLife$timeRange$minTwoGold</td>
                        <td>Closest enemy distance > 4 cells</td>
                      </tr>";
                    }

                    if ($chosenAction == 2 && $enemyMines >= 2) {
                        $calcReward += $rewardKat5;
                        echo "<tr>
                        <td>$rewardKat5</td>
                        <td>For $action while enemy has >3 mines</td>
                        <td>$place$ownLife$ownMines$distanceBiggerFour<mark>$enemyMines</mark>$enemyLife$timeRange$minTwoGold</td>
                        <td>Closest enemys minecount (Abstract)</td>
                      </tr>";
                    }

                    if ($chosenAction == 2 && $enemyLife < $ownLife) {
                        $calcReward += $rewardKat5;
                        echo "<tr>
                        <td>$rewardKat5</td>
                        <td>For $action with " . (1 + $ownLife) * 10 . " health while enemy has " . (1 + $enemyLife) * 10 . "</td>
                        <td>$place<mark>$ownLife</mark>$ownMines$distanceBiggerFour$enemyMines<mark>$enemyLife</mark>$timeRange$minTwoGold</td>
                        <td>1. Own health, 2. Closest enemy's health</td>
                      </tr>";
                    }


                    // Kategorie 6 (+50)
                    if ($chosenAction == 0 && $ownLife <= 2) {
                        $calcReward += $rewardKat6;
                        echo "<tr>
                        <td>$rewardKat6</td>
                        <td>For $action with " . (1 + $ownLife) * 10 . " health</td>
                        <td>$place<mark>$ownLife</mark>$ownMines$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</td>
                        <td>Own health</td>
                      </tr>";
                    }

                    if ($chosenAction == 1 && $ownLife >= 3) {
                        $calcReward += $rewardKat6;
                        echo "<tr>
                        <td>$rewardKat6</td>
                        <td>For $action with " . (1 + $ownLife) * 10 . " health</td>
                        <td>$place<mark>$ownLife</mark>$ownMines$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</td>
                        <td>Own health</td>
                      </tr>";
                    }


                    // Kategorie 7 (+75)
                    if ($place == 1) {
                        $calcReward += $rewardKat7;
                        echo "<tr>
                        <td>$rewardKat7</td>
                        <td>For being first</td>
                        <td><mark>$place</mark>$ownLife$ownMines$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</td>
                        <td>Own rank</td>
                      </tr>";
                    }

                    // timeRangeFaktor
                    $calcReward2 = intval(($calcReward * (1 + ($timeRange * $timeRangeFactor))));

                    echo "<tr>
                        <td>$calcReward</td>
                        <td>Total reward</td>
                        <td>$place$ownLife$ownMines$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</td>
                        <td></td>
                      </tr>";
                    ?>
                </table>
            </div>
            <?php
        }
    }
    ?>
</div>
</body>
</html>
