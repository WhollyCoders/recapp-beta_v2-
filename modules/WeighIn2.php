<?php
class WeighIn{
  public $connection;
  public $db_name = 'mybod4god';
  public $table_name = 'weigh_ins-170907';
  public $Competition;
  public $Competitor;
  public $Team;
  public $Week;
  public $id;
  public $competitor_id;
  public $competition_id;
  public $team_id;
  public $week_id;
  public $weight;
  public $begin;
  public $previous;
  public $current;
  public $data;
  public $json;

  public function __construct($connection){
    $this->connection   = $connection;
    $this->Competition  = new Competition($this->connection);
    $this->Competitor   = new Competitor($this->connection);
    $this->Team         = new Team($this->connection);
    $this->Week         = new Week($this->connection);
    $this->create_table();
  }
// ***** INSERT New Weigh-In *****
  public function add_weigh_in(){
    $sql = "INSERT INTO ".$this->get_table_ref()."(
      `weigh_in_ID`,
      `weigh_in_competitor_ID`,
      `weigh_in_competition_ID`,
      `weigh_in_team_ID`,
      `weigh_in_week_ID`,
      `weigh_in_weight`,
      `weigh_in_date_entered`
    ) VALUES (
      NULL,
      '$this->competitor_id',
      '$this->competition_id',
      '$this->team_id',
      '$this->week_id',
      '$this->weight',
      CURRENT_TIMESTAMP
    );";
    $this->process_query($sql);
  }

// ***** Create Weigh-Ins Table *****
  public function create_table(){
    $sql = "CREATE TABLE IF NOT EXISTS ".$this->get_table_ref()." ( ";
    $sql .= "`weigh_in_ID` INT UNSIGNED NOT NULL AUTO_INCREMENT , ";
    $sql .= "`weigh_in_competitor_ID` INT UNSIGNED NULL , ";
    $sql .= "`weigh_in_competition_ID` INT UNSIGNED NULL , ";
    $sql .= "`weigh_in_team_ID` INT UNSIGNED NULL , ";
    $sql .= "`weigh_in_week_ID` INT UNSIGNED NULL , ";
    $sql .= "`weigh_in_weight` DECIMAL(4,1) NOT NULL , ";
    $sql .= "`weigh_in_date_entered` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , ";
    $sql .= "UNIQUE (`weigh_in_competitor_ID`, `weigh_in_week_ID`), ";
    $sql .= "PRIMARY KEY (`weigh_in_ID`)";
    $sql .= ") ENGINE = InnoDB;";

    // echo('<pre>');
    // print_r($sql);
    // echo('</pre>');

    $result = $this->process_query($sql);
    if(!$result){ echo('There has been an ERROR creating Weigh_Ins Table!!!<br>'); }
  }

// ***** Create Weigh In *****
  public function create_weigh_in($params){
    $this->competitor_id  = $params['competitor_id'];
    $this->competition_id = $params['competition_id'];
    $this->team_id        = $params['team_id'];
    $this->week_id        = $params['week_id'];
    $this->weight         = $params['weight'];
    $this->add_weigh_in();
  }

// ***** Delete Weigh In *****
  public function delete_weigh_in($id){
    $sql = "DELETE FROM `weigh_ins-170907` WHERE weigh_in_ID=$id;";
    $this->process_query($sql);
  }

// ***** Destroy Weigh In *****
  public function destroy_weigh_in($id){
    $this->delete_weigh_in($id);
  }
// ***** Edit Weigh In ***
  public function edit_weigh_in(){
    $sql = "UPDATE `weigh_ins-170907`
    SET `weigh_in_competitor_id` = '$this->competitor_id',
    `weigh_in_competition_id` = '$this->competition_id',
    `weigh_in_team_id` = '$this->team_id',
    `weigh_in_week_id` = '$this->week_id',
    `weigh_in_weight` = '$this->weight'
    WHERE `weigh_ins-170907`.`weigh_in_ID`=$this->id;";
    // prewrap($sql);
    $result = $this->process_query($sql);
  }
// ***** GET ALL Weigh-Ins *****
  public function get_all_weigh_ins(){
    $sql = "SELECT * FROM ".$this->get_table_ref().";";
    $result = $this->process_query($sql);
    if($result){
      return $this->get_data($result);
    }
    // echo('<pre>');
    // print_r($sql);
    // echo('</pre>');
  }

// ***** GET Beginning Weight *****
  public function get_begin_weight($competitor_id){
    return $this->weight;
  }

// ***** GET Competition Name *****
  public function get_competition_name($id){
    $competition = $this->Competition->get_competition_by_id($id);
    return $competition['name'];
  }

// ***** GET Competitor's Name *****
  public function get_competitor_name($id){
    $competitor = $this->Competitor->get_competitor_by_id($id);
    return $competitor['firstname'].' '.$competitor['lastname'];
  }

// ***** GET Competitor Weigh-In Data *****
  public function get_competitor_weigh_in_data($competitor_id, $week_id){

  }

// ***** GET Current Weight *****
  public function get_current_weight($competitor_id, $week_id){
    return $this->weight;
  }

// ***** GET Weigh-In Data *****
  public function get_data($result){
    $this->data = array();
    $rows = $this->get_rows($result);
    if($rows == 1){
      $row = mysqli_fetch_assoc($result);
      $this->data = array(
        'id'                  =>      $row['weigh_in_ID'],
        'competitor_id'       =>      $row['weigh_in_competitor_ID'],
        'competition_id'      =>      $row['weigh_in_competition_ID'],
        'team_id'             =>      $row['weigh_in_team_ID'],
        'week_id'             =>      $row['weigh_in_week_ID'],
        'weight'              =>      $row['weigh_in_weight'],
        'date_entered'        =>      $row['weigh_in_date_entered']
      );
      $this->json = json_encode($this->data);
    }else{
      while($row = mysqli_fetch_assoc($result)){
        $this->data[] = array(
          'id'                  =>      $row['weigh_in_ID'],
          'competitor_id'       =>      $row['weigh_in_competitor_ID'],
          'competition_id'      =>      $row['weigh_in_competition_ID'],
          'team_id'             =>      $row['weigh_in_team_ID'],
          'week_id'             =>      $row['weigh_in_week_ID'],
          'weight'              =>      $row['weigh_in_weight'],
          'date_entered'        =>      $row['weigh_in_date_entered']
        );
      }
      $this->json = json_encode($this->data);
    }
    return $this->data;
  }

// *** Get Next Competitor ***
  public function get_next($id){
    $this->id = $id;
    $sql = "SELECT * FROM weigh_ins
    WHERE weigh_in_ID > '$this->id'
    ORDER BY weigh_in_ID ASC LIMIT 1;";
    $result = mysqli_query($this->connection, $sql);
    if($result){
      $weigh_in = $this->get_weigh_in_data($result);
      if(!$weigh_in['id']){
        return $this->id;
      }
      return $weigh_in['id'];
    }
  }

// *** Get Previous Competitor ***
  public function get_previous($id){
    $this->id = $id;
    $sql = "SELECT * FROM weigh_ins
    WHERE weigh_in_ID < '$this->id'
    ORDER BY weigh_in_ID DESC LIMIT 1;";
    $result = mysqli_query($this->connection, $sql);
    if($result){
      $weigh_in = $this->get_weigh_in_data($result);
      if(!$weigh_in['id']){
        return $this->id;
      }
      return $weigh_in['id'];
    }
  }

// ***** GET Previous Weight *****
  public function get_previous_weight($competitor_id, $week_id){
    $weight = $this->get_week($week_id);
    return $weight;
  }

// ***** GET Row Count *****
  public function get_rows($result){
    return $rows = mysqli_num_rows($result);
  }

// ***** Get DB Table Reference *****
  public function get_table_ref(){
    return "`$this->db_name`".'.'."`$this->table_name`";
  }

// ***** GET Team Name *****
  public function get_team_name($id){
    $team = $this->Team->get_team_by_id($id);
    return $team['name'];
  }

// ***** GET Week Number *****
  public function get_week($id){
    $week = $this->Week->get_week_number($id);
    return $week['code'];
  }

// ***** GET Weigh-In By ID *****
  public function get_weigh_in_by_id($id){
    $sql = "SELECT * FROM `weigh_ins-170907` WHERE weigh_in_ID='$id';";
    // prewrap($sql);
    $result = mysqli_query($this->connection, $sql);
    if($result){
      return $this->get_data($result);
    }
  }

// ***** PROCESS Query *****
  public function process_query($sql){
    $result = mysqli_query($this->connection, $sql);
    return $result;
  }

// ***** Update Weigh In *****
  public function update_weigh_in($params){
    $this->id             = $params['id'];
    $this->competitor_id  = $params['competitor_id'];
    $this->competition_id = $params['competition_id'];
    $this->team_id        = $params['team_id'];
    $this->week_id        = $params['week_id'];
    $this->weight         = $params['weight'];
    $this->edit_weigh_in();
  }

}

 ?>
