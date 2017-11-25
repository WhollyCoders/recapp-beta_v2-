<?php
// require('../../__CONNECT/recapp-connect.php');
// require('./Result.php');
// require('../../modules/Result.php');
class CompetitionResult extends Result
{
    public $table_name  = 'competition_results';

    // *** Get BEGIN, PREVIOUS and CURRENT ***
    // *** Get Begin Weight ***
    protected function get_begin_weight()
    {
        $sql = "SELECT sum(weigh_in_weight) as begin_weight
        FROM `weighins` 
        WHERE weigh_in_week = '".$this->begin_week."' LIMIT 1;";
        $result = $this->process_query($sql);
        if($result){
        $row = mysqli_fetch_assoc($result);
        return $row['begin_weight'];
        }
    }  
    // *** Get Previous Weight ***
    protected function get_previous_weight()
    {
        $sql = "SELECT sum(weigh_in_weight) as previous_weight 
        FROM `weighins` 
        WHERE weigh_in_week = '".$this->previous_week."' LIMIT 1";
        $result = $this->process_query($sql);
        if($result)
        {
        $row = mysqli_fetch_assoc($result);
        return $row['previous_weight'];
        }
    }
    // *** Get Current Weight ***
    protected function get_current_weight()
    {
        $sql = "SELECT sum(weigh_in_weight) as current_weight  
        FROM `weighins` 
        WHERE weigh_in_week = '".$this->current_week."' LIMIT 1;";
        
        // echo('<pre>');
        // print_r($sql);
        // echo('</pre>');

        $result = $this->process_query($sql);
        if($result)
        {
        $row = mysqli_fetch_assoc($result);
        return $row['current_weight'];
        }
    }
    // *** Set Data ***
    public function set_data($data)
    {
        $this->week         = $data['week'];
        $this->set_weeks();
    } 
    // *** Insert Results Into Database ***
    public function insert_results()
    {
      $sql = "INSERT INTO `".$this->table_name."`(
        `competition_result_ID`,
        `competition_result_competition`,
        `competition_result_week`,
        `competition_result_begin_weight`,
        `competition_result_previous_weight`,
        `competition_result_current_weight`,
        `competition_result_weight_loss`,
        `competition_result_weight_loss_percent`,
        `competition_result_weight_loss_overall`,
        `competition_result_weight_loss_percent_overall`,
        `competition_result_date_entered`
      ) VALUES (
        NULL,
        '$this->competition',
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
    // *** Create Results Table ***
    public function create_table()
    {
          $sql = "CREATE TABLE IF NOT EXISTS `".$this->table_name."` (";
          $sql .= "`competition_result_ID` INT UNSIGNED NOT NULL AUTO_INCREMENT , ";
          $sql .= "`competition_result_competition` VARCHAR(100) NOT NULL , ";
          $sql .= "`competition_result_week` VARCHAR(100) NOT NULL , ";
          $sql .= "`competition_result_begin_weight` DECIMAL(8,1) NOT NULL , ";
          $sql .= "`competition_result_previous_weight` DECIMAL(8,1) NOT NULL , ";
          $sql .= "`competition_result_current_weight` DECIMAL(8,1) NOT NULL , ";
          $sql .= "`competition_result_weight_loss` DECIMAL(10,1) NOT NULL , ";
          $sql .= "`competition_result_weight_loss_percent` FLOAT(8,6) NOT NULL , ";
          $sql .= "`competition_result_weight_loss_overall` DECIMAL(10,1) NOT NULL , ";
          $sql .= "`competition_result_weight_loss_percent_overall` FLOAT(8,6) NOT NULL , ";
          $sql .= "`competition_result_date_entered` DATETIME NOT NULL , ";
          $sql .= "UNIQUE (`competition_result_competition`, `competition_result_week`), ";
          $sql .= "PRIMARY KEY (`competition_result_ID`)";
          $sql .= ") ENGINE = InnoDB;";
          // echo('<pre>');
          // print_r($sql);
          // echo('</pre>');
          $result = $this->process_query($sql);
    }
    // *** Post Results ***
    public function post_results($week)
    {

        $this->data = array(
            'week'        =>      $week
        );
        $this->get_results($this->data);
            
    }
    // *** Set Params ***
    public function set_params($params)
    {
      $this->week       = $params['week'];
    }

}

    // $week       = 'Week 1';

    // $data       = array(
    //                         'week'        =>      $week
    //                     );

    // $CompetitionResult = new CompetitionResult($connection);
    // $CompetitionResult->get_results($data);

    // echo('<pre>');
    // print_r($CompetitionResult);
    // echo('</pre>');

?>