<?php
// require('./config.php');
function get_team_begin($connection, $team)
{
  // *** Select ALL weighins WHERE team name = $team AND weigh_in_week = ORIENTATION
  $sql = "SELECT weigh_in_weight 
  FROM weighins
  WHERE weigh_in_team = '$team'
  AND weigh_in_week = 'Orientation';";
  $result = mysqli_query($connection, $sql);
  // *** Loop through results and compute SUM of weigh_in_weight
  if($result)
  {
    $team_begin = 0;
    while($row = mysqli_fetch_assoc($result))
    {
      $team_begin += $row['weigh_in_weight'];
    }

      // *** Return SUM
      return $team_begin;
  }
}

function get_team_current($connection, $team, $week)
{
  $week = 'Week '.$week;
  // *** Select ALL weighins WHERE team name = $team AND weigh_in_week = $week
  $sql = "SELECT weigh_in_weight 
  FROM weighins
  WHERE weigh_in_team = '$team'
  AND weigh_in_week = '$week';";
  $result = mysqli_query($connection, $sql);
  // *** Loop through results and compute SUM of weigh_in_weight
  if($result)
  {
    $team_current = 0;
    while($row = mysqli_fetch_assoc($result))
    {
      $team_current += $row['weigh_in_weight'];
    }
      // *** Return SUM
      return $team_current;
  }
}

function get_team_previous($connection, $team, $week)
{
  // *** Select ALL weighins WHERE team name = $team AND weigh_in_week = ($week - 1)
  if($week == 1){ return $team_previous = get_team_begin($connection, $team);}
  $week = $week - 1;
  $week = 'Week '.$week;
  // *** Select ALL weighins WHERE team name = $team AND weigh_in_week = $week
  $sql = "SELECT weigh_in_weight 
  FROM weighins
  WHERE weigh_in_team = '$team'
  AND weigh_in_week = '$week';";
  $result = mysqli_query($connection, $sql);
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

// $week           = 2;
// $team           = 'Mighty Mangoes';
// $team_begin     = get_team_begin($connection, $team);
// $team_previous  = get_team_previous($connection, $team, $week);
// $team_current   = get_team_current($connection, $team, $week);
// echo('Begin: '.$team_begin.'<br>');
// echo('Previous: '.$team_previous.'<br>');
// echo('Current: '.$team_current.'<br>');
?>