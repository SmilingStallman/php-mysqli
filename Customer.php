<?php
  
  class Customer{

    private $custID;
    private $conn;

    function __construct(){
      error_reporting(E_ALL);

      require_once 'dbConn.class.php';
      $this->custID = $_POST['custID'];
      $this->conn = dbConn::getInstance();
    }

    //helper DRY function
    private function query_customers(){
      return $this->conn->query('SELECT * FROM customers');
    }

    public function get_customer($custID){
      if(!isset($custID)){
        return 'Cannot query without pkey custID';
      }

      $result = $this->conn->query("SELECT * FROM customers WHERE custID = '$custID'");
      $customer = $result->fetch_assoc();

      if(!$result){
        return 'No customer exists with specified ID.';
      }
      return $customer;
    }

    public function get_all_customers(){
      $customers = $this->query_customers();

      if(!$customers){
        return "Error: no customers";
      }
      return $customers->fetch_all();
    }

    public function get_customer_headers(){
      $customers = $this->query_customers();
      return array_keys($customers->fetch_assoc());
    }
}



?>
