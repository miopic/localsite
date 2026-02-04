<h2>nukes</h2>


<?php

error_reporting(E_ALL);
echo "hello<BR>";
echo getcwd().'<BR>';

/* /opt/bitnami/apache/htdocs */
define('MODELS', '/home/bitnami/source/app/models/links.php');
echo MODELS.'<br>';

if (file_exists(MODELS))
{
  echo "it exists<br>";
} else {
  echo "it does NOT exists<br>";
}



?>
