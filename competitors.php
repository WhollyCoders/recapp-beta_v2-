<?php
// $page_title = 'Competitors';
// require('../myb4g-connect.php');
// require('./php/library.php');
// require('./models/competitor/Competitor.php');
// require('./models/team/Team.php');
// include('./includes/header.inc.php');
$competitor = new Competitor($connection);
// prewrap($competitor);
$competitors = $competitor->get_competitors();
// prewrap($competitor);

$team = new Team($connection);
$teams = $team->get_teams();
?>
    <div class="container">
      <div class="row">
        <div class="col-md-9">
          <h1>Competitors</h1>
          <table class="table table-striped table-bordered table-condensed table-hover">
            <tr>
              <th>ID</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Email Address</th>
              <th>Phone Number</th>
              <th>Team ID</th>
              <th>Date Added</th>
            </tr>
            <?php
            foreach($competitors as $competitor){
              ?>
              <tr>
                <td><?php echo($competitor['id']);?></td>
                <td><?php echo($competitor['first_name']);?></td>
                <td><?php echo($competitor['last_name']);?></td>
                <td><?php echo($competitor['email']);?></td>
                <td><?php echo($competitor['phone']);?></td>
                <td><?php echo($competitor['team_id']);?></td>
                <td><?php echo($competitor['date_entered']);?></td>
                <td><a class="btn btn-primary" href="./editcompetitor.php?id=<?php echo($competitor['id']);?>">Update</a></td>
                <td><a class="btn btn-danger" href="./delete.php?id=<?php echo($competitor['id']);?>">Delete</a></td>
              </tr>
          <?php
         }
            ?>
          </table>
        </div>
          <aside class="col-md-3">
            <h2>Add Competitor</h2>
            <form class="form-add-competitor" action="./php/addCompetitor.php" method="post">
              <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Email">
              </div>
              <div class="form-group">
                <label for="first">First Name</label>
                <input type="text" class="form-control" name="first" id="first" placeholder="First Name">
              </div>
              <div class="form-group">
                <label for="last">Last Name</label>
                <input type="text" class="form-control" name="last" id="last" placeholder="Last Name">
              </div>
              <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" class="form-control" name="phone" id="phone" placeholder="Phone">
              </div>
              <div class="form-group">
                <label for="team_id">Team ID</label>
                <select class="form-control" name="team_id" id="team_id">
                  <option value="" disabled selected>*** SELECT TEAM ***</option>
                  <?php
                       foreach ($teams as $team) { ?>

                             <option value="<?php echo($team['team_id']);?>"><?php echo($team['team_name']);?></option>

                     <?php  }
                   ?>
                </select>
              </div>
              <input class="btn btn-success btn-lg" type="submit" name="add_competitor" id="add_competitor" value="Submit">
            </form>
          </aside>
        </div>
    </div>
<?php include('./includes/footer.inc.php'); ?>
