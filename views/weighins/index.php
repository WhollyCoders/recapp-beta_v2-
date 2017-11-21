<?php 
require('./config.php');
$WeighIn = new WeighIn($connection);

$WeighIns = $WeighIn->get_all_weigh_ins();

    // echo('<pre>');
    // print_r($WeighIns);
    // echo('</pre>');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Weigh-Ins</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
      <table class="table">
        <tr>
          <th>ID</th>
          <th>Competition</th>
          <th>Competitor</th>
          <th>Team</th>
          <th>Week</th>
          <th>Weight</th>
          <th>Date Entered</th>
        </tr>
        <?php foreach ($WeighIns as $weigh_in) { ?>
        <tr>
          <td><?php echo($weigh_in['id']); ?></td>
          <td><?php echo($weigh_in['competition']); ?></td>
          <td><?php echo($weigh_in['competitor']); ?></td>
          <td><?php echo($weigh_in['team']); ?></td>
          <td><?php echo($weigh_in['week']); ?></td>
          <td><?php echo($weigh_in['weight']); ?></td>
          <td><?php echo($weigh_in['date_entered']); ?></td>
        </tr>
        <?php } ?>
      </table>
    </div>
  </body>
</html>