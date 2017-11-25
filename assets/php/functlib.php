<?php
// *** Get Competitor Name ***
function competitor($data)
{
  $name = explode(",", $data);
  // print_r($name);
  return $data = trim($name[1]).' '.trim($name[0]);
}
// *** PreWrapper ***
function prewrap($data)
{
  echo('<pre>');
  print_r($data);
  echo('</pre>');
}
// *** Page ReDirect ***
function redirect($data)
{
  header('Location: '.$data);
}
?>