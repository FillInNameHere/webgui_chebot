<?php
require_once __DIR__ . '../../models/GameLog.php';
require_once __DIR__ . '../../repo/GameLogRepo.php';
require_once __DIR__ . '../../repo/GameStepRepo.php';
require_once __DIR__ . '../../../sessionservices/SessionFactory.php';

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
    <title>Vindinium Projekt - CHEBot 1 - Detailed Log - GameID: <?php echo $gameLogId ?></title>
</head>
<body bgcolor="#383b32">
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
				<a class="navbar-brand" href="../chebot1/">CHEBot 1</a>
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

	<!-- Modale -->
    <?php foreach ($gameSteps as $gs) { ?>        
	<div class="modal" id="modal-log-id-<?php echo $gs->getGameStepId(); ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel-log">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myLargeModalLabel-log">ID: <?php echo $gs->getGameStepId(); ?></h4>
				</div>
				<div class="modal-body">
			<div class="table-responsive">
                        <table class="table table-bordered">
			    <tr>
                                <th>Explanation</th>
                                <th>Reward</th>
                                <th>State-Part</th>
                                <th>Description</th>
                            </tr>
                            <?php
                            $reward = $gs->getReward();
                            $state = $gs->getStateStateId();
                            $chosenAction = $gs->getChosenAction();
                            $action = "error";
                            if ($chosenAction == 1) $action = "going to the next tavern";
                            if ($chosenAction == 2) $action = "going to the next mine";
                            if ($chosenAction == 3) $action = "engaging the closest enemy";

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
                            if ($chosenAction == 1 && $minTwoGold == 0) {
                                $calcReward += $rewardKat1;
                                echo "<tr>
				<td>For $action and less than 2 Gold</td>
                                <td>$rewardKat1</td>
                                <td>$place$ownLife$ownMines$distanceBiggerFour$enemyMines$enemyLife$timeRange<mark>$minTwoGold</mark></td>
                                <td>Bot has more than 2 gold</td>
                              </tr>";
                            }

                            if ($chosenAction == 1 && $ownLife == 9) {
                                $calcReward += $rewardKat1;
                                echo "<tr>
				<td>For $action with " . (1 + $ownLife) * 10 . " health</td>
                                <td>$rewardKat1</td>
                                <td>$place<mark>$ownLife</mark>$ownMines$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</td>
                                <td>Own health</td>
                              </tr>";
                            }

                            if ($chosenAction == 2 && $ownLife <= 1) {
                                $calcReward += $rewardKat1;
                                echo "<tr>
				<td>For $action with " . (1 + $ownLife) * 10 . " health</td>
                                <td>$rewardKat1</td>
                                <td>$place<mark>$ownLife</mark>$ownMines$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</td>
                                <td>Own health</td>
                              </tr>";
                            }

                            if ($chosenAction == 3 && $enemyMines == 0) {
                                $calcReward += $rewardKat1;
                                echo "<tr>
				<td>For $action while closest enemy has no mines</td>
                                <td>$rewardKat1</td>
                                <td>$place$ownLife$ownMines$distanceBiggerFour<mark>$enemyMines</mark>$enemyLife$timeRange$minTwoGold</td>
                                <td>Closest enemy's mines</td>
                              </tr>";
                            }

                            if ($chosenAction == 3 && $distanceBiggerFour == 1) {
                                $calcReward += $rewardKat1;
                                echo "<tr>
				<td>For $action while enemy is far away</td>
                                <td>$rewardKat1</td>
                                <td>$place$ownLife$ownMines<mark>$distanceBiggerFour</mark>$enemyMines$enemyLife$timeRange$minTwoGold</td>
                                <td>Enemy is further away than 4 cells</td>
                              </tr>";
                            }


                            // Kategorie 2 (-75)
                            if ($chosenAction == 1 && $ownLife >= 7 && $ownLife <= 8) {
                                $calcReward += $rewardKat2;
                                echo "<tr>
				<td>For $action with " . (1 + $ownLife) * 10 . " health</td>
                                <td>$rewardKat2</td>
                                <td>$place<mark>$ownLife</mark>$ownMines$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</td>
                                <td>Own health</td>
                              </tr>";
                            }

                            if ($chosenAction == 2 && $ownLife == 2) {
                                $calcReward += $rewardKat2;
                                echo "<tr>
				<td>For $action with " . (1 + $ownLife) * 10 . " health</td>
                                <td>$rewardKat2</td>
                                <td>$place<mark>$ownLife</mark>$ownMines$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</td>
                                <td>Own health</td>
                              </tr>";
                            }

                            if ($chosenAction == 3 && $ownLife <= 1) {
                                $calcReward += $rewardKat2;
                                echo "<tr>
				<td>For $action with " . (1 + $ownLife) * 10 . " health</td>
                                <td>$rewardKat2</td>
                                <td>$place<mark>$ownLife</mark>$ownMines$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</td>
                                <td>Own health</td>
                              </tr>";
                            }

                            if ($place >= 3 && $place <= 4) {
                                $calcReward += $rewardKat2;
                                echo "<tr>
				<td>For being $place.</td>
                                <td>$rewardKat2</td>
                                <td><mark>$place</mark>$ownLife$ownMines$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</td>
                                <td>Own bot ranking</td>
                              </tr>";
                            }


                            // Kategorie 3 (-25)
                            if ($chosenAction == 1 && $ownLife == 6) {
                                $calcReward += $rewardKat3;
                                echo "<tr>
				<td>For $action with " . (1 + $ownLife) * 10 . " health</td>
                                <td>$rewardKat3</td>
                                <td>$place<mark>$ownLife</mark>$ownMines$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</td>
                                <td>Own health</td>
                              </tr>";
                            }

                            if ($chosenAction == 3 && $ownMines == 3) {
                                $calcReward += $rewardKat3;
                                echo "<tr>
				<td>For $action with $ownMines mines</td>
                                <td>$rewardKat3</td>
                                <td>$place$ownLife<mark>$ownMines</mark>$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</td>
                                <td>Bot has >=8 mines</td>
                              </tr>";
                            }

                            if ($chosenAction == 3 && $enemyMines == 1) {
                                $calcReward += $rewardKat3;
                                echo "<tr>
				<td>For $action while enemy has $enemyMines</td>
                                <td>$rewardKat3</td>
                                <td>$place$ownLife$ownMines$distanceBiggerFour<mark>$enemyMines</mark>$enemyLife$timeRange$minTwoGold</td>
                                <td>Enemy has 1-3 mines</td>
                              </tr>";
                            }

                            if ($chosenAction == 3 && $enemyLife > $ownLife) {
                                $calcReward += $rewardKat3;
                                echo "<tr>
				<td>For $action with " . (1 + $ownLife) * 10 . " health while enemy has " . (1 + $enemyLife) * 10 . "</td>
                                <td>$rewardKat3</td>
                                <td>$place<mark>$ownLife</mark>$ownMines$distanceBiggerFour$enemyMines<mark>$enemyLife</mark>$timeRange$minTwoGold</td>
                                <td>1. Own health, 2. Closest enemy's health</td>
                              </tr>";
                            }

                            if ($place == 2) {
                                $calcReward += $rewardKat3;
                                echo "<tr>
				<td>For being Second</td>
                                <td>$rewardKat3</td>
                                <td><mark>$place</mark>$ownLife$ownMines$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</td>
                                <td>Own rank</td>
                              </tr>";
                            }


                            // Kategorie 4 (-2)
                            if ($chosenAction == 1 && $ownLife == 5) {
                                $calcReward += $rewardKat4;
                                echo "<tr>
				<td>For $action with " . (1 + $ownLife) * 10 . " health</td>
                                <td>$rewardKat4</td>
                                <td>$place<mark>$ownLife</mark>$ownMines$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</td>
                                <td>" . (1 + $ownLife) * 10 . " health</td>
                              </tr>";
                            }

                            if ($chosenAction == 3 && $ownMines == 2) {
                                $calcReward += $rewardKat4;
                                echo "<tr>
				<td>For $action with 4-7 owned mines</td>
                                <td>$rewardKat4</td>
                                <td>$place$ownLife<mark>$ownMines</mark>$distanceBiggerFour$enemyMines<mark>$enemyLife</mark>$timeRange$minTwoGold</td>
                                <td>Bot's minecount (Abstract)</td>
                              </tr>";
                            }

                            if ($chosenAction == 3 && $enemyLife == $ownLife) {
                                $calcReward += $rewardKat4;
                                echo "<tr>
				<td>For $action with " . (1 + $ownLife) * 10 . " health while enemy has " . (1+$enemyLife)*10 . "</td>
                                <td>$rewardKat4</td>
                                <td>$place<mark>$ownLife</mark>$ownMines$distanceBiggerFour$enemyMines<mark>$enemyLife</mark>$timeRange$minTwoGold</td>
                                <td>1. Own health, 2. Closest enemy's health</td>
                              </tr>";
                            }


                            // Kategorie 5 (+13)
                            if ($chosenAction == 1 && $ownLife >= 3 && $ownLife <= 4) {
                                $calcReward += $rewardKat5;
                                echo "<tr>
				<td>For $action with " . (1 + $ownLife) * 10 . " health</td>
                                <td>$rewardKat5</td>
                                <td>$place<mark>$ownLife</mark>$ownMines$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</td>
                                <td>Own health</td>
                              </tr>";
                            }

                            if ($chosenAction == 3 && $ownMines <= 1) {
                                $calcReward += $rewardKat5;
                                echo "<tr>
				<td>For $action with 0-3 Mines</td>
                                <td>$rewardKat5</td>
                                <td>$place$ownLife<mark>$ownMines</mark>$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</td>
                                <td>Own minecount (Abstract)</td>
                              </tr>";
                            }

                            if ($chosenAction == 3 && $distanceBiggerFour == 0) {
                                $calcReward += $rewardKat5;
                                echo "<tr>
				<td>For $action while closest enemy is closer than 4 cells</td>
                                <td>$rewardKat5</td>
                                <td>$place$ownLife$ownMines<mark>$distanceBiggerFour</mark>$enemyMines$enemyLife$timeRange$minTwoGold</td>
                                <td>Closest enemy distance > 4 cells</td>
                              </tr>";
                            }

                            if ($chosenAction == 3 && $enemyMines >= 2) {
                                $calcReward += $rewardKat5;
                                echo "<tr>
				<td>For $action while enemy has >3 mines</td>
                                <td>$rewardKat5</td>
                                <td>$place$ownLife$ownMines$distanceBiggerFour<mark>$enemyMines</mark>$enemyLife$timeRange$minTwoGold</td>
                                <td>Closest enemys minecount (Abstract)</td>
                              </tr>";
                            }

                            if ($chosenAction == 3 && $enemyLife < $ownLife) {
                                $calcReward += $rewardKat5;
                                echo "<tr>
				<td>For $action with " . (1 + $ownLife) * 10 . " health while enemy has " . (1 + $enemyLife) * 10 . "</td>
                                <td>$rewardKat5</td>
                                <td>$place<mark>$ownLife</mark>$ownMines$distanceBiggerFour$enemyMines<mark>$enemyLife</mark>$timeRange$minTwoGold</td>
                                <td>1. Own health, 2. Closest enemy's health</td>
                              </tr>";
                            }


                            // Kategorie 6 (+50)
                            if ($chosenAction == 1 && $ownLife <= 2) {
                                $calcReward += $rewardKat6;
                                echo "<tr>
				<td>For $action with " . (1 + $ownLife) * 10 . " health</td>
                                <td>$rewardKat6</td>
                                <td>$place<mark>$ownLife</mark>$ownMines$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</td>
                                <td>Own health</td>
                              </tr>";
                            }

                            if ($chosenAction == 2 && $ownLife >= 3) {
                                $calcReward += $rewardKat6;
                                echo "<tr>
				<td>For $action with " . (1 + $ownLife) * 10 . " health</td>
                                <td>$rewardKat6</td>
                                <td>$place<mark>$ownLife</mark>$ownMines$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</td>
                                <td>Own health</td>
                              </tr>";
                            }


                            // Kategorie 7 (+75)
                            if ($place == 1) {
                                $calcReward += $rewardKat7;
                                echo "<tr>
				<td>For being first</td>
                                <td>$rewardKat7</td>
                                <td><mark>$place</mark>$ownLife$ownMines$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</td>
                                <td>Own rank</td>
                              </tr>";
                            }

			    if($timeRange == 0){
				$trf0 = 1 + $timeRange * $timeRangeFactor; 
				echo "<tr>
				<td>TimeRangeFactor</td>
                                <td>* <mark>"; echo $trf0; echo"</mark></td>
                                <td>$place$ownLife$ownMines$distanceBiggerFour$enemyMines$enemyLife<mark>$timeRange</mark>$minTwoGold</td>
                                <td>TimeRangeFaktor</td>
                              </tr>";
			    }

			    if($timeRange == 1){
				$trf1 = 1 + $timeRange * $timeRangeFactor; 
				echo "<tr>
				<td>TimeRangeFactor</td>
                                <td>* <mark>"; echo $trf1; echo"</mark></td>
                                <td>$place$ownLife$ownMines$distanceBiggerFour$enemyMines$enemyLife<mark>$timeRange</mark>$minTwoGold</td>
                                <td>TimeRangeFaktor</td>
                              </tr>";
			    }

		            if($timeRange == 2){
				$trf2 = 1 + $timeRange * $timeRangeFactor; 
				echo "<tr>
				<td>TimeRangeFactor</td>
                                <td>* <mark>"; echo $trf2; echo"</mark></td>
                                <td>$place$ownLife$ownMines$distanceBiggerFour$enemyMines$enemyLife<mark>$timeRange</mark>$minTwoGold</td>
                                <td>TimeRangeFaktor</td>
                              </tr>";
			    }

                            if($timeRange == 3){
				$trf3 = 1 + $timeRange * $timeRangeFactor; 
				echo "<tr>
				<td>TimeRangeFactor</td>
                                <td>* <mark>"; echo $trf3; echo"</mark></td>
                                <td>$place$ownLife$ownMines$distanceBiggerFour$enemyMines$enemyLife<mark>$timeRange</mark>$minTwoGold</td>
                                <td>TimeRangeFaktor</td>
                              </tr>";
			    }

                            // timeRangeFaktor
                            $calcReward2 = intval(($calcReward * (1 + ($timeRange * $timeRangeFactor))));

                            echo "<tr>
				<td><b>Total reward</b></td>
                                <td><b>$calcReward2</b></td>
                                <td><b>$place$ownLife$ownMines$distanceBiggerFour$enemyMines$enemyLife$timeRange$minTwoGold</b></td>
                                <td><b></b></td>
                              </tr>";
                            ?>
                        </table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
	
	<div style="float: right; position: fixed; top: 25px; padding-left: 5px; right: 5px;">
		<iframe src="<?php echo $gameLog->getGameURL(); ?>" style="width: 1000px; height: 920px; border: none;"></iframe>
	</div>

	<div class="jumbotron" style="padding-left: 10px;">
		<h2><span style="">Gamelink: </span><a href="<?php echo $gameLog->getGameURL(); ?>" target="_blank"><?php echo $gameLog->getGameURL(); ?></a></h2>
		<p>
			<?php
			echo "The game was ";
			if ($gameLog->getWin() == 1) echo "won.<br />";
			else echo "lost.<br />";
			echo "The game did ";
			if ($gameLog->getCrashed() == 1) echo "not ";
			echo "end normally. ";
			if ($gameLog->getCrashed() == 1) echo "<br />The last message was " . $gameLog->getEndMessage();
			?>
		</p>
	</div>

    <div style="float: left; width: 47%;">
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead bgcolor="#fff">
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
                </thead>
                </tbody>
                </tr>
                <?php
                foreach ($gameSteps as $gs) { ?>
                    <tr data-toggle="modal" data-target="#modal-log-id-<?php echo $gs->getGameStepId(); ?>">
                        <td><?php echo $gs->getGameStepId(); ?></td>
                        <td><?php echo $gs->getTurn(); ?></td>
                        <?php
                        if ($gs->getBestActionThen() == 1) {echo "<td bgcolor=\"#ffea57\"><img src=\"../get/tavern.png\" height=\"16px\" width=\"12px\" />"; echo "&nbsp; Tavern";}
                        elseif ($gs->getBestActionThen() == 2) {echo "<td bgcolor=\"#c49441\"><img src=\"../get/mine.png\" height=\"16px\" width=\"8px\" />"; echo "&nbsp; Mine";}
                        elseif ($gs->getBestActionThen() == 3) {echo "<td bgcolor=\"#ff6b6a\"><img src=\"../get/fight.png\" height=\"16px\" width=\"16px\" />"; echo "&nbsp; Fight";}
                        else echo "<td>Continue"; ?></td>
                        <?php if ($gs->getChosenAction() == 1) {echo "<td bgcolor=\"#ffea57\"><img src=\"../get/tavern.png\" height=\"16px\" width=\"12px\" />"; echo "&nbsp; Tavern";}
                        elseif ($gs->getChosenAction() == 2) {echo "<td bgcolor=\"#c49441\"><img src=\"../get/mine.png\" height=\"16px\" width=\"8px\" />"; echo "&nbsp; Mine";}
                        elseif ($gs->getChosenAction() == 3) {echo "<td bgcolor=\"#ff6b6a\"><img src=\"../get/fight.png\" height=\"16px\" width=\"16px\" />"; echo "&nbsp; Fight";}
                        else echo "<td>Continue"; ?></td>
                        <td><?php echo $gs->getOldQval() ?></td>
                        <td><?php echo $gs->getNewQval() ?></td>
                        <td><?php echo $gs->getReward() ?></td>
                        <td><?php echo $gs->getStateStateId() ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        </div>
    </div>	
</body>
</html>