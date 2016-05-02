<?php
/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 01.05.2016
 * Time: 13:27
 */

namespace vindinium\logservices\models;
/**
 * @Entity @Table(name = "GameStep")
 */
Class GameStep
{
    /**
     * @Column(type="integer") @Id @GeneratedValue(strategy="IDENTITY")
     */
    protected $gameStepId;

    /**
     * @Column(type="integer")
     */
    protected $gameLog_gameLogId;
    /**
     * @Column(type="integer")
     */
    protected $bestActionThen;
    /**
     * @Column(type="integer")
     */
    protected $chosenAction;
    /**
     * @Column(type="integer")
     */
    protected $reward;
    /**
     * @Column(type="integer")
     */
    protected $state_stateId;

    /**
     * @Column(type="float")
     */
    protected $newQval;
    
    /**
     * @Column(type="float")
     */
    protected $oldQval;

    /**
     * @return mixed
     */
    public function getGameStepId()
    {
        return $this->gameStepId;
    }

    /**
     * @return mixed
     */
    public function getBestActionThen()
    {
        return $this->bestActionThen;
    }

    /**
     * @param mixed $bestActionThen
     */
    public function setBestActionThen($bestActionThen)
    {
        $this->bestActionThen = $bestActionThen;
    }

    /**
     * @return mixed
     */
    public function getChosenAction()
    {
        return $this->chosenAction;
    }

    /**
     * @param mixed $chosenAction
     */
    public function setChosenAction($chosenAction)
    {
        $this->chosenAction = $chosenAction;
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
    public function getStateStateId()
    {
        return $this->state_stateId;
    }

    /**
     * @param mixed $state_stateId
     */
    public function setStateStateId($state_stateId)
    {
        $this->state_stateId = $state_stateId;
    }

    /**
     * @return mixed
     */
    public function getNewQval()
    {
        return $this->newQval;
    }

    /**
     * @param mixed $newQval
     */
    public function setNewQval($newQval)
    {
        $this->newQval = $newQval;
    }

    /**
     * @return mixed
     */
    public function getOldQval()
    {
        return $this->oldQval;
    }

    /**
     * @param mixed $oldQval
     */
    public function setOldQval($oldQval)
    {
        $this->oldQval = $oldQval;
    }

    /**
     * @return mixed
     */
    public function getGameLogGameLogId()
    {
        return $this->gameLog_gameLogId;
    }

    /**
     * @param mixed $gameLog_gameLogId
     */
    public function setGameLogGameLogId($gameLog_gameLogId)
    {
        $this->gameLog_gameLogId = $gameLog_gameLogId;
    }

}

?>