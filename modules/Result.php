<?php
// require('../../../__CONNECT/recapp-connect.php');
class Result
{
    public $db_name     = 'recapp';
    public $competition = 'Bod4God';
    public $table_name  = 'results';
    public $connection;
    public $begin;
    public $previous;
    public $current;
    public $begin_week;
    public $previous_week;
    public $current_week;
    public $weight_loss;
    public $weight_loss_percent;
    public $weight_loss_overall;
    public $weight_loss_percent_overall;
    public $competitor;
    public $team;
    public $week;
    public $data;
    public $json;
    
    public function __construct($connection)
    {
        $this->connection = $connection;
        // $this->drop_table();
        $this->create_table();
    }
    // *** Get Results ***
    public function get_results($data)
    {
        $this->set_data($data);
        $this->set_weights();
        $this->compute_results();
        $this->insert_results();
        // echo('Success!!!<br>');
    }
    // *** Post Results ***
    public function post_results($week)
    {
        // $this->drop_table();
        $sql = "SELECT * 
                FROM weighins
                WHERE weigh_in_week='$week';";

        $result = $this->process_query($sql);
        if($result){
            while($row = mysqli_fetch_assoc($result)){
                $this->data = array(
                    'competitor'  =>      $row['weigh_in_competitor'],
                    'team'        =>      $row['weigh_in_team'],
                    'week'        =>      $row['weigh_in_week']
                );
                $this->get_results($this->data);
            }
        }
    }
    // *** Set Weights ***
    public function set_weights()
    {
        $this->begin    = $this->get_begin_weight();
        $this->previous = $this->get_previous_weight();
        $this->current  = $this->get_current_weight();
    }
    // *** Get BEGIN, PREVIOUS and CURRENT ***
    // *** Get Begin Weight ***
    protected function get_begin_weight()
    {
        $sql = "SELECT weigh_in_weight FROM `weighins` 
        WHERE weigh_in_competitor = '".$this->competitor."'
        AND weigh_in_competition = '".$this->competition."'
        AND weigh_in_week = '".$this->begin_week."' LIMIT 1;";
        $result = $this->process_query($sql);
        if($result){
        $row = mysqli_fetch_assoc($result);
        return $row['weigh_in_weight'];
        }
    }  
    // *** Get Previous Weight ***
    protected function get_previous_weight()
    {
        $sql = "SELECT weigh_in_weight FROM `weighins` 
        WHERE weigh_in_competitor = '".$this->competitor."' 
        AND weigh_in_competition = '".$this->competition."'
        AND weigh_in_week = '".$this->previous_week."' LIMIT 1";
        $result = $this->process_query($sql);
        if($result)
        {
        $row = mysqli_fetch_assoc($result);
        return $row['weigh_in_weight'];
        }
    }
    // *** Get Current Weight ***
    protected function get_current_weight()
    {
        $sql = "SELECT weigh_in_weight FROM `weighins` 
        WHERE weigh_in_competitor = '".$this->competitor."' 
        AND weigh_in_competition = '".$this->competition."'
        AND weigh_in_week = '".$this->current_week."' LIMIT 1";
        $result = $this->process_query($sql);
        if($result)
        {
        $row = mysqli_fetch_assoc($result);
        return $row['weigh_in_weight'];
        }
    }
    // *** Set Data ***
    public function set_data($data)
    {
        $this->competitor   = $data['competitor'];
        $this->team         = $data['team'];
        $this->week         = $data['week'];
        $this->set_weeks();
    }
    // *** Set Weeks ***
    public function set_weeks()
    {
        $this->begin_week = 'Orientation';
        $this->previous_week = $this->get_previous_week();
        $this->current_week = $this->week;
    }
    // *** Get Previous Week ***
    public function get_previous_week()
    {
        if($this->week == 'Week 1'){
            return $week = 'Orientation';
        }
        $week = $this->extract_week($this->week);
        return 'Week '.($week - 1);
    }
    // *** Extract Week Number ***
    public function extract_week($week)
    {
        $week = explode(' ', $week);
        return $week[1];
    }
    // *** Compute Results ***
    public function compute_results()
    {

        $this->weight_loss                  = $this->get_weight_loss();
        $this->weight_loss_percent          = $this->get_weight_loss_percent();
        $this->weight_loss_overall          = $this->get_weight_loss_overall();
        $this->weight_loss_percent_overall  = $this->get_weight_loss_percent_overall();

        $results = array(
            'weight_loss'                 =>      $this->weight_loss,
            'weight_loss_percent'         =>      $this->weight_loss_percent,
            'weight_loss_overall'         =>      $this->weight_loss_overall,
            'weight_loss_percent_overall' =>      $this->weight_loss_percent_overall
            );

        return $results;
    }
    // *** RESULTS ***
    // *** Get Weight Loss ***
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
    public function get_weight_loss_overall()
    {
        $result = $this->current - $this->begin;
        return number_format($result, 1);
    }
    public function get_weight_loss_percent_overall()
    {
        $result = ($this->get_weight_loss_overall() / $this->begin) * 100;
        return number_format($result, 6);
    }
    // ***** PROCESS Query *****
    public function process_query($sql){
        $result = mysqli_query($this->connection, $sql);
        return $result;
    }
    // *** Insert Results Into Database ***
    public function insert_results()
    {
    $sql = "INSERT INTO `".$this->table_name."`(
        `result_ID`,
        `result_competition`,
        `result_competitor`,
        `result_team`,
        `result_week`,
        `result_begin_weight`,
        `result_previous_weight`,
        `result_current_weight`,
        `result_weight_loss`,
        `result_weight_loss_percent`,
        `result_weight_loss_overall`,
        `result_weight_loss_percent_overall`,
        `result_date_entered`
    ) VALUES (
        NULL,
        '$this->competition',
        '$this->competitor',
        '$this->team',
        '$this->week',
        '$this->begin',
        '$this->previous',
        '$this->current',
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
    // *** Create Table ***
    public function create_table()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `".$this->table_name."` (";
        $sql .= "`result_ID` INT UNSIGNED NOT NULL AUTO_INCREMENT , ";
        $sql .= "`result_competition` VARCHAR(100) NOT NULL , ";
        $sql .= "`result_competitor` VARCHAR(100) NOT NULL , ";
        $sql .= "`result_team` VARCHAR(100) NOT NULL , ";
        $sql .= "`result_week` VARCHAR(100) NOT NULL , ";
        $sql .= "`result_begin_weight` DECIMAL(4,1) NOT NULL , ";
        $sql .= "`result_previous_weight` DECIMAL(4,1) NOT NULL , ";
        $sql .= "`result_current_weight` DECIMAL(4,1) NOT NULL , ";
        $sql .= "`result_weight_loss` DECIMAL(4,1) NOT NULL , ";
        $sql .= "`result_weight_loss_percent` FLOAT(8,6) NOT NULL , ";
        $sql .= "`result_weight_loss_overall` DECIMAL(4,1) NOT NULL , ";
        $sql .= "`result_weight_loss_percent_overall` FLOAT(8,6) NOT NULL , ";
        $sql .= "`result_date_entered` DATETIME NOT NULL , ";
        $sql .= "UNIQUE (`result_competitor`, `result_week`), ";
        $sql .= "PRIMARY KEY (`result_ID`)";
        $sql .= ") ENGINE = InnoDB;";
        $result = $this->process_query($sql);
    }
    // *** Drop Table ***
    public function drop_table()
    {
        $sql = "DROP TABLE `".$this->table_name."`;";
        
    // echo('<pre>');
    // print_r($sql);
    // echo('</pre>');

        $result = $this->process_query($sql);
    }
}



// $competitor = 'Michael Parks';
// $team       = 'Mighty Mangoes';
// $week       = 'Week 1';

// $data       = array(
//                         'competitor'  =>      $competitor,
//                         'team'        =>      $team,
//                         'week'        =>      $week
//                     );

// $Result = new Result($connection);
// $Result->get_results($data);

// echo('<pre>');
// print_r($Result);
// echo('</pre>');

 ?>