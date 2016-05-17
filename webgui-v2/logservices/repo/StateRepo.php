<?php
/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 17.05.2016
 * Time: 08:54
 */

namespace vindinium\logservices\repos;

require_once __DIR__ . '/../models/State.php';
require_once __DIR__ . '/../../persistenceservices/PersistenceFactory.php';

use vindinium\persistenceservices\PersistenceFactory;

class StateRepo
{
    private $entityManager;

    /**
     * GameLogRepo constructor.
     * @param $entityManager
     */
    public function __construct()
    {
        $pf = new PersistenceFactory();
        $this->entityManager = $pf->getEm();
    }

    public function findAllStates()
    {
        $States = $this->entityManager->getRepository('vindinium\logservices\models\State')->findAll();
        return $States;
    }
}