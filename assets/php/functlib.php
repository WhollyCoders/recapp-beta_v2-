<?php
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