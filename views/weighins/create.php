<?php 
if(isset($_POST['add_weigh_in']))
{

  require('./config.php');
  $params = array(
    'competitor_id'      =>    $competitor   = ucfirst(mysqli_real_escape_string($connection, trim($_POST['add_competitor']))),
    'competition_id'     =>    $competition  = ucfirst(mysqli_real_escape_string($connection, trim($_POST['add_competition']))),
    'team_id'            =>    $team         = ucfirst(mysqli_real_escape_string($connection, trim($_POST['add_team']))),
    'week_id'            =>    $week         = ucfirst(mysqli_real_escape_string($connection, trim($_POST['add_week']))),
    'weight'             =>    $weight       = mysqli_real_escape_string($connection, trim($_POST['add_weight']))
  );

  $WeighIn = new WeighIn($connection);
  // $WeighIn->create_weigh_in($params);

  // echo('<pre>');
  // print_r($WeighIn);
  // echo('</pre>');

  // echo('<pre>');
  // print_r($params);
  // echo('</pre><br>');

  $WeighIn->create_weigh_in($params);

  // echo('<pre>');
  // print_r($WeighIn);
  // echo('</pre>');

  header('Location: ./new.php');
}
?>