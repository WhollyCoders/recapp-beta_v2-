<?php
// require('../../../__CONNECT/recapp-connect.php');
class Team{
  public $db_name     = 'recapp';
  public $table_name  = 'teams';
  public $connection;
  public $id;
  public $name;
  public $details;
  public $competition_id;
  public $data;
  public $json;

  public function __construct($connection){
    $this->connection = $connection;
    $this->create_table();
  }

  public function add_team(){
    $sql = "INSERT INTO `teams`(
      `team_ID`,
      `team_name`,
      `team_details`,
      `team_competition_ID`,
      `team_date_entered`
    ) values(
      NULL,
      '$this->name',
      '$this->details',
      '$this->competition_id',
      CURRENT_TIMESTAMP
    );";
    $result = $this->process_query($sql);
  }
  // INSERT INTO `teams` (`team_ID`, `team_name`, `team_details`, `team_competition_ID`, `team_date_entered`) 
  // VALUES (NULL, 'Mighty Mangoes', NULL, NULL, CURRENT_TIMESTAMP), (NULL, 'Cool Cucumbers', NULL, NULL, CURRENT_TIMESTAMP);
  public function create_team($params){
    $this->name             = $params['name'];
    $this->details          = $params['details'];
    $this->competition_id   = $params['competition_id'];
    $this->add_team();
  }

  public function create_table(){
    $sql = "CREATE TABLE IF NOT EXISTS `".$this->db_name."`.`".$this->table_name."` (
       `team_ID` INT NOT NULL AUTO_INCREMENT ,
       `team_name` VARCHAR(50) NOT NULL ,
       `team_details` TEXT NULL ,
       `team_competition_ID` INT NULL,
       `team_date_entered` DATETIME NOT NULL ,
       PRIMARY KEY (`team_ID`)
     ) ENGINE = InnoDB;";
    $result = $this->process_query($sql);
  }

  public function delete_team($id){
    $this->id = $id;
    $this->destroy_team();
  }

  public function destroy_team(){
    $sql = "DELETE FROM $this->table_name WHERE team_ID='$this->id';";
    prewrap($sql);
    $result = $this->process_query($sql);
  }

  public function edit_team($params){
    $this->id               = $params['id'];
    $this->name             = $params['name'];
    $this->details          = $params['details'];
    $this->competition_id   = $params['competition_id'];
    $this->update_team();
  }

  public function get_team_by_id($id){
    $this->id = $id;
    $sql = "SELECT * FROM teams WHERE team_ID='".$this->id."';";
    $result = $this->process_query($sql);
    if($result){ return $this->get_data($result); }
  }

  public function get_teams(){
    $sql = "SELECT * FROM teams;";
    $result = $this->process_query($sql);
    if($result){ return $this->get_data($result); }
  }

  public function get_data($result){
    $this->data = array();
    $rows = mysqli_num_rows($result);
    if($rows > 1){
      while($row = mysqli_fetch_assoc($result)){
        $this->data[] = array(
          'id'            =>    $row['team_ID'],
          'name'          =>    $row['team_name'],
          'details'       =>    $row['team_details'],
          'date_entered'  =>    $row['team_date_entered']
        );
      }

    }else{
      $row = mysqli_fetch_assoc($result);
      $this->data[] = array(
        'id'            =>    $row['team_ID'],
        'name'          =>    $row['team_name'],
        'details'       =>    $row['team_details'],
        'date_entered'  =>    $row['team_date_entered']
      );

    }
      $this->json = json_encode($this->data);

      $path = '../../assets/data/json/';
      $file_name = 'teams.json';
      $url = $path.$file_name;
      $content = $this->json;

      file_put_contents($url, $content);
      return $this->data;
  }

  public function process_query($sql){
    return $result = mysqli_query($this->connection, $sql);
  }

  public function update_team(){
    $sql = "UPDATE $this->table_name
    SET team_name='$this->name',
    team_details='$this->details'
    WHERE team_ID='$this->id';";
    $result = $this->process_query($sql);
  }

}
// echo('This is the Team Module<br>');
 ?>
