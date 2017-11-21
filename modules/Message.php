<?php
class Message{
  public $connection;
  public $id;
  public $email;
  public $firstname;
  public $lastname;
  public $message;
  public $data;
  public $json;

  public function __construct($connection){
    $this->connection = $connection;
    $this->create_messages_table();
  }
  // *** GET MESSAGE DATA ***
    public function get_message_data($result){
      $this->data = array();
      $rows = mysqli_num_rows($result);
      $this->records = $rows;
      if($rows > 1){
        while($row = mysqli_fetch_assoc($result)){
          $this->data[] = array(
            'id'          =>      $row['message_ID'],
            'email'       =>      $row['message_email'],
            'firstname'   =>      $row['message_firstname'],
            'lastname'    =>      $row['message_lastname'],
            'message'     =>      $row['message_body'],
            'date_added'  =>      $row['message_date_added']
          );
        }
      }else{
        $row = mysqli_fetch_assoc($result);
        $this->data[] = array(
          'id'          =>      $row['message_ID'],
          'email'       =>      $row['message_email'],
          'firstname'   =>      $row['message_firstname'],
          'lastname'    =>      $row['message_lastname'],
          'message'     =>      $row['message_body'],
          'date_added'  =>      $row['message_date_added']
        );
        $this->data = $this->data[0];
      }
      $this->json = json_encode($this->data);
      return $this->data;
    }
  // *** GET ALL MESSAGES ***
    public function get_all_messages(){
      $sql = "SELECT * FROM messages;";
      $result = mysqli_query($this->connection, $sql);
      if($result){
        return $this->get_message_data($result);
      }
    }
  // *** GET ONE MESSAGE - By ID ***
    public function get_message_by_id($id){
      $sql = "SELECT * FROM messages WHERE message_ID='$id';";
      $result = mysqli_query($this->connection, $sql);
      if($result){
        return $this->get_message_data($result);
      }
    }
  public function create_messages_table(){
    $sql = "CREATE TABLE IF NOT EXISTS messages (
      message_ID          INT UNSIGNED NOT NULL AUTO_INCREMENT,
      message_email       VARCHAR(100) NOT NULL,
      message_firstname   VARCHAR(100) NOT NULL,
      message_lastname    VARCHAR(100) NOT NULL,
      message_body        TEXT NOT NULL,
      message_date_added  DATETIME NOT NULL,
      PRIMARY KEY(message_ID)
    );";
    $result = mysqli_query($this->connection, $sql);
    if(!$result){echo('There has been an ERROR | 01CMTab');}
  }
// *** ADD NEW MESSAGE ***
  public function add_new_message($params){
    $this->set_params($params);
    $sql = "INSERT INTO `messages` (
      `message_ID`,
      `message_email`,
      `message_firstname`,
      `message_lastname`,
      `message_body`,
      `message_date_added`
    ) VALUES (
      NULL,
      '$this->email',
      '$this->firstname',
      '$this->lastname',
      '$this->message',
      CURRENT_TIMESTAMP
    );";
    $result = mysqli_query($this->connection, $sql);
    if(!$result){echo('There has been an ERROR | 02ANMes');}else{
      $this->redirect_to_order();
    }
  }
// *** SET MESSAGE PARAMS ***
  public function set_params($params){
    $this->email      = $params['email'];
    $this->firstname  = $params['firstname'];
    $this->lastname   = $params['lastname'];
    $this->message    = $params['message'];
  }
// *** REDIRECT TO ORDER PAGE ***
  public function redirect_to_order(){
    header('Location: ./message-success.php');
  }
}

 ?>
