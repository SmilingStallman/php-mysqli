<?php

  class ordersFacade{
    private $shippedCounter;
    private $unshippedAlterter;
    private $dailyShipCheck;

    public function __construct(){
      require 'shippedCounter.class.php';
      require 'unshippedAlerter.class.php';
      require 'dailyShipCheck.class.php';
      $this->shippedCounter = new shippedCounter();
      $this->unshippedAlterter = new unshippedAlterter();
      $this->dailyShipCheck = new dailyShipCheck();
    }

    public function getNumShipped(){
      return $this->shippedCounter->getNumShipped();
    }

    public function checkUnshippedWarning(){
      return $this->unshippedAlterter->checkUnshippedWarning();
    }

    public function getShippedToday(){
      return $this->dailyShipCheck->getShippedToday();
    }
  }

?>
