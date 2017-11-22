<?php
require('./config.php');
$Competitor = new Competitor($connection);
$Competitors = $Competitor->get_all_competitors();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Competitors</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <!-- Custom styles for this template -->
    <link href="../../assets/css/custom.css" rel="stylesheet">
  </head>
  <body>
    <div class="container">
      <h1>Competitors</h1>
      <table class="table table-striped table-bordered table-condensed table-hover">
      <tr>
        <th>ID</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Team</th>
      </tr>
      <?php
      foreach($Competitors as $competitor){
        ?>
        <tr>
          <td><?php echo($competitor['id']);?></td>
          <td><?php echo($competitor['firstname']);?></td>
          <td><?php echo($competitor['lastname']);?></td>
          <td><?php echo($competitor['team_name']);?></td>
          <td><a class="btn btn-primary" href="./edit.php?id=<?php echo($competitor['id']);?>">View Detail</a></td>
          <!-- <td><a class="btn btn-danger" href="./delete.php?id=<?php echo($competitor['id']);?>">Delete</a></td> -->
        </tr>
    <?php
   }
      ?>
    </table>
    </div>
  </body>
</html>