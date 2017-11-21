<?php
    class Contact{
      public $connection;
      public $id;
      public $email;
      public $firstname;
      public $lastname;
      public $phone;

      public function __construct($connection){
        $this->connection = $connection;
        $this->create_table();
      }

      public function add_contact($params){
        $this->email      = $params['email'];
        $this->firstname  = $params['firstname'];
        $this->lastname   = $params['lastname'];
        $this->phone      = $params['phone'];
        $this->create_contact();
      }

      public function create_contact(){
        $sql = "INSERT INTO `twc_contacts` (
          `contact_ID`,
          `contact_email`,
          `contact_firstname`,
          `contact_lastname`,
          `contact_phone`,
          `contact_date_added`
        ) VALUES (
          NULL,
          '$this->email',
          '$this->firstname',
          '$this->lastname',
          '$this->phone',
          CURRENT_TIMESTAMP
          );";

          $result = mysql_query($this->connection, $sql);
      }

      public function create_table(){
        $sql = "CREATE TABLE IF NOT EXISTS `thewhollycoder`.`twc_contacts` (
          `contact_ID` INT NOT NULL AUTO_INCREMENT ,
          `contact_email` VARCHAR(100) NOT NULL ,
          `contact_firstname` VARCHAR(50) NULL ,
          `contact_lastname` VARCHAR(50) NULL ,
          `contact_phone` VARCHAR(20) NULL ,
          `contact_date_added` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
          PRIMARY KEY (`contact_ID`)
        ) ENGINE = InnoDB;";
        $result = mysql_query($this->connection, $sql);
      }

    }

 ?>