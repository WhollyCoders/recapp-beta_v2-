<?php

class WeeklyWeightLoss
{
  public $connection;
  public $db_name     = 'recapp';
  public $table_name  = 'weighins';
  public $competition = 'Bod4God';
  public $id;
  public $competitor;
  public $team;
  public $begin_week;
  public $current_week;
  public $previous_week;
  public $begin_weight;
  public $current_weight;
  public $previous_weight;

  public function __construct($connection, $competitor, $week)
  {
    $this->connection = $connection;
    $this->competitor = $competitor;
    $this->set_weeks($week);

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
    $sql = "SELECT weigh_in_weight
            FROM weighins
            WHERE weigh_in_competitor='".$this->competitor."'
            AND weigh_in_week='".$this->begin_week."';";
    $result = $this->process_query($sql);
    if($result)
    {
      $row = mysqli_fetch_assoc($result);

        return $this->begin_weight = $row['weigh_in_weight'];

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
    $sql = "SELECT weigh_in_weight
            FROM weighins
            WHERE weigh_in_competitor='".$this->competitor."'
            AND weigh_in_week='".$this->current_week."';";
    $result = $this->process_query($sql);
    if($result)
    {
      $row = mysqli_fetch_assoc($result);
  
         return $this->current_weight = $row['weigh_in_weight'];
  
    }
  }

// *** Get Previous Weight ***
  public function get_previous_weight()
  {
    $sql = "SELECT weigh_in_weight
            FROM weighins
            WHERE weigh_in_competitor='".$this->competitor."'
            AND weigh_in_week='".$this->previous_week."';";
    $result = $this->process_query($sql);
    if($result)
    {
      $row = mysqli_fetch_assoc($result);
      
             return $this->previous_weight = $row['weigh_in_weight'];
    }
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

// *** Get Weights ***
  public function set_weights()
  {
    $this->get_begin_weight();
    $this->get_previous_weight();
    $this->get_current_weight();
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