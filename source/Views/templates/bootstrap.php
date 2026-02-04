<!DOCTYPE html>
<html>
<?php
$tabLabel = isset($tabLabel) ? $tabLabel : $_SERVER['HTTP_HOST'];
$navbarBrandValue = 'DAVID MARKS';
$navbarBrandValue = isset($navbarBrandValue) ? $navbarBrandValue : str_replace('.com', '', $_SERVER['HTTP_HOST']).'&nbsp;';
$this->set('navbarBrandValue', $navbarBrandValue);
$bootVersion = isset($bootVersion)? $bootVersion : '4.2.1';
$chartsEnabled = isset($chartsEnabled) ? $chartsEnabled : false;
$mapsEnabled = isset($mapsEnabled) ? $mapsEnabled : true;
$measurementUnits = isset($measurementUnits) ? $measurementUnits : '';

if ($chartsEnabled)
{
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<?php
}
?>

<head>
  <!-- Google tag (gtag.js) -->
<?php
?>
  <!--<script src="../assets/js/color-modes.js"></script>-->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Hugo 0.118.2">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<?php
  if ($mapsEnabled)
  {
?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
     integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
     crossorigin=""/>

    <!-- google map api
     <script
       src="https://maps.googleapis.com/maps/api/js?v=3.54&key=AIzaSyAS5815JrGPMSsO-cB-3nOuZaNcpeqW07U&map_ids=248130336f999827&libraries=geometry&v=weekly&loading=async">
     </script> -->
<?php
  }
?>
  <title><?=$tabLabel?></title>
  <style>
  .btn-secondary {
      --bs-btn-color: #fff;
      --bs-btn-bg: lightgrey;
      --bs-btn-border-color: #6c757d;
      --bs-btn-hover-color: red;
      --bs-btn-hover-bg: lightyellow;
      --bs-btn-hover-border-color: red;
      --bs-btn-focus-shadow-rgb: 130,138,145;
  }
  </style>

  <link href='/css/customBoot.css' rel='stylesheet' type='text/css'>

  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
       integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
       crossorigin="">
  </script>

</head>
  <body onload="startTime()" style="min-height:1000px">
      <?php
      include('bootstrap-status.php');
      include('bootstrap-header.php');
      ?>
    <div class="container" style="margin-bottom:15px; backgroundColor:grey">
      <?=$content?>
    </div>
    <?php
   // include('bootstrap-footer.php');
    ?>
    <!-- Optional JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

  </body>
</html>
