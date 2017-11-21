<?php
class BookAuthor{
  public $connection;
  public $ID;
  public $firstname; // *** REQUIRED ***
  public $lastname; // *** REQUIRED ***

  public function __construct($connection){
    $this->connection = $connection;
    $this->create_book_authors_table();
    // $this->welcome_message();
  }

  public function get_book_author_by_id($id){
    $this->ID = $id;
    $sql = "SELECT * FROM `book_authors`
    WHERE book_author_ID='$this->ID';";
    $result = mysqli_query($this->connection, $sql);
    if($result){
      $row = mysqli_fetch_array($result);
      return $row['book_author_firstname'].' '.$row['book_author_lastname'];
    }
  }

  public function add_book_author($data){
    $this->set_book_author($data);
    $this->insert_book_author();
  }

  public function set_book_author($data){
    $this->firstname  = $data['firstname'];
    $this->lastname   = $data['lastname'];
  }

  public function insert_book_author(){
    $sql = "INSERT INTO `book_authors` (
      `book_author_ID`,
      `book_author_firstname`,
      `book_author_lastname`,
      `book_author_date_entered`
    ) VALUES (
      NULL,
      '$this->firstname',
      '$this->lastname',
      CURRENT_TIMESTAMP
    );";
    $result = mysqli_query($this->connection, $sql);
    if(!$result){echo('*** Error INSERTING Book ***<br>');}
  }

  public function create_book_authors_table(){
    $sql = "CREATE TABLE IF NOT EXISTS `whollycoders`.`book_authors` (
       `book_author_ID` INT NOT NULL AUTO_INCREMENT ,
       `book_author_firstname` VARCHAR(50) NOT NULL ,
       `book_author_lastname` VARCHAR(50) NOT NULL ,
       `book_author_date_entered` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
       PRIMARY KEY (`book_author_ID`)
     ) ENGINE = InnoDB;";
    $result = mysqli_query($this->connection, $sql);
    if(!$result){echo('*** ERROR Creating BOOKS Table ***<br>');}
  }

  public function welcome_message(){
    echo('BookAuthor Class Instantiated...<br>');
  }

}

 ?>
