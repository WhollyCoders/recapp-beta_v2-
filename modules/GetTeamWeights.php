<?php

class GetTeamWeights
{
  public $connection;
  public $db_name     = 'recapp';
  public $table_name  = 'weighins';
  public $competition = 'Bod4God';
  public $week;
  public $team;
  public $begin;
  public $previous;
  public $current;

  public function __construct($connection)
  {
    $this->connection = $connection;
  }
// *** Extract Week Number ***
  public function extract_week($week)
  {
    $week = explode(' ', $week);
    return $week[1];
  }
// *** Get Team's BEGIN Weight *** 
  public function get_team_begin()
  {
    // *** Select ALL weighins WHERE team name = $team AND weigh_in_week = ORIENTATION
    $sql = "SELECT weigh_in_weight 
    FROM weighins
    WHERE weigh_in_team = '".$this->team."'
    AND weigh_in_week = 'Orientation';";
    $result = $this->process_query($sql);
    // *** Loop through results and compute SUM of weigh_in_weight
    if($result)
    {
      $this->begin = 0;
      while($row = mysqli_fetch_assoc($result))
      {
        $this->begin += $row['weigh_in_weight'];
      }
  
        // *** Return SUM
        return $this->begin;
    }
  }
// *** Get Team's CURRENT Weight ***   
  public function get_team_current($week)
  {
    $week = $this->extract_week($week);
    $week = 'Week '.$week;
    // *** Select ALL weighins WHERE team name = $team AND weigh_in_week = $week
    $sql = "SELECT weigh_in_weight 
    FROM weighins
    WHERE weigh_in_team = '".$this->team."'
    AND weigh_in_week = '$week';";
    $result = $this->process_query($sql);
    // *** Loop through results and compute SUM of weigh_in_weight
    if($result)
    {
      $this->current = 0;
      while($row = mysqli_fetch_assoc($result))
      {
        $this->current += $row['weigh_in_weight'];
      }
        // *** Return SUM
        return $this->current;
    }
  }

// *** Get Team's PREVIOUS Weight ***  
  public function get_team_previous($week)
  {
    $week = $this->extract_week($week);
    // *** Select ALL weighins WHERE team name = $team AND weigh_in_week = ($week - 1)
    if($week == 1){ return $this->previous = $this->get_team_begin();}
    $week = $week - 1;
    $week = 'Week '.$week;
    // *** Select ALL weighins WHERE team name = $team AND weigh_in_week = $week
    $sql = "SELECT weigh_in_weight 
    FROM weighins
    WHERE weigh_in_team = '".$this->team."'
    AND weigh_in_week = '$week';";
    $result = $this->process_query($sql);
    // *** Loop through results and compute SUM of weigh_in_weight
    if($result)
    {
      $team_previous = 0;
      while($row = mysqli_fetch_assoc($result))
      {
        $team_previous += $row['weigh_in_weight'];
      }
        // *** Return SUM
        return $team_previous;
    }
  }

  // *** Get Weights ***
  public function get_team_weights($params)
    {
      $this->set_params($params);
      $this->begin    = $this->get_team_begin();
      $this->previous = $this->get_team_previous($this->week);
      $this->current  = $this->get_team_current($this->week);
      $weights  = array(
        'begin'       =>    $this->begin,
        'previous'    =>    $this->previous,
        'current'     =>    $this->current
      );
    
      return $weights;
    }
// *** Pre-Format Wrapper ***
  public function prewrap($data)
  {
    echo('<pre>');
    print_r($data);
    echo('</pre>');
  }

// ***** PROCESS Query *****
  public function process_query($sql)
  {
    $result = mysqli_query($this->connection, $sql);
    return $result;
  }

// *** Set Params ***
  public function set_params($params)
  {
    $this->week       = $params['week'];
    $this->team       = $params['team'];
  }

}

?>