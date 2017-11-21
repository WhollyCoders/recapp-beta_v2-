<?php
class Result{
  public $connection;
  public $db_name = 'recapp';
  public $table_name = 'results';
  public $competition = 'Bod4God';
  public $id;
  public $competitor;
  public $team;
  public $week;
  public $begin;
  public $previous;
  public $current;
  public $weight_loss;
  public $weight_loss_pct;
  public $overall_weight_loss;
  public $overall_weight_loss_pct;

  public function __construct($connection){
    $this->connection = $connection;
    $this->create_table();
  }
// *** Compute Results ***
  public function compute_results()
  {
    $this->get_weight_loss();
    $this->get_weight_loss_percent();
    $this->get_weight_loss_overall();
    $this->get_weight_loss_percent_overall();

    $results = array(
      'weight_loss'                   =>        number_format($this->weight_loss, 1),
      'weight_loss_percent'           =>        number_format($this->weight_loss_percent, 6),
      'weight_loss_overall'           =>        number_format($this->weight_loss_overall, 1),
      'weight_loss_percent_overall'   =>        number_format($this->weight_loss_percent_overall, 6)
    );

    return $results;
  }
// *** Create Results Table ***
  public function create_table()
  {
        $sql = "CREATE TABLE IF NOT EXISTS `".$this->table_name."` (";
        $sql .= "`result_ID` INT UNSIGNED NOT NULL AUTO_INCREMENT , ";
        $sql .= "`result_competitor` VARCHAR(100) NOT NULL , ";
        $sql .= "`result_competition` VARCHAR(100) NOT NULL , ";
        $sql .= "`result_team` VARCHAR(100) NOT NULL , ";
        $sql .= "`result_week` VARCHAR(100) NOT NULL , ";
        $sql .= "`result_weight_loss` DECIMAL(4,1) NOT NULL , ";
        $sql .= "`result_weight_loss_percent` FLOAT(8,6) NOT NULL , ";
        $sql .= "`result_weight_loss_overall` DECIMAL(4,1) NOT NULL , ";
        $sql .= "`result_weight_loss_percent_overall` FLOAT(8,6) NOT NULL , ";
        $sql .= "`result_date_entered` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP , ";
        $sql .= "UNIQUE (`result_competitor`, `result_week`), ";
        $sql .= "PRIMARY KEY (`result_ID`)";
        $sql .= ") ENGINE = InnoDB;";
        $result = $this->process_query($sql);
  }
// *** Get Weight Loss ***
  public function get_weight_loss()
  {
    $this->weight_loss = $this->current - $this->previous;
    return number_format($this->weight_loss, 1);
  }

// *** Get Weight Loss Percent ***
  public function get_weight_loss_percent()
  {
    $this->weight_loss_percent = ($this->get_weight_loss() / $this->previous) * 100;
    return number_format($this->weight_loss_percent, 6);
  }

// *** Get Overall Weight Loss ***
  public function get_weight_loss_overall()
  {
    $this->weight_loss_overall = $this->current - $this->begin;
    return number_format($this->weight_loss_overall, 1);
  }

// *** Get Overall Weight Loss Percent ***
  public function get_weight_loss_percent_overall()
  {
    $this->weight_loss_percent_overall = ($this->get_weight_loss_overall() / $this->begin) * 100;
    return number_format($this->weight_loss_percent_overall, 6);
  }  

// *** Insert Results Into Database ***
  public function insert_results()
  {
    $sql = "INSERT INTO `".$this->table_name."`(
      `result_ID`,
      `result_competitor`,
      `result_competition`,
      `result_team`,
      `result_week`,
      `result_weight_loss`,
      `result_weight_loss_percent`,
      `result_weight_loss_overall`,
      `result_weight_loss_percent_overall`,
      `result_date_entered`
    ) VALUES (
      NULL,
      '$this->competitor',
      '$this->competition',
      '$this->team',
      '$this->week',
      '$this->weight_loss',
      '$this->weight_loss_percent',
      '$this->weight_loss_overall',
      '$this->weight_loss_percent_overall',
      CURRENT_TIMESTAMP
    );";

  // echo('<pre>');
  // print_r($sql);
  // echo('</pre>');

  $result = $this->process_query($sql);
  }

// ***** PROCESS Query *****
  public function process_query($sql)
  {
    $result = mysqli_query($this->connection, $sql);
    return $result;
  }

  public function set_params($params)
  {
    $this->competitor = $params['competitor'];
    $this->team       = $params['team'];
    $this->week       = $params['week'];
  }

  public function set_weights($params, $weights)
  {
    $this->set_params($params);
    $this->begin      = $weights['begin'];
    $this->previous   = $weights['previous'];
    $this->current    = $weights['current'];
  }
} 
?>
