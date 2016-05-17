<?php
/**
 * Created by PhpStorm.
 * User: Eric
 * Date: 17.05.2016
 * Time: 08:50
 */

namespace vindinium\logservices\models;

/**
 * @Entity @Table(name = "State")
 */
class State
{
    /**
     * @Column(type="integer") @Id @GeneratedValue(strategy="IDENTITY")
     */
    protected $stateId;

    /**
     * @Column(type="integer")
     */
    protected $bestAction;

}