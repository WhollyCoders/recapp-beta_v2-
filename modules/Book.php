<?php
// require('../../dbconnect.php');
// require('../functlib.php');
require('../classes/BookAuthor.php');
class Book{
  public $connection;
  public $id;
  public $title; // *** REQUIRED ***
  public $subtitle;
  public $author_id;
  public $description;
  public $keywords;
  public $isbn_10;
  public $isbn_13;
  public $data;
  public $json;
// *** OBJECTS ***
  public $BookAuthor;

  public function __construct($connection){
    $this->connection = $connection;
    $this->BookAuthor = new BookAuthor($this->connection);
    $this->create_books_table();
    // $this->welcome_message();
  }

  public function get_all_books(){
    $sql = "SELECT * FROM `books`;";
    $result = mysqli_query($this->connection, $sql);
    return $this->get_book_data($result);
  }

  public function get_book_title($id){
    $this->id = $id;
    $sql = "SELECT book_title FROM `books` WHERE book_ID='$this->id' LIMIT 1;";
    $result = mysqli_query($this->connection, $sql);
    if($result){
      $row = mysqli_fetch_assoc($result);
      return $row['book_title'];
    }else{echo('*** Error Getting Book Title ***<br>');}
  }

  public function get_book_data($result){
    if($result){
      $this->data = array();
      $rows = mysqli_num_rows($result);
      if($rows > 1){
        while($row = mysqli_fetch_assoc($result)){
          require('../books/schema.php');
        }
      }else{
        $row = mysqli_fetch_assoc($result);
          require('../books/schema.php');
        $this->data = $this->data[0];
        // print_r($this->data);
      }
      $this->json = json_encode($this->data);
      return $this->data;
    }
  }

  public function get_author_name($author_id){
    return $this->BookAuthor->get_book_author_by_id($author_id);
  }

  public function add_author($data){
    $this->BookAuthor->add_book_author($data);
  }

  public function add_book($data){
    $this->set_book_params($data);
    $this->insert_book();
  }

  public function set_book_params($data){
    $this->title        = $data['title'];
    $this->subtitle     = $data['subtitle'];
    $this->author_id    = $data['author_id'];
    $this->description  = $data['description'];
    $this->keywords     = $data['keywords'];
    $this->isbn_10      = $data['isbn_10'];
    $this->isbn_13      = $data['isbn_13'];
  }

  public function insert_book(){
    $sql = "INSERT INTO `books` (
      `book_ID`,
      `book_title`,
      `book_subtitle`,
      `book_author_ID`,
      `book_description`,
      `book_keywords`,
      `book_isbn_10`,
      `book_isbn_13`,
      `book_date_entered`
    ) VALUES (
      NULL,
      '$this->title',
      '$this->subtitle',
      '$this->author_id',
      '$this->description',
      '$this->keywords',
      '$this->isbn_10',
      '$this->isbn_13',
      CURRENT_TIMESTAMP
    );";
    $result = mysqli_query($this->connection, $sql);
    if(!$result){echo('*** Error INSERTING Book ***<br>');}
  }

  public function create_books_table(){
    // *** Include Table Description ***
    require('../books/table.php');
    $result = mysqli_query($this->connection, $sql);
    if(!$result){echo('*** ERROR Creating BOOKS Table ***<br>');}
  }

  public function welcome_message(){
    echo('BOOK Class Instantiated...<br>');
  }
}

$title        = 'Getting Started With Coding';
$author_id    = 0;
$description  = NULL;
$keywords     = NULL;
$isbn_10      = NULL;
$isbn_13      = '978-1-119-17717-3';

$book_params  = array(

  'title'       =>  $title,
  'author_id'   =>  $author_id,
  'description' =>  $description,
  'keywords'    =>  $keywords,
  'isbn_10'     =>  $isbn_10,
  'isbn_13'     =>  $isbn_13

);

// $book = new Book($connection);
// $book->create($book_params);
// $book->add_book($book_params);
// prewrap($book);

 ?>
