<?php
require('./config.php');
include('../../assets/includes/header-inc.php');
$WeighIn = new WeighIn($connection);
// prewrap($competitor);
$Teams = $WeighIn->Team->get_teams();
// prewrap($Teams);
?>
        <div class="container">
          <aside class="col-md-3">
            <h2>Add Competitor</h2>
            <form class="form-add-competitor" action="./create.php" method="post">
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
                <label for="team_id">Team Name</label>
                <select class="form-control" name="team_id" id="team_id">
                  <option value="" disabled selected>*** SELECT TEAM ***</option>
                  <?php
                       foreach ($Teams as $team) { ?>

                             <option value="<?php echo($team['id']);?>"><?php echo($team['name']);?> (<?php echo($team['id']);?>) </option>

                     <?php  }
                   ?>
                </select>
              </div>
              <input class="btn btn-success btn-lg" type="submit" name="add_competitor" id="add_competitor" value="Submit">
            </form>
          </aside>
        </div>
<!-- <?php include('./includes/footer.inc.php'); ?> -->