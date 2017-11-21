<?php

class WeeklyTeamWeightLoss
{
  public $connection;
  public $db_name     = 'recapp';
  public $table_name  = 'weighins';
  public $team;
  public $begin_week;
  public $current_week;
  public $previous_week;
  public $begin_weight;
  public $current_weight;
  public $previous_weight;
  public $begin_weight_comp;
  public $current_weight_comp;
  public $previous_weight_comp;
  public $data;
  public $json;

  public function __construct($connection, $team, $week)
  {
    $this->connection = $connection;
    $this->team       = $team;
    $this->create_team_results_table();
    $this->set_weeks($week);

  }
// *** Compute Weekly Team Weight Loss ***
  public function compute_weekly_team_weight_loss()
  {
    // Get Team Name
    // Get Week Name
    // Get Weights
    $weights = $this->get_weights();
    // Compute Results
    // Insert Results
    // Display Results
  }

// *** Create Team Results Table ***
  public function create_team_results_table()
  {
    $sql = "CREATE TABLE IF NOT EXISTS `team_results` ( ";
    $sql .= "`team_result_ID` INT UNSIGNED NOT NULL AUTO_INCREMENT , ";
    $sql .= "`team_result_competitor` VARCHAR(100) NOT NULL , ";
    $sql .= "`team_result_competition` VARCHAR(100) NOT NULL , ";
    $sql .= "`team_result_team` VARCHAR(100) NOT NULL , ";
    $sql .= "`team_result_week` VARCHAR(100) NOT NULL , ";
    $sql .= "`team_result_weight_loss` DECIMAL(4,1) NOT NULL , ";
    $sql .= "`team_result_weight_percent` DECIMAL(4,1) NOT NULL , ";
    $sql .= "`team_result_weight_loss_overall` DECIMAL(4,1) NOT NULL , ";
    $sql .= "`team_result_weight_loss_percent_overall` DECIMAL(4,1) NOT NULL , ";
    $sql .= "`team_result_date_entered` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , ";
    $sql .= "UNIQUE (`team_result_competitor`, `team_result_week`), ";
    $sql .= "PRIMARY KEY (`team_result_ID`)";
    $sql .= ") ENGINE = InnoDB;";

    $result = $this->process_query($sql);
    if(!$result){ echo('There has been an ERROR creating TEAM RESULTS Table!!!<br>'); }
  }  

// *** Extract Week Number ***
  public function extract_week_number($week)
  {
    $week = explode(' ', $week);
    return $week[1];
  }

// *** Get Begin Week ***
  public function get_begin_week()
  {
     return $this->begin_week; 
  }

// *** Get Begin Weight ***
  public function get_begin_weight()
  {
    $sql = "SELECT sum(weigh_in_weight) as begin_weight
            FROM weighins
            WHERE weigh_in_team='".$this->team."'
            AND weigh_in_week='".$this->begin_week."';";
    $result = $this->process_query($sql);
    if($result)
    {
      $row = mysqli_fetch_assoc($result);

        return $this->begin_weight = $row['begin_weight'];

    }
  }

// *** Get Competition Begin Weight ***
  public function get_begin_weight_comp()
  {
    $sql = "SELECT sum(weigh_in_weight) as begin_weight
            FROM weighins
            WHERE weigh_in_week='".$this->begin_week."';";
    $result = $this->process_query($sql);
    if($result)
    {
      $row = mysqli_fetch_assoc($result);

        return $this->begin_weight_comp = $row['begin_weight'];

    }
  }  

// *** Get Current Week ***
public function get_current_week()
  {
    return $this->current_week; 
  }

// *** Get Previous Week ***
  public function get_previous_week($week)
  {
    if($week == 'Week 1'){ return $this->begin_week; }
      return $this->previous_week = 'Week '.($this->extract_week_number($week) - 1);
  }

// *** Get Current Weight ***
  public function get_current_weight()
  {
    $sql = "SELECT sum(weigh_in_weight) as current_weight
            FROM weighins
            WHERE weigh_in_team='".$this->team."'
            AND weigh_in_week='".$this->current_week."';";
    $result = $this->process_query($sql);
    if($result)
    {
      $row = mysqli_fetch_assoc($result);
  
         return $this->current_weight = $row['current_weight'];
  
    }
  }

// *** Get Competition Current Weight ***
  public function get_current_weight_comp()
  {
    $sql = "SELECT sum(weigh_in_weight) as current_weight
            FROM weighins
            WHERE weigh_in_week='".$this->current_week."';";
    $result = $this->process_query($sql);
    if($result)
    {
      $row = mysqli_fetch_assoc($result);

        return $this->current_weight_comp = $row['current_weight'];

    }
  }

// *** Get Previous Weight ***
  public function get_previous_weight()
  {
    $sql = "SELECT sum(weigh_in_weight) as previous_weight
            FROM weighins
            WHERE weigh_in_team='".$this->team."'
            AND weigh_in_week='".$this->previous_week."';";
    $result = $this->process_query($sql);
    if($result)
    {
      $row = mysqli_fetch_assoc($result);
      
             return $this->previous_weight = $row['previous_weight'];
    }
  }

// *** Get Competition Previous Weight ***
  public function get_previous_weight_comp()
  {
    $sql = "SELECT sum(weigh_in_weight) as previous_weight
            FROM weighins
            WHERE weigh_in_week='".$this->previous_week."';";
    $result = $this->process_query($sql);
    if($result)
    {
      $row = mysqli_fetch_assoc($result);
      
            return $this->previous_weight_comp = $row['previous_weight'];
    }
  }

// *** Get Weekly Team Weight Loss ***
  public function get_weekly_team_weight_loss_leaders($week)
  {
    $sql = "SELECT *
            FROM team_results
            WHERE team_result_week='$week'
            ORDER BY team_result_weight_loss_percent
            LIMIT 3;";
    $result = $this->process_query($sql);
    if($result)
    {
      $this->data = array();
      while($row = mysqli_fetch_assoc($result))
      {
        $this->data[] = array(
          'id'                            =>        $row['team_result_ID'],
          'competition'                   =>        $row['team_competition'],
          'team'                          =>        $row['team_team'],
          'week'                          =>        $row['team_week'],
          'weight_loss'                   =>        $row['team_weight_loss'],
          'weight_loss_percent'           =>        $row['team_weight_loss_percent'],
          'weight_loss_overall'           =>        $row['team_weight_loss_overall'],
          'weight_loss_percent_overall'   =>        $row['team_weight_loss_percent_overall'],
        );
      }
        $this->json = json_encode($this->data);
        return $this->data;
    }
  }

// *** Get Weight Loss - Competition Overall***
  public function get_weight_loss_comp()
  {
    return $this->current_weight_comp - $this->begin_weight_comp;
  }

// *** Get Weight Loss - Competition Week ***
  public function get_weight_loss()
  {
    return $this->current_weight_comp - $this->previous_weight_comp;
  }

// *** Get Weights ***
  public function get_weights()
  {
    $weights = array(
      'begin'       =>    $this->begin_weight,
      'previous'    =>    $this->previous_weight,
      'current'     =>    $this->current_weight
    );
    return $weights;
  }

// *** Get Competition Weights ***
public function get_weights_comp()
{
  $weights = array(
    'begin'       =>    $this->begin_weight_comp,
    'previous'    =>    $this->previous_weight_comp,
    'current'     =>    $this->current_weight_comp
  );
  return $weights;
}

// *** Set Weights ***
  public function set_weights()
  {
    $this->get_begin_weight();
    $this->get_previous_weight();
    $this->get_current_weight();
  }

// *** Set Competition Weights ***
  public function set_weights_comp()
  {
    $this->get_begin_weight_comp();
    $this->get_previous_weight_comp();
    $this->get_current_weight_comp();
  }

// ***** PROCESS Query *****
  public function process_query($sql){
    $result = mysqli_query($this->connection, $sql);
    return $result;
  }

// *** Set Weeks ***
  public function set_weeks($week)
  {
    $this->begin_week     = 'Orientation';
    $this->current_week   = $week;
    $this->previous_week  = $this->get_previous_week($week);
  }
}

?>