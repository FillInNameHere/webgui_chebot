<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 08.05.2016
 * Time: 19:06
 */
require_once __DIR__ . '/../sessionservices/SessionFactory.php';
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
<html>
    <head>
        <title>Vindinium Project - Config</title>
    </head>
    <body>
        <h2>Configuration:</h2>
        <span></span>
        <form method="post">
            <?php foreach ($vars as $key => $value) { ?>
            <label><?php echo $key; ?></label>
            <input type="text" name="<?php echo $key; ?>" value="<?php echo $value; ?>"/><br />
            <?php } ?>
            <input type="submit" name="submit" value="Send" />
        </form>
    </body>
</html>