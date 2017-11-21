<?php 

// *** Insert Results Into Database ***
function insert_results($connection, $competitor, $competition, $wk, $team, $results)
{
  $weight_loss                  = $results['weight_loss'];
  $weight_loss_percent          = $results['weight_loss_percent'];
  $weight_loss_overall          = $results['weight_loss_overall'];
  $weight_loss_percent_overall  = $results['weight_loss_percent_overall'];
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
    '$competitor',
    '$competition',
    '$team',
    '$wk',
    '$weight_loss',
    '$weight_loss_percent',
    '$weight_loss_overall',
    '$weight_loss_percent_overall',
    CURRENT_TIMESTAMP
  );";

$result = mysqli_query($connection, $sql);
}

?>