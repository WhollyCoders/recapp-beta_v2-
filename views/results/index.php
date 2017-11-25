<?php 

  if(isset($_GET['week']))
  {
    $week = 'Week '.$_GET['week'];
  }else{
    $week = 'Week 1';
  }

  require('./config.php');
    $WeighIn            = new WeighIn($connection);
    // *** Table Reset ***
    $WeighIn->reset_tables();
    $Teams              = $WeighIn->get_teams();
    $Result             = new Result($connection);
    $TeamResult         = new TeamResult($connection);
    $CompetitionResult  = new CompetitionResult($connection);
    $CompetitionResult->post_results($week);
        // echo('<pre>');
        // print_r($CompetitionResult);
        // echo('</pre>');
    $week_ending_date         = $WeighIn->get_week_ending_date($week);
    $weekly_weight_loss_comp  = $WeighIn->get_weekly_weight_loss_competition($week);
    $weekly_weight_loss_over  = $WeighIn->get_weekly_weight_loss_overall($week);

    $Result->post_results($week);
    $Leaders_wiwl             = $WeighIn->get_weekly_weight_loss_leaders($week); // *** Weekly Individual Weight Loss ***
    $Leaders_oiwl             = $WeighIn->get_weekly_overall_weight_loss_leaders($week); // *** Overall Individual Weight Loss ***
    
    $TeamResult->post_results($week);
    $Leaders_wtwl = $WeighIn->get_weekly_weight_loss_leaders_team($week); // *** Weekly Team Weight Loss ***
    $Leaders_otwl = $WeighIn->get_weekly_weight_loss_leaders_team_overall($week); // *** Overall Team Weight Loss ***
    
    $Leaders_wobl = $WeighIn->overall_biggest_loser_week($week); // *** Overall Biggest Loser ***
    $Leaders_mrpl = $WeighIn->most_raw_pounds($week); // *** Most Raw Pounds Lost ***
    $Leaders_top10 = $WeighIn->top_ten_overall($week); // *** Top 10 Overall ***
    

    // echo('<pre>');
    // print_r($TeamResult);
    // echo('</pre>');

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Weekly Recap</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
      body{background-color: #eee;}
      .container{width: 1020px; background-color: #fff; min-height: 660px; margin: 30px auto; padding: 15px 20px;}
      header{background-color: #000; color: #fff; height: 45px; line-height: 45px; padding: 0px 20px;}
      header h1 span{color: #f00; font-size: 125%;}
      .content-main{width: 70%; float: left; min-height: 660px; padding: 15px 20px; border-left: 1px solid #000;}
      .content-main .section-team, .section-weekly-individual-weight-loss h4, .section-weekly-overall-weight-loss h4{text-align: left;}
      .section-weekly-individual-weight-loss ol, .section-weekly-overall-weight-loss ol{text-align: left;}
      .section-team{border-top: 1px solid #ccc; border-bottom: 1px solid #ccc;}
      .content-main h2 span, .content-main h3 span{color: #08d;}
      .content-main h2, .content-main h3, .content-main div{text-align: center;}
      .content-main div img{width: 340px; height: auto; text-align: center;}
      .sidebar-right{width: 20%; float: right; background-color: #ccc; min-height: 660px; padding: 15px 20px;}
      footer{clear: both; background-color: #000; color: #fff; height: 45px; line-height: 45px; padding: 0px 20px;}
    </style>
  </head>
  <body>
    <div class="container">
      <header>
        <h1>Bod<span>4</span>God Results - <?php echo($week); ?></h1>
      </header>
      <div class="content">
        <section class="page-results">
          <div class="content-main">
            <div>
              <img src="../../assets/images/ltl_logo.jpg" alt="logo">
            </div>
            <h2>Weekly Statistics From Week Ending - <span><?php echo($week_ending_date); ?></span></h2>
            <h3>
              Our total weight loss from <?php echo($week); ?> is <span><?php echo($weekly_weight_loss_comp); ?> </span> pounds!!!<br>
              Our overall total overall weight loss <br>for the competition is <span><?php echo($weekly_weight_loss_over); ?> </span> pounds!!!
            </h3> 
            <div class="section-team">
              <h2>Team Names</h2>
              <ol>
                <?php foreach ($Teams as $team) { ?>
                  <li><?php echo($team['name']); ?></li>
                <?php } ?>
              </ol>
            </div>
            <p>
              <div class="section-weekly-individual-weight-loss">
                <h4>Weekly Individual Weight Loss:</h4>
                <ol>
                  <?php foreach ($Leaders_wiwl as $wiwl) { ?>
                    <li><?php echo($wiwl['competitor']); ?> - <?php echo($wiwl['team']); ?> (<?php echo($wiwl['weight_loss']); ?> LBS ) [<?php echo($wiwl['weight_loss_percent']); ?> %]</li>
                  <?php } ?>
                </ol>
              </div>
            </p>
            <p>
              <div class="section-weekly-overall-weight-loss">
              <h4>Overall Individual Weight Loss:</h4>
                  <ol>
                    <?php foreach ($Leaders_oiwl as $oiwl) { ?>
                      <li><?php echo($oiwl['competitor']); ?> - <?php echo($oiwl['team']); ?> (<?php echo($oiwl['weight_loss_overall']); ?> LBS) [<?php echo($oiwl['weight_loss_percent_overall']); ?> %]</li>
                    <?php } ?>
                  </ol>
                </div>
            </p>
            <p>
            <div class="section-weekly-individual-weight-loss">
              <h4>Weekly Team Weight Loss:</h4>
              <ol>
                <?php foreach ($Leaders_wtwl as $wtwl) { ?>
                  <li><?php echo($wtwl['team']); ?> (<?php echo($wtwl['weight_loss']); ?> LBS) [<?php echo($wtwl['weight_loss_percent']); ?> %]</li>
                <?php } ?>
              </ol>
            </div>
          </p>
          <p>
          <div class="section-weekly-overall-weight-loss">
          <h4>Overall Team Weight Loss:</h4>
              <ol>
                <?php foreach ($Leaders_otwl as $otwl) { ?>
                  <li><?php echo($otwl['team']); ?> (<?php echo($otwl['weight_loss_overall']); ?> LBS) [<?php echo($otwl['weight_loss_percent_overall']); ?> %]</li>
                <?php } ?>
              </ol>
            </div>
          </p>
            <h2>Overall Biggest Loser: <?php echo($Leaders_wobl['competitor']); ?> (<?php echo($Leaders_wobl['weight_loss_overall']); ?>lbs) <?php echo($Leaders_wobl['weight_loss_percent_overall']); ?>%</h2>
            <p>
            <div class="section-weekly-overall-weight-loss">
            <h4>Most Raw Pounds Loss:</h4>
                <ol>
                  <?php foreach ($Leaders_mrpl as $mrpl) { ?>
                    <li><?php echo($mrpl['competitor']); ?> - <?php echo($mrpl['team']); ?> (<?php echo($mrpl['weight_loss_overall']); ?> LBS)</li>
                  <?php } ?>
                </ol>
              </div>
          </p>
          </div>
          <p>
              <!-- <div class="section-weekly-overall-weight-loss">
              <h4>Top-Ten Overall Individual Weight Loss:</h4>
                  <ol>
                    <?php foreach ($Leaders_top10 as $top10) { ?>
                      <li><?php echo($top10['competitor']); ?> - <?php echo($top10['team']); ?> (<?php echo($top10['weight_loss_overall']); ?> LBS) [<?php echo($top10['weight_loss_percent_overall']); ?> %]</li>
                    <?php } ?>
                  </ol>
                </div> -->
            </p>
          <aside class="sidebar-right">
          <h3>Weekly Results</h3>
            <ul>
              <li><a href="./index.php?week=1">Week 1</a></li>
              <li><a href="./index.php?week=2">Week 2</a></li>
              <li><a href="./index.php?week=3">Week 3</a></li>
              <li><a href="./index.php?week=4">Week 4</a></li>
              <li><a href="./index.php?week=5">Week 5</a></li>
              <li><a href="./index.php?week=6">Week 6</a></li>
              <li><a href="./index.php?week=7">Week 7</a></li>
              <li><a href="./index.php?week=8">Week 8</a></li>
              <li><a href="./index.php?week=9">Week 9</a></li>
              <li><a href="./index.php?week=10">Week 10</a></li>
            </ul>
            <div class="section-weekly-overall-weight-loss">
              <h4>Top-Ten Overall Individual Weight Loss:</h4>
                  <ol>
                    <?php foreach ($Leaders_top10 as $top10) { ?>
                      <li><?php echo($top10['competitor']); ?> - <?php echo($top10['team']); ?> (<?php echo($top10['weight_loss_overall']); ?> LBS) [<?php echo($top10['weight_loss_percent_overall']); ?> %]</li>
                    <?php } ?>
                  </ol>
                </div>
          </aside>
        </section>
      </div>
      <footer>
        <p>&copy; 2017 RecApp</p>
      </footer>
    </div>
  </body>
</html>