<?php 
// *** Extract Week Number ***
function extract_week($week)
{
  $week = explode(' ', $week);
  return $week[1];
}
// *** Pre-Format Wrapper ***
function prewrap($array)
{
  echo('<pre>');
  print_r($array);
  echo('</pre>');
}
// ***** Get Begin Weight *****
function get_begin_weight($connection, $competitor, $competition)
{
  $sql = "SELECT weigh_in_weight FROM `weighins` 
  WHERE weigh_in_competitor = '$competitor'
  AND weigh_in_competition = '$competition'
  AND weigh_in_week = 'Orientation' LIMIT 1;";
  $result = mysqli_query($connection, $sql);
  if($result){
    $row = mysqli_fetch_assoc($result);
    return $row['weigh_in_weight'];
  }
}

function get_previous_weight($connection, $competitor, $competition, $wk)
{
  $wk = extract_week($wk);
  $sql = "SELECT weigh_in_weight FROM `weighins` 
  WHERE weigh_in_competitor = '$competitor' 
  AND weigh_in_competition = '$competition'
  AND weigh_in_week = 'Week ".($wk - 1)."' LIMIT 1";
  $result = mysqli_query($connection, $sql);
  if($result)
  {
    $row = mysqli_fetch_assoc($result);
    return $row['weigh_in_weight'];
  }
}

function get_current_weight($connection, $competitor, $competition, $wk)
{
  $sql = "SELECT weigh_in_weight FROM `weighins` 
  WHERE weigh_in_competitor = '$competitor' 
  AND weigh_in_competition = '$competition'
  AND weigh_in_week = '$wk' LIMIT 1";
  $result = mysqli_query($connection, $sql);
  if($result)
  {
    $row = mysqli_fetch_assoc($result);
    return $row['weigh_in_weight'];
  }
}

function get_weights($connection, $competitor, $competition, $wk)
{
  $begin    = get_begin_weight($connection, $competitor, $competition);
  $previous = get_previous_weight($connection, $competitor, $competition, $wk);
  $current  = get_current_weight($connection, $competitor, $competition, $wk);
  $weights  = array(
    'begin'       =>    $begin,
    'previous'    =>    $previous,
    'current'     =>    $current
  );

  return $weights;
}

// $competitor     = 'Michael Parks';
// $competition    = 'Bod4God';
// $week           = 'Week 3';

// $weights = get_weights($connection, $competitor, $competition, $week);

// prewrap($weights);

?>