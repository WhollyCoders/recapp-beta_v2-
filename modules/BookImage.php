<?php
class BookImage{
  public $connection;
  public $ID;
  public $image_url;

  public function __construct($connection){
    $this->connection = $connection;
    $this->create_book_images_table();
    $this->welcome_message();
  }

  public function create_book_images_table(){
    $sql = "CREATE TABLE IF NOT EXISTS `whollycoders`.`book_images` (
       `book_image_ID` INT NOT NULL AUTO_INCREMENT ,
       `book_image_url` VARCHAR(255) NOT NULL ,
       `book_image_date_entered` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
       PRIMARY KEY (`book_image_ID`)
     ) ENGINE = InnoDB;";
    $result = mysqli_query($this->connection, $sql);
    if(!$result){echo('*** ERROR Creating BOOKS Table ***<br>');}
  }

  public function set_book_image_params($data){
    $this->image_url    = $data['image_url'];
  }

  public function add_book_image(){
    $sql = "INSERT INTO `book_images` (
      `book_image_ID`,
      `book_image_url`,
      `book_image_date_entered`
    ) VALUES (
      NULL,
      'https://easysemester.com/image/cache/data/0132492660-500x500.jpg',
      CURRENT_TIMESTAMP
    );";
  }

  public function welcome_message(){
    echo('Book IMAGE Class Instantiated...<br>');
  }
}


//'https://easysemester.com/image/cache/data/0132492660-500x500.jpg'
// https://www.paypal.me/wmimedia

 ?>
