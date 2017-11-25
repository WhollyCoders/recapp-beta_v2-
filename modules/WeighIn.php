<?php
include_once('../../modules/Competitor.php');
include_once('../../modules/Competition.php');
include_once('../../modules/Team.php');
include_once('../../modules/Week.php');
include_once('../../modules/Result.php');
include_once('../../modules/TeamResult.php');
include_once('../../modules/CompetitionResult.php');
include_once('../../modules/File.php');
class WeighIn{
  public $connection;
  public $db_name     = 'recapp_v2';
  public $table_name  = 'weighins';
  public $col_name;
  public $id;
  public $competitor_id;
  public $competitor_name;
  public $competition_id;
  public $competition_name;
  public $team_id;
  public $team_name;
  public $week;
  public $weight;
  public $date_entered;
  public $Competitor;
  public $Competition;
  public $Team;
  public $Week;
  public $Result;
  public $begin;
  public $previous;
  public $current;
  public $data;
  public $json;

  public function __construct($connection)
  {
    $this->connection           = $connection;
    $this->Competitor           = new Competitor($this->connection);
    $this->Competition          = new Competition($this->connection);
    $this->Team                 = new Team($this->connection);
    $this->Week                 = new Week($this->connection);
    $this->create_table();
  }

// ***** ADD/INSERT New Weigh-In *****
  public function add_weigh_in()
  {

    $sql = "INSERT INTO `".$this->table_name."`(
      `weigh_in_ID`,
      `weigh_in_competitor_ID`,
      `weigh_in_competitor_name`,
      `weigh_in_competition_ID`,
      `weigh_in_competition_name`,
      `weigh_in_team_ID`,
      `weigh_in_team_name`,
      `weigh_in_week`,
      `weigh_in_weight`,
      `weigh_in_date_entered`
    ) VALUES (
      NULL,
      '$this->competitor_id',
      '$this->competitor_name',
      '$this->competition_id',
      '$this->competition_name',
      '$this->team_id',
      '$this->team_name',
      '$this->week',
      '$this->weight',
      CURRENT_TIMESTAMP
    );";
                // echo('<pre>');
                // print_r($sql);
                // echo('</pre>'); 

    $this->process_query($sql);
  }

// ***** Create Weigh-Ins Table *****
  public function create_table()
  {
    $sql = "CREATE TABLE IF NOT EXISTS ".$this->table_name." ( ";
    $sql .= "`weigh_in_ID` INT UNSIGNED NOT NULL AUTO_INCREMENT , ";
    $sql .= "`weigh_in_competitor_ID` INT UNSIGNED NOT NULL , ";
    $sql .= "`weigh_in_competitor_name` VARCHAR(100) NULL , ";
    $sql .= "`weigh_in_competition_ID` INT UNSIGNED NOT NULL , ";
    $sql .= "`weigh_in_competition_name` VARCHAR(100) NULL , ";
    $sql .= "`weigh_in_team_ID` INT UNSIGNED NOT NULL , ";
    $sql .= "`weigh_in_team_name` VARCHAR(100) NULL , ";
    $sql .= "`weigh_in_week` VARCHAR(100) NULL , ";
    $sql .= "`weigh_in_weight` DECIMAL(4,1) NULL , ";
    $sql .= "`weigh_in_date_entered` DATETIME NOT NULL , ";
    $sql .= "UNIQUE (`weigh_in_competitor_ID`, `weigh_in_week`), ";
    $sql .= "PRIMARY KEY (`weigh_in_ID`)";
    $sql .= ") ENGINE = InnoDB;";
                // echo('<pre>');
                // print_r($sql);
                // echo('</pre>'); 
    $result = $this->process_query($sql);
    if(!$result){ echo('There has been an ERROR creating Weigh_Ins Table!!!<br>'); }
  }

          // ***** Create Weigh In *****
            public function create_weigh_in($params)
            {
              $this->competitor_id    = $params['competitor_id'];
              $this->competitor_name  = $this->get_competitor_name($params['competitor_id']);
              $this->competition_id   = $params['competition_id'];
              $this->competition_name = $this->get_competition_name($params['competition_id']);
              $this->team_id          = $params['team_id'];
              $this->team_name        = $this->get_team_name($params['team_id']);
              $this->week             = $this->get_week_name($params['week_id']);
              $this->weight           = $params['weight'];
              $this->add_weigh_in();
            }

// ***** Delete Weigh In *****
  public function delete_weigh_in($id){
    $sql = "DELETE FROM `".$this->table_name."` WHERE weigh_in_ID=$id;";
    $this->process_query($sql);
  }

// ***** Destroy Weigh In *****
  public function destroy_weigh_in($id){
    $this->delete_weigh_in($id);
  }
// ***** Edit Weigh In ***
  public function edit_weigh_in(){
    $sql = "UPDATE `".$this->table_name."`
    SET `weigh_in_competitor` = '$this->competitor',
    `weigh_in_competition` = '$this->competition',
    `weigh_in_team` = '$this->team',
    `weigh_in_week` = '$this->week',
    `weigh_in_weight` = '$this->weight'
    WHERE `".$this->table_name."`.`weigh_in_ID`=$this->id;";
 
    $result = $this->process_query($sql);
  }

// *** Extract Week Number ***
  public function extract_week($week)
  {
    $week = explode(' ', $week);
    return $week[1];
  }

// ***** GET ALL Weigh-Ins *****
  public function get_all_weigh_ins(){
    $sql = "SELECT * FROM ".$this->table_name." ORDER BY weigh_in_week ASC, weigh_in_weight DESC;";
    $result = $this->process_query($sql);
    if($result){
      return $this->get_data($result);
    }
  }

// ***** GET Beginning Weight *****
  public function get_begin_weight(){
    $sql = "SELECT weigh_in_weight FROM `weighins` 
    WHERE weigh_in_competitor = '".$this->competitor."'
    AND weigh_in_week = 'Orientation' LIMIT 1;";
    $result = $this->process_query($sql);
    if($result){
      $row = mysqli_fetch_assoc($result);
      return $row['weigh_in_weight'];
    }
  }

// *** Get Column Name ***
  public function get_column_name(){
    return $this->col_name;
  }

// ***** GET Competition Name *****
  public function get_competition_name($id){
    $competition = $this->Competition->get_competition_by_id($id);
    return $competition[0]['name'];
  }

// *** Get Competitors ***
  public function get_competitions()
  {
    return $this->Competition->get_competitions();
  }

// ***** GET Competitor's Name *****
  public function get_competitor_name($id){
    $competitor = $this->Competitor->get_competitor_by_id($id);
    return $competitor['firstname'].' '.$competitor['lastname'];
  }

// *** Get Competitors ***
  public function get_competitors()
  {
    return $this->Competitor->get_all_competitors();
  }

// *** Get Current Week ***
  public function get_current_week($week)
  {
    return $current_week = 'Week '.$week;
  }  
// ***** GET Weigh-In Data *****
  public function get_data($result){
    $this->data = array();
    $rows = $this->get_rows($result);
    if($rows == 1){
      $row = mysqli_fetch_assoc($result);
      $this->data = array(
        'id'               =>      $row['weigh_in_ID'],
        'competitor'       =>      $row['weigh_in_competitor'],
        'competition'      =>      $row['weigh_in_competition'],
        'team'             =>      $row['weigh_in_team'],
        'week'             =>      $row['weigh_in_week'],
        'weight'           =>      $row['weigh_in_weight'],
        'date_entered'     =>      $row['weigh_in_date_entered']
      );
      $this->json = json_encode($this->data);
    }else{
      while($row = mysqli_fetch_assoc($result)){
        $this->data[] = array(
          'id'               =>      $row['weigh_in_ID'],
          'competitor'       =>      $row['weigh_in_competitor'],
          'competition'      =>      $row['weigh_in_competition'],
          'team'             =>      $row['weigh_in_team'],
          'week'             =>      $row['weigh_in_week'],
          'weight'           =>      $row['weigh_in_weight'],
          'date_entered'     =>      $row['weigh_in_date_entered']
        );
      }
      $this->json = json_encode($this->data);
    }
    return $this->data;
  }
// *** Get Next Competitor ***
  public function get_next($id){
    $this->id = $id;
    $sql = "SELECT * FROM weigh_ins
    WHERE weigh_in_ID > '$this->id'
    ORDER BY weigh_in_ID ASC LIMIT 1;";
    $result = mysqli_query($this->connection, $sql);
    if($result){
      $weigh_in = $this->get_weigh_in_data($result);
      if(!$weigh_in['id']){
        return $this->id;
      }
      return $weigh_in['id'];
    }
  }
// *** Get Previous Competitor ***
  public function get_previous($id){
    $this->id = $id;
    $sql = "SELECT * FROM weigh_ins
    WHERE weigh_in_ID < '$this->id'
    ORDER BY weigh_in_ID DESC LIMIT 1;";
    $result = mysqli_query($this->connection, $sql);
    if($result){
      $weigh_in = $this->get_weigh_in_data($result);
      if(!$weigh_in['id']){
        return $this->id;
      }
      return $weigh_in['id'];
    }
  }
// *** Get Team Results Data ***
public function get_team_results_data($result)
{
  $this->data = array();
  while($row = mysqli_fetch_assoc($result))
  {
    $this->data[] = array(
      'id'                          =>      $row['team_result_ID'],
      'competition'                 =>      $row['team_result_competition'],
      'team'                        =>      $row['team_result_team'],
      'week'                        =>      $row['team_result_week'],
      'begin_weight'                =>      $row['team_result_begin_weight'],
      'previous_weight'             =>      $row['team_result_previous_weight'],
      'current_weight'              =>      $row['team_result_current_weight'],
      'weight_loss'                 =>      $row['team_result_weight_loss'],
      'weight_loss_percent'         =>      $row['team_result_weight_loss_percent'],
      'weight_loss_overall'         =>      $row['team_result_weight_loss_overall'],
      'weight_loss_percent_overall' =>      $row['team_result_weight_loss_percent_overall'],
      'date_entered'                =>      $row['team_result_date_entered']
    );
  }
  $this->json = json_encode($this->data);
  return $this->data;
}
// *** Get Results Data ***
  public function get_results_data($result)
  {
    $this->data = array();
    while($row = mysqli_fetch_assoc($result))
    {
      $this->data[] = array(
        'id'                          =>      $row['result_ID'],
        'competitor'                  =>      $row['result_competitor'],
        'competition'                 =>      $row['result_competition'],
        'team'                        =>      $row['result_team'],
        'week'                        =>      $row['result_week'],
        'weight_loss'                 =>      $row['result_weight_loss'],
        'weight_loss_percent'         =>      $row['result_weight_loss_percent'],
        'weight_loss_overall'         =>      $row['result_weight_loss_overall'],
        'weight_loss_percent_overall' =>      $row['result_weight_loss_percent_overall'],
        'date_entered'                =>      $row['result_date_entered']
      );
    }
    $this->json = json_encode($this->data);
    return $this->data;
  }

// ***** GET Row Count *****
  public function get_rows($result){
    return $rows = mysqli_num_rows($result);
  }

// ***** Get DB Table Reference *****
  public function get_table_ref(){
    return "`$this->db_name`".'.'."`$this->table_name`";
  }

// ***** GET Team Name *****
  public function get_team_name($id){
    $team = $this->Team->get_team_by_id($id);
    return $team[0]['name'];
  }

// *** Get Teams ***
  public function get_teams()
  {
    return $this->Team->get_teams();
  }

// ***** GET Week Number *****
  public function get_week($id){
    $week = $this->Week->get_week_number($id);
    return $week['code'];
  }
// *** Get Week Ending Date ***
  public function get_week_ending_date($week)
  {
    $sql = "SELECT week_begin_date
            FROM `weeks`
            WHERE week_name='$week'
            LIMIT 1;";

                // echo('<pre>');
                // print_r($sql);
                // echo('</pre>'); 

    $result = $this->process_query($sql);
    if($result)
    {
      $row = mysqli_fetch_assoc($result);
      return $row['week_begin_date'];
    }
  }
// ***** GET Week Name By ID *****
  public function get_week_name($id){
    $week = $this->Week->get_week_by_id($id);
    return $week['name'];
  }
// *** Get Weekly Overall Weight Loss Leaders ***
  public function get_weekly_overall_weight_loss_leaders($week)
  {
    // $week = 'Week '.$week;
    $sql = "SELECT * FROM `results`
            WHERE result_week='$week'
            ORDER BY result_weight_loss_percent_overall
            LIMIT 10;";

    // echo('<pre>');
    // print_r($sql);
    // echo('</pre>');    

    $result = $this->process_query($sql);
    if($result)
    {
      return $this->get_results_data($result);
    }
  }
// Get Weekly Weight Loss Competition - Week
  public function get_weekly_weight_loss_competition($week)
  {
    $sql = "SELECT competition_result_weight_loss 
            FROM `competition_results`
            WHERE competition_result_week='$week'
            LIMIT 1;";

    // echo('<pre>');
    // print_r($sql);
    // echo('</pre>'); 

    $result = $this->process_query($sql);
    if($result)
    {
      $row = mysqli_fetch_assoc($result);
      return $row['competition_result_weight_loss'];
    }
  }
// *** Get Weekly Weight Loss Leaders ***
  public function get_weekly_weight_loss_leaders($week)
  {
    // $week = 'Week '.$week;
    $sql = "SELECT * FROM `results`
            WHERE result_week='$week'
            ORDER BY result_weight_loss_percent
            LIMIT 10;";

    // echo('<pre>');
    // print_r($sql);
    // echo('</pre>');    

    $result = $this->process_query($sql);
    if($result)
    {
      return $this->get_results_data($result);
    }
  }  
// *** Get Weekly Team Weight Loss Leaders ***  
  public function get_weekly_weight_loss_leaders_team($week)
  {
    $sql = "SELECT * FROM `team_results`
            WHERE team_result_week='$week'
            ORDER BY team_result_weight_loss_percent ASC
            LIMIT 3;";

    // echo('<pre>');
    // print_r($sql);
    // echo('</pre>');    

    $result = $this->process_query($sql);
    if($result)
    {
      return $this->get_team_results_data($result);
    }
  }
// *** Get Weekly Team Weight Loss Leaders - Overall ***
public function get_weekly_weight_loss_leaders_team_overall($week)
{
    $sql = "SELECT * FROM `team_results`
    WHERE team_result_week='$week'
    ORDER BY team_result_weight_loss_percent_overall ASC
    LIMIT 3;";

  // echo('<pre>');
  // print_r($sql);
  // echo('</pre>');    

  $result = $this->process_query($sql);
  if($result)
  {
  return $this->get_team_results_data($result);
  }
}
// Get Weekly Weight Loss Competition - Week
  public function get_weekly_weight_loss_overall($week)
  {
    $sql = "SELECT competition_result_weight_loss_overall 
            FROM `competition_results`
            WHERE competition_result_week='$week'
            LIMIT 1;";

    // echo('<pre>');
    // print_r($sql);
    // echo('</pre>'); 

    $result = $this->process_query($sql);
    if($result)
    {
      $row = mysqli_fetch_assoc($result);
      return $row['competition_result_weight_loss_overall'];
    }
  }
// ***** GET Weeks *****
  public function get_weeks(){
    return $this->Week->get_all_weeks();
  }

// ***** GET Weigh-In By ID *****
  public function get_weigh_in_by_id($id){
    $sql = "SELECT * FROM `weigh_ins-170907` WHERE weigh_in_ID='$id';";
    // prewrap($sql);
    $result = mysqli_query($this->connection, $sql);
    if($result){
      return $this->get_data($result);
    }
  }

// ***** GET Weigh-In By Week Name *****
  public function get_weigh_in_by_week($week){
    $weigh_in_week = 'Week '.$week;
    $sql = "SELECT * FROM `".$this->table_name."` WHERE weigh_in_week='$weigh_in_week';";
    // prewrap($sql);
    $result = mysqli_query($this->connection, $sql);
    if($result){
      return $this->get_data($result);
    }
  }

// *** Get Weight Loss ***
  public function get_weight_loss()
  {
    $this->weight_loss = $this->previous - $this->current;
    return number_format($this->weight_loss, 1);
  }

// *** Get Weight Loss Competition ***
  public function get_weight_loss_competition_week($week)
  { 
    $weight_loss =  $this->get_previous_weight_competition($week) - $this->get_current_weight_competition($week);
    return number_format($weight_loss, 1);
  }

// *** Get Weight Loss Percent ***
  public function get_weight_loss_percent()
  {
    $this->weight_loss_percent = ($this->get_weight_loss() / $this->previous) * 100;
    return number_format($this->weight_loss_percent, 6);
  }

// *** Get Weights ***
  public function get_weights()
  {
    $sql = "SELECT * FROM `weighins;";
    return $result = mysqli_query($this->connection, $sql);
  }

// *** Insert Results Into Database ***
  public function insert_results()
  {
    $sql = "INSERT INTO `results`(
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

    $this->process_query($sql);
  }
// *** Most Raw Pounds ***
  public function most_raw_pounds($week)
  {
    // $week = 'Week '.$week;
    $sql = "SELECT * FROM `results`
    WHERE result_week='$week'
    ORDER BY result_weight_loss_overall
    LIMIT 10;";

// echo('<pre>');
// print_r($sql);
// echo('</pre>');    

$result = $this->process_query($sql);
if($result)
{
return $this->get_results_data($result);
}
  }
// *** Overall Biggest Loser - Week ***
  public function overall_biggest_loser_week($week)
  {
    // $week = 'Week '.$week;
    $sql = "SELECT * FROM `results`
            WHERE result_week='$week'
            ORDER BY result_weight_loss_percent_overall
            LIMIT 1;";

    // echo('<pre>');
    // print_r($sql);
    // echo('</pre>');    

    $result = $this->process_query($sql);
    if($result)
    {
      $row = mysqli_fetch_array($result);
      return $this->data = array(
        'id'                          =>      $row['result_ID'],
        'competitor'                  =>      $row['result_competitor'],
        'competition'                 =>      $row['result_competition'],
        'team'                        =>      $row['result_team'],
        'week'                        =>      $row['result_week'],
        'weight_loss'                 =>      $row['result_weight_loss'],
        'weight_loss_percent'         =>      $row['result_weight_loss_percent'],
        'weight_loss_overall'         =>      $row['result_weight_loss_overall'],
        'weight_loss_percent_overall' =>      $row['result_weight_loss_percent_overall'],
        'date_entered'                =>      $row['result_date_entered']
      );
    }
  }
// ***** PROCESS Query *****
  public function process_query($sql){
    $result = mysqli_query($this->connection, $sql);
    return $result;
  }

// ***** Update Weigh In *****
  public function update_weigh_in($params){
    $this->id             = $params['id'];
    $this->competitor_id  = $params['competitor_id'];
    $this->competition_id = $params['competition_id'];
    $this->team_id        = $params['team_id'];
    $this->week_id        = $params['week_id'];
    $this->weight         = $params['weight'];
    $this->edit_weigh_in();
  }
// *** Reset Tables ***
  public function reset_tables()
  {
    $sql = "DROP TABLE `competition_results`;";
    $result = $this->process_query($sql);
    $sql = "DROP TABLE `team_results`;";
    $result = $this->process_query($sql);
    $sql = "DROP TABLE `results`;";
    $result = $this->process_query($sql);
  }
/// *** Set Weights ***
  public function set_weights()
  {
    $this->begin    = $this->get_begin_weight($this->competitor_id);
    $this->previous = $this->get_previous_weight($this->competitor_id, $this->week_id);
    $this->current  = $this->get_current_weight($this->competitor_id, $this->week_id);
  }
// *** Top 10 Overall ***
  public function top_ten_overall($week)
  {
        $sql = "SELECT * FROM `results`
        WHERE result_week='$week'
        ORDER BY result_weight_loss_percent_overall
        LIMIT 10;";

        // echo('<pre>');
        // print_r($sql);
        // echo('</pre>');    

        $result = $this->process_query($sql);
        if($result)
        {
          return $this->get_results_data($result);
        }
  }
// *** Total Weight Loss - Beginning Week
  public function total_weight_begin_week($week)
  {
    $sql = "SELECT weigh_in_weight
    FROM `weighins`
    WHERE weigh_in_week = 'Orientation';";

    $result = $this->process_query($sql);
    if($result)
    {
      while($row = mysqli_fetch_assoc($result))
      {
        $this->begin_total += $row['weigh_in_weight'];
      }
        return number_format($this->begin_total, 2);
    }
  }
  
// *** Total Weight Loss - Current Week
  public function total_weight_current_week($week)
  {
    $current_week = $this->get_current_week($week);

    $sql = "SELECT weigh_in_weight
    FROM `weighins`
    WHERE weigh_in_week = '$current_week';";

    $result = $this->process_query($sql);

    if($result)
    {
      while($row = mysqli_fetch_assoc($result))
      {
        $this->current_total += $row['weigh_in_weight'];
      }
        return number_format($this->current_total, 2);
    }
  }

// *** Total Weight Loss - Previous Week
  public function total_weight_previous_week($week)
  {
    $previous_week = $this->get_previous_week($week);
    $sql = "SELECT weigh_in_weight
    FROM `weighins`
    WHERE weigh_in_week = '$previous_week';";

    $result = $this->process_query($sql);
    if($result)
    {
      while($row = mysqli_fetch_assoc($result))
      {
        $this->previous_total += $row['weigh_in_weight'];
      }
        return number_format($this->previous_total, 2);
    }
  }

}
?>
