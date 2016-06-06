<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 08.05.2016
 * Time: 19:37
 */

namespace vindinium\config\repo;

require_once __DIR__ . '/../models/Vars.php';

use vindinium\config\models\Vars;


class VarRepo
{
    //private $path = __DIR__ . "/file/vars.vfv";
    private $path = '/home/crambow/projects/projects/checlient2/vindinium-client-1.0.0/target/file/vars.vfv';

    public function getVars() {
        $fileString = file_get_contents($this->path);
        $mixedJSON = json_decode($fileString);
        $vars = new Vars();
        return $vars->deserialize($mixedJSON);
    }

    public function setVars($vars) {
        $jsonString = json_encode($vars);
        file_put_contents($this->path, $jsonString);
    }
}