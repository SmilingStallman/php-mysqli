<?php
  class unshippedAlterter{
    private $db;

    public function __construct(){
      require_once 'dbConn.class.php';
      $this->db = dbConn::getInstance();
    }

    public function checkUnshippedWarning(){
      $all = $this->db->query('SELECT COUNT(*) FROM orders');
      $shipped = $this->db->query('SELECT COUNT(*) FROM orders WHERE status != "Shipped";');
      $unshipped = number_format((($shipped->fetch_row()[0] / $all->fetch_row()[0]) * 100), 2);

      return $unshipped > 30 ? "Warning: $unshipped% not shipped" : "Okay: only $unshipped% not shipped";
    }
  }
?>
