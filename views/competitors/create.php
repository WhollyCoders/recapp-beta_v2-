<?php
  require('./config.php');
  include('../../assets/includes/header-inc.php');
  $WeighIn = new WeighIn($connection);
  if(isset($_POST['add_competitor'])){
    $id = $_POST['team_id'];
    $team = $WeighIn->Team->get_team_by_id($id)[0]['name'];
    $params = array(
      'email'       =>    $_POST['email'],
      'firstname'   =>    ucfirst($_POST['first']),
      'lastname'    =>    ucfirst($_POST['last']),
      'phone'       =>    $_POST['phone'],
      'team_id'     =>    $_POST['team_id'],
      'team'        =>    $team
    );
  prewrap($params);

  $WeighIn->Competitor->add_competitor($params);

  // prewrap($WeighIn);
}
?>