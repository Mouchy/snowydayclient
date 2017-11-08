<?php
// src/AppclientloginBundle/Entity/User.php

namespace SD\AppclientloginBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;




class User extends BaseUser
{
    
    protected $id;
    /**
     * {@inheritdoc}
     */
     public function __construct()
    {

        parent::__construct();
    }
     
     
    public function setId($id)
    {
        echo 'setId';
        $this->id = $id;

        return $this;
    }


}