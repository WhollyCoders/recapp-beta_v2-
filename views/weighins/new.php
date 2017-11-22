<?php 
require('./config.php');
$WeighIn      = new WeighIn($connection);
$Competitors  = $WeighIn->get_competitors();
$Competitions = $WeighIn->get_competitions();
$Teams        = $WeighIn->get_teams();
$Weeks        = $WeighIn->get_weeks();
    // echo('<pre>');
    // print_r($WeighIn);
    // echo('</pre><br>');

    // echo('<pre>');
    // print_r($Competitions);
    // echo('</pre><br>');

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>New Weigh-In</title>
  <style>
    body{background-color: #333; font-size: 20px; font-family: 'century gothic', helvetica, arial, sans-serif;}
    .container{background-color: #fff; margin: 30px auto; width: 480px; border-radius: 10px;}
    .wrapper-weigh-in-form{padding: 30px 40px;}
    select, input[type="text"]{padding: 0px 10px; height: 45px; line-height: 45px; width: 94%; font-size: 20px;}
    select{width: 100%;}
    input[type="submit"]{border: none; border-radius: 35px; height: 75px; width: 100%; background-color: #39f; 
    color: #fff; padding: 25px; font-size: 25px;}
    input[type="text"]::placeholder{font-style: italic; font-family: times;}
    input[type="submit"]:hover{background-color: #047ed2; color: #fff; border: 1px solid #39f;}
    .link-upload {text-align: center;}
  </style>
</head>
<body>
  <div class="container">
    <div class="wrapper-weigh-in-form">
      <h1>New Weigh-In</h1>
      <form action="./create.php" method="post">
        <p>
          <label><strong>Competitor</strong></label><br>
          <select name="add_competitor">
              <option value="null"><strong>Select Competitor</strong></option>
            <?php foreach ($Competitors as $competitor) { ?>
              <option value="<?php echo($competitor['id']);?>"><?php echo($competitor['firstname'].' '.$competitor['lastname']);?> (<?php echo($competitor['id']);?>) </option>
            <?php }?>
          </select>
        </p>
        <p>
          <label><strong>Competition</strong></label><br>
          <select name="add_competition">
              <option value="1"><strong>Losing2Live</strong></option>
            <!-- <?php foreach ($Competitions as $competition) { ?>
              <option value="<?php echo($competition['id']);?>"><?php echo($competition['name']);?></option>
            <?php }?> -->
          </select>
        </p>
        <p>
          <label><strong>Team</strong></label><br>
          <select name="add_team">
              <option value="null"><strong>Select Team</strong></option>
            <?php foreach ($Teams as $team) { ?>
              <option value="<?php echo($team['id']);?>"><?php echo($team['name']);?> (<?php echo($team['id']);?>) </option>
            <?php }?>
          </select>
        </p>
        <p>
          <label><strong>Week</strong></label><br>
          <select name="add_week">
              <option value="null"><strong>Select Week</strong></option>
            <?php foreach ($Weeks as $week) { ?>
              <option value="<?php echo($week['id']);?>"><?php echo($week['name']);?> (<?php echo($week['description']);?>)</option>
            <?php }?>
          </select>
        </p>
        <p>
          <label><strong>Weight</strong></label><br>
          <input type="text" name="add_weight" placeholder="Enter Weight">
        </p>
        <p>
          <input type="submit" name="add_weigh_in" value="Submit Weight">
        </p>
        <p class="link-upload">
          <a href="./upload.php">Upload Weigh-Ins</a>
        </p>
      </form>
    </div>
  </div>

</body>
</html>