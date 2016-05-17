<?php

/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 08.05.2016
 * Time: 19:09
 */
namespace vindinium\config\models;

class Vars implements \JsonSerializable
{
    public $explorationFactor;
    public $learningRate;

    /**
     * Vars constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getExplorationFactor()
    {
        return $this->explorationFactor;
    }

    /**
     * @param mixed $explorationFactor
     */
    public function setExplorationFactor($explorationFactor)
    {
        $this->explorationFactor = $explorationFactor;
    }

    /**
     * @return mixed
     */
    public function getLearningRate()
    {
        return $this->learningRate;
    }

    /**
     * @param mixed $learningRate
     */
    public function setLearningRate($learningRate)
    {
        $this->learningRate = $learningRate;
    }

    public function refactor() {
        $this->explorationFactor = doubleval($this->explorationFactor);
        $this->learningRate = doubleval($this->learningRate);
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        $va = array();
        foreach ($this as $key => $value) {
            $va[$key] = $value;
        }
        return $va;
    }

    /**
     * @param $mixed
     */
    public function deserialize($mixed) {
        $res = new Vars();
        foreach ($mixed as $key => $value) {
            try {
                $res->$key = $value;
            } catch (\Exception $e) {
                return null;
            }
        }
        return $res;
    }
}