<?php 
// *** Get Weight Loss ***
function get_weight_loss($previous, $current)
{
  $weight_loss = $current - $previous;
  return number_format($weight_loss, 1);
}

// *** Get Weight Loss Percent ***
function get_weight_loss_percent($previous, $current)
{
  $weight_loss_percent = (get_weight_loss($previous, $current) / $previous) * 100;
  return number_format($weight_loss_percent, 6);
}

// *** Get Overall Weight Loss ***
function get_weight_loss_overall($begin, $current)
{
  $weight_loss_overall = $current - $begin;
  return number_format($weight_loss_overall, 1);
}

// *** Get Overall Weight Loss Percent ***
function get_weight_loss_percent_overall($begin, $current)
{
  $weight_loss_percent_overall = (get_weight_loss_overall($begin, $current) / $begin) * 100;
  return number_format($weight_loss_percent_overall, 6);
}

// *** Compute Results ***
function compute_results($weights)
{
  $begin    = $weights['begin'];
  $previous = $weights['previous'];
  $current  = $weights['current'];

  $weight_loss                  = get_weight_loss($previous, $current);
  $weight_loss_percent          = get_weight_loss_percent($previous, $current);
  $weight_loss_overall          = get_weight_loss_overall($begin, $current);
  $weight_loss_percent_overall  = get_weight_loss_percent_overall($begin, $current);

  $results = array(
    'weight_loss'                   =>        $weight_loss,
    'weight_loss_percent'           =>        $weight_loss_percent,
    'weight_loss_overall'           =>        $weight_loss_overall,
    'weight_loss_percent_overall'   =>        $weight_loss_percent_overall
  );

  return $results;
}

?>