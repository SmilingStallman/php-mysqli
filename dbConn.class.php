<?php
  class dbConn extends mysqli {
    private $user = 'demo';
    private $host = 'localhost';
    private $pass = 'pass123$.';
    private $db = 'demo';
    private static $instance;

    private function __construct(){
      parent::__construct($this->host, $this->user, $this->pass, $this->db);
    }

    public static function getInstance(){
      if(is_null(self::$instance)){
        self::$instance = new self();
      }
      return self::$instance;
    }

    public function testConnection(){
      return is_null(self::$instance) ? "Connection failed" : "Connection sucessful to DB $this->db via user $this->user";
    }
  }
?>
