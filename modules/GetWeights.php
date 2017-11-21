<?php 
class GetWeights
{
  public $connection;
  public $db_name     = 'recapp';
  public $table_name  = 'weighins';
  public $competition = 'Bod4God';
  public $competitor;
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

// *** Get Current Weight ***
  public function get_current()
  {
    $sql = "SELECT weigh_in_weight FROM `".$this->table_name."` 
    WHERE weigh_in_competitor = '".$this->competitor."' 
    AND weigh_in_competition = '".$this->competition."'
    AND weigh_in_week = '".$this->week."' LIMIT 1";
    $result = mysqli_query($this->connection, $sql);
    if($result)
    {
      $row = mysqli_fetch_assoc($result);
      return $row['weigh_in_weight'];
    }
  }

// *** Get Begin Weight ***  
  public function get_begin()
  {
    $sql = "SELECT weigh_in_weight FROM `".$this->table_name."` 
    WHERE weigh_in_competitor = '".$this->competitor."'
    AND weigh_in_competition = '".$this->competition."'
    AND weigh_in_week = 'Orientation' LIMIT 1;";
    // $this->prewrap($sql);
    $result = mysqli_query($this->connection, $sql);
    if($result){
      $row = mysqli_fetch_assoc($result);
      return $row['weigh_in_weight'];
    }
  }

// *** Get Previous Weight ***
  public function get_previous($week)
  {
    if($week == 'Week 1'){return $this->get_begin();}
    $week = $this->extract_week($week);
    $sql = "SELECT weigh_in_weight FROM `".$this->table_name."` 
    WHERE weigh_in_competitor = '".$this->competitor."' 
    AND weigh_in_competition = '".$this->competition."'
    AND weigh_in_week = 'Week ".($week - 1)."' LIMIT 1";
    $result = mysqli_query($this->connection, $sql);
    if($result)
    {
      $row = mysqli_fetch_assoc($result);
      return $row['weigh_in_weight'];
    }
  }

// *** Get Weights ***
  public function get_weights($params)
  {
    $this->set_params($params);
    $this->begin    = $this->get_begin();
    $this->previous = $this->get_previous($this->week);
    $this->current  = $this->get_current();
    $weights  = array(
      'begin'       =>    $this->begin,
      'previous'    =>    $this->previous,
      'current'     =>    $this->current
    );
  
    return $weights;
  }

// *** Pre-Format Wrapper ***
  public function prewrap($array)
  {
    echo('<pre>');
    print_r($array);
    echo('</pre>');
  }

// *** Set Params ***
  public function set_params($params)
  {
    $this->competitor = $params['competitor'];
    $this->week       = $params['week'];
    $this->team       = $params['team'];
  }
}

?>