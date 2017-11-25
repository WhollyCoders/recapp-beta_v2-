<?php
$data = 'Akbar, Katherine';
function competitor($data)
{
  $name = explode(",", $data);
  // print_r($name);
  return $data = $name[1].' '.$name[0];
}

echo $competitor = competitor($data);
?>