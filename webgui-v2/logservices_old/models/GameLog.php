<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 25.04.2016
 * Time: 13:17
 */

namespace vindinium\logservices\models;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity @Table(name = "GameLog")
 */
class GameLog
{
    /**
     * @Column(type="integer") @Id @GeneratedValue(strategy="IDENTITY")
     */
    protected $gameLogId;

    /**
     * @Column(type="string")
     */
    protected $gameURL;

    /**
     * @Column(type="integer")
     */
    protected $whoAmI;

    /**
     * @Column(type="integer")
     */
    protected $win;

    /**
     * @Column(type="integer")
     */
    protected $rounds;

    /**
     * @Column(type="string")
     */
    protected $startingTime;

    /**
     * @Column(type="integer")
     */
    protected $crashed;

    /**
     * @Column(type="integer")
     */
    protected $reward;

    /**
     * @Column(type="integer")
     */
    protected $biggestReward;

    /**
     * @Column(type="integer")
     */
    protected $smallestReward;

    /**
     * @Column(type="string")
     */
    protected $endMessage;


    protected $hero1;
    protected $hero2;
    protected $hero3;
    protected $hero4;
    protected $tavern;
    protected $totalMineCount;
    protected $deathByEnemy;
    protected $deathByMine;
    protected $kills;



    function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getGameLogId()
    {
        return $this->gameLogId;
    }

    /**
     * @param mixed $gameLogId
     */
    public function setGameLogId($gameLogId)
    {
        $this->gameLogId = $gameLogId;
    }

    /**
     * @return mixed
     */
    public function getGameURL()
    {
        return $this->gameURL;
    }

    /**
     * @param mixed $gameURL
     */
    public function setGameURL($gameURL)
    {
        $this->gameURL = $gameURL;
    }

    /**
     * @return mixed
     */
    public function getWhoAmI()
    {
        return $this->whoAmI;
    }

    /**
     * @param mixed $whoAmI
     */
    public function setWhoAmI($whoAmI)
    {
        $this->whoAmI = $whoAmI;
    }

    /**
     * @return mixed
     */
    public function getWin()
    {
        return $this->win;
    }

    /**
     * @param mixed $win
     */
    public function setWin($win)
    {
        $this->win = $win;
    }

    /**
     * @return mixed
     */
    public function getRounds()
    {
        return $this->rounds;
    }

    /**
     * @param mixed $rounds
     */
    public function setRounds($rounds)
    {
        $this->rounds = $rounds;
    }

    /**
     * @return mixed
     */
    public function getTavern()
    {
        return $this->tavern;
    }

    /**
     * @param mixed $tavern
     */
    public function setTavern($tavern)
    {
        $this->tavern = $tavern;
    }

    /**
     * @return mixed
     */
    public function getTotalMineCount()
    {
        return $this->totalMineCount;
    }

    /**
     * @param mixed $totalMineCount
     */
    public function setTotalMineCount($totalMineCount)
    {
        $this->totalMineCount = $totalMineCount;
    }

    /**
     * @return mixed
     */
    public function getDeathByEnemy()
    {
        return $this->deathByEnemy;
    }

    /**
     * @param mixed $deathByEnemy
     */
    public function setDeathByEnemy($deathByEnemy)
    {
        $this->deathByEnemy = $deathByEnemy;
    }

    /**
     * @return mixed
     */
    public function getDeathByMine()
    {
        return $this->deathByMine;
    }

    /**
     * @param mixed $deathByMine
     */
    public function setDeathByMine($deathByMine)
    {
        $this->deathByMine = $deathByMine;
    }

    /**
     * @return mixed
     */
    public function getKills()
    {
        return $this->kills;
    }

    /**
     * @param mixed $kills
     */
    public function setKills($kills)
    {
        $this->kills = $kills;
    }

    /**
     * @return mixed
     */
    public function getStartingTime()
    {
        return $this->startingTime;
    }

    /**
     * @param mixed $startingTime
     */
    public function setStartingTime($startingTime)
    {
        $this->startingTime = $startingTime;
    }

    /**
     * @return mixed
     */
    public function getCrashed()
    {
        return $this->crashed;
    }

    /**
     * @param mixed $crashed
     */
    public function setCrashed($crashed)
    {
        $this->crashed = $crashed;
    }


    /**
     * @return mixed
     */
    public function getHero1()
    {
        return $this->hero1;
    }

    /**
     * @param mixed $hero1
     */
    public function setHero1($hero1)
    {
        $this->hero1 = $hero1;
    }

    /**
     * @return mixed
     */
    public function getHero2()
    {
        return $this->hero2;
    }

    /**
     * @param mixed $hero2
     */
    public function setHero2($hero2)
    {
        $this->hero2 = $hero2;
    }

    /**
     * @return mixed
     */
    public function getHero3()
    {
        return $this->hero3;
    }

    /**
     * @param mixed $hero3
     */
    public function setHero3($hero3)
    {
        $this->hero3 = $hero3;
    }

    /**
     * @return mixed
     */
    public function getHero4()
    {
        return $this->hero4;
    }

    /**
     * @param mixed $hero4
     */
    public function setHero4($hero4)
    {
        $this->hero4 = $hero4;
    }

    /**
     * @return mixed
     */
    public function getReward()
    {
        return $this->reward;
    }

    /**
     * @param mixed $reward
     */
    public function setReward($reward)
    {
        $this->reward = $reward;
    }

    /**
     * @return mixed
     */
    public function getBiggestReward()
    {
        return $this->biggestReward;
    }

    /**
     * @param mixed $biggestReward
     */
    public function setBiggestReward($biggestReward)
    {
        $this->biggestReward = $biggestReward;
    }

    /**
     * @return mixed
     */
    public function getSmallestReward()
    {
        return $this->smallestReward;
    }

    /**
     * @param mixed $smallestReward
     */
    public function setSmallestReward($smallestReward)
    {
        $this->smallestReward = $smallestReward;
    }

    /**
     * @return mixed
     */
    public function getEndMessage()
    {
        return $this->endMessage;
    }

    /**
     * @param mixed $endMessage
     */
    public function setEndMessage($endMessage)
    {
        $this->endMessage = $endMessage;
    }
}