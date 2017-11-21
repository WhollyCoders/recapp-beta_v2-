<?php
class User{
  public $connection;
  public $id;
  public $email;
  public $firstname;
  public $lastname;
  public $password;
  public $phone;

  public function __construct($connection){
    $this->connection = $connection;
    $this->create_table();
  }
// ***** CREATE Users Table *****
  public function create_table(){
    $sql = "";
    $result = mysqli_query($this->connection, $sql);
    if(!$result){echo('There has been an ERROR creating USERS table!!!<br>');}
  }
}


 ?>
