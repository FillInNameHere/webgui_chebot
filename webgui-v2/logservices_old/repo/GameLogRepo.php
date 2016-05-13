<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 26.04.2016
 * Time: 23:17
 */
namespace vindinium\logservices\repos;

require_once __DIR__ . '/../models/GameLog.php';
require_once __DIR__ . '/../../persistenceservices/PersistenceFactory.php';

use vindinium\persistenceservices\PersistenceFactory;

class GameLogRepo
{
    private $entityManager;
    private $states = array(
        'all' => 'Get All',
        'last' => 'Get Last 100',
        'win' => 'Get Winning Games',
        'lost' => 'Get Lost Games');
    
    /**
     * GameLogRepo constructor.
     * @param $entityManager
     */
    public function __construct()
    {
        $pf = new PersistenceFactory();
        $this->entityManager = $pf->getEm();
    }

    public function findGameLog($id)
    {
        $gameLog = $this->entityManager->find('vindinium\logservices\models\GameLog', $id);
        return $gameLog;
    }

    public function findAllGameLogs()
    {
        $gameLogs = $this->entityManager->getRepository('vindinium\logservices\models\GameLog')->findAll();
        return $gameLogs;
    }

    private function findByWin($status)
    {
        $a = array('win' => $status);
        $order = array('gameLogId' => 'DESC');
        $gameLogs = $this->entityManager->getRepository('vindinium\logservices\models\GameLog')->findBy($a, $order);
        return $gameLogs;
    }

    public function findByCrash($status)
    {
        $a = array('crashed' => $status);
        $gameLogs = $this->entityManager->getRepository('vindinium\logservices\models\GameLog')->findBy($a);
        return $gameLogs;
    }

    public function findWinningGameLogs()
    {
        return $this->findByWin(1);
    }

    public function findLostGameLogs()
    {
        return $this->findByWin(0);
    }

    private function findGameLogsWithOrderAndLimit($order, $limit)
    {
        $a = array('gameLogId' => $order);
        $gameLogs = $this->entityManager->getRepository('vindinium\logservices\models\GameLog')->findBy(array(), $a, $limit);
        return $gameLogs;
    }

    public function findLastDESCGameLogs($limit)
    {
        $gameLogs = null;
        if (is_int($limit) && $limit > 1)
        {
            $gameLogs = $this->findGameLogsWithOrderAndLimit('DESC', $limit);
        }
        return $gameLogs;
    }

    //BusinessLogic...
    public function getGLArray($key)
    {
        if ($key == 'all')
        {
            return $this->findAllGameLogs();
        }
        elseif ($key == 'last')
        {
            return $this->findLastDESCGameLogs(100);
        }
        elseif ($key == 'win')
        {
            return $this->findWinningGameLogs();
        }
        elseif ($key == 'lost')
        {
            return $this->findLostGameLogs();
        }
        else
        {
            return null;
        }
    }

    /**
     * @return array
     */
    public function getStates()
    {
        return $this->states;
    }
}