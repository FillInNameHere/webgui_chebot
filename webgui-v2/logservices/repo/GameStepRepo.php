<?php
/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 01.05.2016
 * Time: 15:37
 */

namespace vindinium\logservices\repos;

require_once __DIR__ . '/../models/GameStep.php';
require_once __DIR__ . '/../../persistenceservices/PersistenceFactory.php';

use vindinium\persistenceservices\PersistenceFactory;

class GameStepRepo
{
    private $entityManager;
    private $states = array(
        'all' => 'Get All',
        'first' => 'Get First',
        'previous' => 'Get Previous',
        'next' => 'Get Next',
        'last' => 'Get Last');

    /**
     * GameLogRepo constructor.
     * @param $entityManager
     */
    public function __construct()
    {
        $pf = new PersistenceFactory();
        $this->entityManager = $pf->getEm();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findGameStep($id)
    {
        $gameStep = $this->entityManager->find('vindinium\logservices\models\GameStep', $id);
        return $gameStep;
    }

    /**
     * @param $gameLogId
     * @return array
     */
    public function findAllGameSteps($gameLogId)
    {
        $a = array('gameLog_gameLogId' => $gameLogId);
        $gameSteps = $this->entityManager->getRepository('vindinium\logservices\models\GameStep')->findBy($a);
        return $gameSteps;
    }

    /**
     * @return array
     */
    public function getStates()
    {
        return $this->states;
    }
}