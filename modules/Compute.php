<?php

class Compute
{
  public $db_name     = 'recapp';
  public $competition = 'Bod4God';
  public $connection;
  public $begin;
  public $previous;
  public $current;
  public $competitor;
  public $team;
  public $week;
  
  public function __construct($connection)
  {
    $this->connection = $connection;
  }
// *** Compute Results ***
  public function compute_results()
  {
    $results = array(
      'weight_loss'                 =>      $this->get_weight_loss(),
      'weight_loss_percent'         =>      $this->get_weight_loss_percent(),
      'weight_loss_overall'         =>      $this->get_overall_weight_loss(),
      'weight_loss_percent_overall' =>      $this->get_overall_weight_loss_percent()
    );
  
    return $results;
  }

// *** Extract Week Number ***
  public function extract_week($week)
  {
    $week = explode(' ', $week);
    return $week[1];
  }

/// *** RESULTS ***
  public function get_weight_loss()
  {
    $result = $this->current - $this->previous;
    return number_format($result, 1);
  }

  public function get_weight_loss_percent()
  {
    $result = ($this->get_weight_loss() / $this->previous) * 100;
    return number_format($result, 6);
  }

  public function get_overall_weight_loss()
  {
    $result = $this->current - $this->begin;
    return number_format($result, 1);
  }

  public function get_overall_weight_loss_percent()
  {
    $result = ($this->get_overall_weight_loss() / $this->begin) * 100;
    return number_format($result, 6);
  }

  public function set_weights($weights)
  {
    $this->begin    = $weights['begin'];
    $this->previous = $weights['previous'];
    $this->current  = $weights['current'];
  }

// *** Get Weights ***
  public function get_individual_weights()
  {
    // Need Competitor and Week
    $weights = array(
      'begin'     =>    $this->get_begin_ind(),
      'previous'  =>    $this->get_previous_ind(),
      'current'   =>    $this->get_current_ind()
    );
    return $weights;
  }

// *** Get Team Weights ***
  public function get_team_weights()
  {
    // Need Team and Week
    $weights = array(
      'begin'     =>    $this->get_begin_team(),
      'previous'  =>    $this->get_previous_team(),
      'current'   =>    $this->get_current_team()
    );
    return $weights;
  }

// *** Get Competition Weights ***  
  public function get_competition_weights()
  {
    // Need Week
    $weights = array(
      'begin'     =>    $this->get_begin_comp(),
      'previous'  =>    $this->get_previous_comp(),
      'current'   =>    $this->get_current_comp()
    );
    return $weights;
  }
// ***** PROCESS Query *****
  public function process_query($sql){
    $result = mysqli_query($this->connection, $sql);
    return $result;
  }

// *** Set Competition Week ***  
  public function set_competition_week($week)
  {
    $this->week = $week;
  }

// *** Set Competitor Data ***  
  public function set_competitor_data($data)
  {
    $this->competitor = $data['competitor'];
    $this->week       = $data['week'];
  }

// *** Set Team Data ***  
  public function set_team_data($data)
  {
    $this->team = $data['team'];
    $this->week = $data['week'];
  }

// ******************************* INDIVIDUAL METHODS ***************************
  public function get_begin_ind()
  {
    $sql = "SELECT weigh_in_weight FROM `weighins` 
    WHERE weigh_in_competitor = '".$this->competitor."'
    AND weigh_in_competition = '".$this->competition."'
    AND weigh_in_week = 'Orientation' LIMIT 1;";
    $result = $this->process_query($sql);
    if($result){
      $row = mysqli_fetch_assoc($result);
      return $row['weigh_in_weight'];
    }
  }  

// *** Get Current Weight ***
  public function get_current_ind()
  {
    $sql = "SELECT weigh_in_weight FROM `weighins` 
    WHERE weigh_in_competitor = '".$this->competitor."' 
    AND weigh_in_competition = '".$this->competition."'
    AND weigh_in_week = '".$this->week."' LIMIT 1";
    $result = $this->process_query($sql);
    if($result)
    {
      $row = mysqli_fetch_assoc($result);
      return $row['weigh_in_weight'];
    }
  }
  
// *** Get Previous Weight ***
  public function get_previous_ind()
  {
    
    if($this->week == 'Week 1'){return $this->get_begin_ind();}
    $week = $this->extract_week($this->week);
    $sql = "SELECT weigh_in_weight FROM `weighins` 
    WHERE weigh_in_competitor = '".$this->competitor."' 
    AND weigh_in_competition = '".$this->competition."'
    AND weigh_in_week = 'Week ".($week - 1)."' LIMIT 1";
    $result = $this->process_query($sql);
    if($result)
    {
      $row = mysqli_fetch_assoc($result);
      return $row['weigh_in_weight'];
    }
    
  }

}


 ?>
