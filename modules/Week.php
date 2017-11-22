<?php
  class Week{
    public $connection;
    public $db_name = 'recapp_v2';
    public $table_name = 'weeks';
    public $id;
    public $name;
    public $description;
    public $code;
    public $begin_date;
    public $end_date;
    public $b4g;
    public $sss;
    public $memory;
    public $competition_id;
    public $data;
    public $json;
// *** Class Constructor ***
    public function __construct($connection){
      $this->connection = $connection;
      $this->create_table();
    }

// *** Add Week ***
    public function add_week($params){
      $this->name           =   $params['name'];
      $this->description    =   $params['description'];
      $this->code           =   $params['code'];
      $this->begin_date     =   $params['begin_date'];
      $this->end_date       =   $params['end_date'];
      $this->b4g            =   $params['b4g'];
      $this->sss            =   $params['sss'];
      $this->memory         =   $params['memory'];
      $this->competition_id =   $params['competition_id'];
      $this->create_week();
    }

// *** Create Weeks Table ***    
    public function create_table(){
      $sql = "CREATE TABLE IF NOT EXISTS `".$this->db_name."`.`".$this->table_name."` ( ";
      $sql .= "`week_ID` INT UNSIGNED NOT NULL AUTO_INCREMENT , ";
      $sql .= "`week_name` VARCHAR(100) NOT NULL , ";
      $sql .= "`week_description` VARCHAR(100) NULL , ";
      $sql .= "`week_code` VARCHAR(100) NULL , ";
      $sql .= "`week_begin_date` VARCHAR(20) NULL , ";
      $sql .= "`week_end_date` VARCHAR(20) NULL , ";
      $sql .= "`week_b4g` VARCHAR(255) NULL , ";
      $sql .= "`week_sss` VARCHAR(255) NULL , ";
      $sql .= "`week_memory` VARCHAR(255) NULL , ";
      $sql .= "`week_competition_ID` INT UNSIGNED NULL , ";
      $sql .= "`week_date_entered` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, ";
      $sql .= "PRIMARY KEY (`week_ID`)";
      $sql .= ") ENGINE = InnoDB;";

      $result = mysqli_query($this->connection, $sql);
      if(!$result){
        echo('There was a problem creating the WEEKS table!!!<br>');
      }
    }

 // *** Create Week ***   
    public function create_week(){
      $sql = "INSERT INTO `".$this->table_name."` (
        `week_ID`,
        `week_name`,
        `week_description`,
        `week_code`,
        `week_begin_date`,
        `week_end_date`,
        `week_b4g`,
        `week_sss`,
        `week_memory`,
        `week_competition_ID`,
        `week_date_entered`
      ) VALUES (
        NULL,
        '$this->name',
        '$this->description',
        '$this->code',
        '$this->begin_date',
        '$this->end_date',
        '$this->b4g',
        '$this->sss',
        '$this->memory',
        '$this->competition_id',
        CURRENT_TIMESTAMP
      );";
      // prewrap($sql);
      $result = mysqli_query($this->connection, $sql);
      if(!$result){
        echo('There has been an ERROR --- Unable to CREATE WEEK!!!<br>');
      }else{
        // echo('Week Added Successfully!!!<br>');
      }
    }

//INSERT INTO `weeks` (`week_ID`, `week_name`, `week_description`, `week_code`, `week_begin_date`, `week_end_date`, `week_b4g`, `week_sss`, `week_memory`, `week_competition_ID`, `week_date_entered`) 
// VALUES (NULL, 'Orientation', NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, CURRENT_TIMESTAMP), (NULL, 'Week 1', NULL, '1', NULL, NULL, NULL, NULL, NULL, NULL, CURRENT_TIMESTAMP);

// *** Edit Week ***
    public function edit_week($params){
      $this->id             =   $params['id'];
      $this->name           =   $params['name'];
      $this->description    =   $params['description'];
      $this->code           =   $params['code'];
      $this->begin_date     =   $params['begin_date'];
      $this->end_date       =   $params['end_date'];
      $this->b4g            =   $params['b4g'];
      $this->sss            =   $params['sss'];
      $this->memory         =   $params['memory'];
      $this->competition_id =   $params['competition_id'];
      $this->update_week();
    }

// *** Delete Week ***
    public function delete_week($id){
      $this->id = $id;
      $this->destroy_week();
    }

// ***** Destroy Week *****
    public function destroy_week(){
      $sql = "DELETE FROM `weeks` WHERE week_ID='$this->id';";
      $result = mysqli_query($this->connection, $sql);
      if(!$result){echo('There has been an ERROR deleting week...<br>');}
    }

// *** Get ALL Weeks ***
  public function get_all_weeks(){
    $sql = "SELECT * FROM weeks";
    $result = mysqli_query($this->connection, $sql);
    if($result){
      return $this->get_week_data($result);
    }
  }

// *** Get DB Name ***
    public function get_db_name(){
      return $this->db_name;
    }

// ***** Get Max ID *****
    public function get_max_id(){
      $sql = "SELECT * FROM weeks ORDER BY week_ID DESC LIMIT 1";
      $result = mysqli_query($this->connection, $sql);
      if($result){
        $week = $this->get_week_data($result);
        return $week['id'];
      }
    }

// *** Get Next Week ***
    public function get_next($id){
      $this->id = $id;
      $sql = "SELECT * FROM weeks
      WHERE week_ID > '$this->id'
      ORDER BY week_ID ASC LIMIT 1;";
      $result = mysqli_query($this->connection, $sql);
      if($result){
        $week = $this->get_week_data($result);
        if(!$week['id']){
          return $this->id;
        }
        return $week['id'];
      }
    }


// *** Get Previous Week ***
    public function get_previous($id){
      $this->id = $id;
      $sql = "SELECT * FROM weeks
      WHERE week_ID < '$this->id'
      ORDER BY week_ID DESC LIMIT 1;";
      $result = mysqli_query($this->connection, $sql);
      if($result){
        $week = $this->get_week_data($result);
        if(!$week['id']){
          return $this->id;
        }
        return $week['id'];
      }
    }

// *** Get Table Name ***
    public function get_table_name(){
      return $this->table_name;
    }

// *** Get Week By ID ***    
    public function get_week_by_id($id){
      $sql = "SELECT * FROM weeks WHERE week_ID='$id' LIMIT 1";
      $result = mysqli_query($this->connection, $sql);
      if($result){
        return $this->get_week_data($result);
      }
    }


// ***** Get Week Ending Date *****
    public function get_week_ending_date($week_number){
      $id = $week_number;
      $sql = "SELECT week_begin_date FROM weeks
      WHERE week_code='$id';";
      // prewrap($sql);
      $result = mysqli_query($this->connection, $sql);
      if($result){
        $rows = mysqli_fetch_assoc($result);
        // prewrap($rows);
        return $rows['week_begin_date'];
      }
    }

// *** Get Week ID ***
    public function get_week_id($week_number){
      return $week_number + 1;
    }

// ***** Get Week Number *****
  public function get_week_number($week_id){
    $sql = "SELECT * FROM weeks WHERE week_ID='$week_id';";
    $result = mysqli_query($this->connection, $sql);
    if($result){
      return $this->get_week_data($result);
    }else{echo('There has been an ERROR!!!');}
  }

//*************** GET Week DATA *****************
    public function get_week_data($result){
      $this->rows = mysqli_num_rows($result);
      $this->data = array();
      if($this->rows > 1){
        while($row = mysqli_fetch_assoc($result)){
          $this->data[] = array(
            'id'              =>    $row['week_ID'],
            'name'            =>    $row['week_name'],
            'description'     =>    $row['week_description'],
            'code'            =>    $row['week_code'],
            'begin_date'      =>    $row['week_begin_date'],
            'end_date'        =>    $row['week_end_date'],
            'b4g'             =>    $row['week_b4g'],
            'sss'             =>    $row['week_sss'],
            'memory'          =>    $row['week_memory'],
            'competition_ID'  =>    $row['week_competition_ID'],
            'date_entered'    =>    $row['week_date_entered']
          );
        }
        $this->json = json_encode($this->data);

      }else{
        $row = mysqli_fetch_assoc($result);
          $this->data = array(
            'id'              =>    $row['week_ID'],
            'name'            =>    $row['week_name'],
            'description'     =>    $row['week_description'],
            'code'            =>    $row['week_code'],
            'begin_date'      =>    $row['week_begin_date'],
            'end_date'        =>    $row['week_end_date'],
            'b4g'             =>    $row['week_b4g'],
            'sss'             =>    $row['week_sss'],
            'memory'          =>    $row['week_memory'],
            'competition_ID'  =>    $row['week_competition_ID'],
            'date_entered'    =>    $row['week_date_entered']
          );

        $this->json = json_encode($this->data);
      }
      return $this->data;
    }


// ***** Does ID Exist *****
    public function id_exist($id){
      $sql = "SELECT * FROM weeks WHERE week_ID='$id';";
      $result = mysqli_query($this->connection, $sql);
      if($result){
        return true;
      }else{
        return false;
      }
    }

// ***** Update Week *****
    public function update_week(){
      $sql = "UPDATE `weeks`
      SET `week_name`= '$this->name',
      `week_description`= '$this->description',
      `week_code`= '$this->code',
      `week_begin_date`= '$this->begin_date',
      `week_end_date`= '$this->end_date',
      `week_b4g`= '$this->b4g',
      `week_sss`= '$this->sss',
      `week_memory`= '$this->memory',
      `week_competition_ID`= '$this->competition_id'
      WHERE `weeks`.`week_ID`= '$this->id';";
        // prewrap($sql);
      $result = mysqli_query($this->connection, $sql);
      // if(!$result){
      //   echo('There has been a problem updating the week...<br>');
      // }else{
      //   header('Location: ./index.php');
      // }
    }

  }


/*
INSERT INTO `weeks` (`week_ID`, `week_name`, `week_description`, `week_code`, `week_begin_date`, `week_end_date`, `week_b4g`, `week_sss`, `week_memory`, `week_competition_ID`, `week_date_entered`) VALUES (NULL, 'Week 1', 'Orientation', '0', '07-Sep-2017', '13-Sep-2017', 'Orientation', 'Kick-Off Celebration', NULL, NULL, CURRENT_TIMESTAMP);
*/
 ?>
