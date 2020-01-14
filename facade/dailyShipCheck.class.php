<?php
  class dailyShipCheck{
    private $db;

    public function __construct(){
      require_once 'dbConn.class.php';
      $this->db = dbConn::getInstance();
    }

    public function getShippedToday(){
      $result = $this->db->query('SELECT * FROM orders WHERE orderDate = "2005-05-31";')->fetch_all(MYSQLI_ASSOC);
      return print_r($result);
    }
  }
?>
