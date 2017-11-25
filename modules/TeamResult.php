<?php
// require('../../__CONNECT/recapp-connect.php');
// require('../../modules/Result.php');
// require('./Result.php');
class TeamResult extends Result
{
    public $table_name  = 'team_results';

    // *** Get BEGIN, PREVIOUS and CURRENT ***
    // *** Get Begin Weight ***
    protected function get_begin_weight()
    {
        $sql = "SELECT sum(weigh_in_weight) as begin_weight
        FROM `weighins` 
        WHERE weigh_in_team_name = '".$this->team."'
        AND weigh_in_competition_name = '".$this->competition."'
        AND weigh_in_week = '".$this->begin_week."' LIMIT 1;";
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
        WHERE weigh_in_team_name = '".$this->team."'
        AND weigh_in_competition_name = '".$this->competition."'
        AND weigh_in_week = '".$this->previous_week."' LIMIT 1";
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
        WHERE weigh_in_team_name = '".$this->team."'
        AND weigh_in_competition_name = '".$this->competition."'
        AND weigh_in_week = '".$this->current_week."' LIMIT 1";
        $result = $this->process_query($sql);
        if($result)
        {
        $row = mysqli_fetch_assoc($result);
        return $row['current_weight'];
        }
    }
    // *** Post Results ***
    public function post_results($week)
    {
        $Team = new Team($this->connection);
        $Teams = $Team->get_teams();
        foreach ($Teams as $team) {
           
            $data       = array(
                'team'        =>      $team['name'],
                'week'        =>      $week
            );

            $this->get_results($data);
        }
                
    }
    // *** Set Data ***
    public function set_data($data)
    {
        $this->team         = $data['team'];
        $this->week         = $data['week'];
        $this->set_weeks();
    } 
    // *** Insert Results Into Database ***
    public function insert_results()
    {
      $sql = "INSERT INTO `".$this->table_name."`(
        `team_result_ID`,
        `team_result_competition`,
        `team_result_team`,
        `team_result_week`,
        `team_result_begin_weight`,
        `team_result_previous_weight`,
        `team_result_current_weight`,
        `team_result_weight_loss`,
        `team_result_weight_loss_percent`,
        `team_result_weight_loss_overall`,
        `team_result_weight_loss_percent_overall`,
        `team_result_date_entered`
      ) VALUES (
        NULL,
        '$this->competition',
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
    // *** Create Results Table ***
    public function create_table()
    {
          $sql = "CREATE TABLE IF NOT EXISTS `".$this->table_name."` (";
          $sql .= "`team_result_ID` INT UNSIGNED NOT NULL AUTO_INCREMENT , ";
          $sql .= "`team_result_competition` VARCHAR(100) NOT NULL , ";
          $sql .= "`team_result_team` VARCHAR(100) NOT NULL , ";
          $sql .= "`team_result_week` VARCHAR(100) NOT NULL , ";
          $sql .= "`team_result_begin_weight` DECIMAL(6,1) NOT NULL , ";
          $sql .= "`team_result_previous_weight` DECIMAL(6,1) NOT NULL , ";
          $sql .= "`team_result_current_weight` DECIMAL(6,1) NOT NULL , ";
          $sql .= "`team_result_weight_loss` DECIMAL(4,1) NOT NULL , ";
          $sql .= "`team_result_weight_loss_percent` FLOAT(8,6) NOT NULL , ";
          $sql .= "`team_result_weight_loss_overall` DECIMAL(4,1) NOT NULL , ";
          $sql .= "`team_result_weight_loss_percent_overall` FLOAT(8,6) NOT NULL , ";
          $sql .= "`team_result_date_entered` DATETIME NOT NULL , ";
          $sql .= "UNIQUE (`team_result_team`, `team_result_week`), ";
          $sql .= "PRIMARY KEY (`team_result_ID`)";
          $sql .= ") ENGINE = InnoDB;";
          // echo('<pre>');
          // print_r($sql);
          // echo('</pre>');
          $result = $this->process_query($sql);
    }
    // *** Set Params ***
    public function set_params($params)
    {
      $this->team       = $params['team'];
      $this->week       = $params['week'];
    }

    
}

    // $team       = 'Mighty Mangoes';
    // $week       = 'Week 1';

    // $data       = array(
    //                         'team'        =>      $team,
    //                         'week'        =>      $week
    //                     );

    // $TeamResult = new TeamResult($connection);
    // $TeamResult->get_results($data);

    // echo('<pre>');
    // print_r($TeamResult);
    // echo('</pre>');

?>