<?php
// require('../../__CONNECT/recapp-connect.php');
class Competition{
  public $db_name     = 'recapp';
  public $table_name  = 'competitions';
  public $connection;
  public $id;
  public $name;
  public $details;
  public $Team;
  public $Week;
  public $data;
  public $json;

  public function __construct($connection){
    $this->connection = $connection;
    // $this->Team       = new Team($this->connection);
    // $this->Week       = new Week($this->connection);
    $this->create_table();
  }

  public function add_competition(){
    $sql = "INSERT INTO `competitions`(
      `competition_ID`,
      `competition_name`,
      `competition_details`,
      `competition_date_entered`
    ) values(
      NULL,
      '$this->name',
      '$this->details',
      CURRENT_TIMESTAMP
    );";
    echo($sql);
    $result = $this->process_query($sql);
  }

  public function create_competition($params){
    $this->name     = $params['name'];
    $this->details  = $params['details'];
    $this->add_competition();
    // header('Location: ./index.php');
  }

  public function create_table(){
    $sql = "CREATE TABLE IF NOT EXISTS `".$this->db_name."`.`".$this->table_name."` (
       `competition_ID` INT NOT NULL AUTO_INCREMENT ,
       `competition_name` VARCHAR(50) NOT NULL ,
       `competition_details` TEXT NOT NULL ,
       `competition_date_entered` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,
       PRIMARY KEY (`competition_ID`)
     ) ENGINE = InnoDB;";
    $result = $this->process_query($sql);
  }

  public function delete_competition($id){
    $this->id = $id;
    $this->destroy_competition();
  }

  public function destroy_competition(){
    $sql = "DELETE FROM $this->table_name WHERE competition_ID='$this->id';";
    prewrap($sql);
    $result = $this->process_query($sql);
  }

  public function edit_competition($params){
    $this->id       = $params['id'];
    $this->name     = $params['name'];
    $this->details  = $params['details'];
    $this->update_competition();
  }

  public function get_competition_by_id($id){
    $this->id = $id;
    $sql = "SELECT * FROM competitions WHERE competition_ID='".$this->id."';";
    $result = $this->process_query($sql);
    if($result){ return $this->get_data($result); }
  }

  public function get_competitions(){
    $sql = "SELECT * FROM competitions;";
    $result = $this->process_query($sql);
    if($result){ return $this->get_data($result); }
  }

  public function get_data($result){
    $this->data = array();
    $rows = mysqli_num_rows($result);
    if($rows > 1){
      while($row = mysqli_fetch_assoc($result)){
        $this->data[] = array(
          'id'            =>    $row['competition_ID'],
          'name'          =>    $row['competition_name'],
          'details'       =>    $row['competition_details'],
          'date_entered'  =>    $row['competition_date_entered']
        );
      }

    }else{
      $row = mysqli_fetch_assoc($result);
      $this->data[] = array(
        'id'            =>    $row['competition_ID'],
        'name'          =>    $row['competition_name'],
        'details'       =>    $row['competition_details'],
        'date_entered'  =>    $row['competition_date_entered']
      );

    }
      $this->json = json_encode($this->data);

      $path = '../../assets/data/json/';
      $file_name = 'competitions.json';
      $url = $path.$file_name;
      $content = $this->json;

      file_put_contents($url, $content);
      return $this->data;
  }

  public function process_query($sql){
    return $result = mysqli_query($this->connection, $sql);
  }
  public function set_competition($competition)
  {
    $this->competition = $competition;
  }
  public function update_competition(){
    $sql = "UPDATE $this->table_name
    SET competition_name='$this->name',
    competition_details='$this->details'
    WHERE competition_ID='$this->id';";
    $result = $this->process_query($sql);
  }

}

 ?>
