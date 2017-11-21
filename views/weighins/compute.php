<?php 
require('./config.php');
echo $week  = 3;
echo '<br>';
$WeighIn    = new WeighIn($connection);

    // echo('<pre>');
    // print_r($WeighIn );
    // echo('</pre><br>');

$WeighIn->set_weights(1, $week);   
echo('Beginning Weight: '.$WeighIn->begin.'<br>');
echo('Previous Weight:'.$WeighIn->previous.'<br>');
echo('Current Weight: '.$WeighIn->current.'<br><br>');

echo('Weight Loss: '.$WeighIn->get_weight_loss().'<br>');
echo('Weight Loss Percent: '.$WeighIn->get_weight_loss_percent().'<br>');
echo('Overall Weight Loss: '.$WeighIn->get_overall_weight_loss().'<br>');
echo('Overall Weight Loss Percent: '.$WeighIn->get_overall_weight_loss_percent().'<br>');

echo $current_weight_competition = 'CURRENT '.$WeighIn->total_weight_current_week($week).'<br>';
echo $previous_weight_competition = 'PREVIOUS '.$WeighIn->total_weight_previous_week($week).'<br>';

?>