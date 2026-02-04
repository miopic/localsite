<?php
$tabLabel = isset($tabLabel) ? $tabLabel : $_SERVER['HTTP_HOST'];
$navbarBrandValue = 'DAVID MARKS';
$navbarBrandValue = isset($navbarBrandValue) ? $navbarBrandValue : str_replace('.com', '', $_SERVER['HTTP_HOST']).'&nbsp;';
$this->set('navbarBrandValue', $navbarBrandValue);
$bootVersion = isset($bootVersion)? $bootVersion : '4.2.1';
$chartsEnabled = isset($chartsEnabled) ? $chartsEnabled : false;
$mapsEnabled = isset($mapsEnabled) ? $chartsEnabled : false;
$measurementUnits = isset($measurementUnits) ? $measurementUnits : '';

if ($chartsEnabled)
{
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<?php
}
?>
<head>
  <script src="../assets/js/color-modes.js"></script>
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
    <!-- google map api -->
     <script
       src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAS5815JrGPMSsO-cB-3nOuZaNcpeqW07U&map_ids=248130336f999827&libraries=geometry&v=weekly">
     </script>
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
</head>
  <body>

      <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 " style="padding-bottom:8px; margin-bottom:12px">
        <div class="container d-flex flex-wrap"">
          <ul class="nav me-auto">
            <li class="nav-item"><a href="<?=APP_NAME?>/activities/dashboard" class="nav-link link-dark px-2 active" aria-current="page">Home</a></li>
            <li class="nav-item"><a href="<?=APP_NAME?>/activities/search" class="nav-link link-dark px-2">Search</a></li>
            <li class="nav-item"><a href="<?=APP_NAME?>/activities/logbook" class="nav-link link-dark px-2">Logbook</a></li>
            <li class="nav-item"><a href="<?=APP_NAME?>/activities/month" class="nav-link link-dark px-2">Month</a></li>
            <li class="nav-item"><a href="<?=APP_NAME?>/activities/view" class="nav-link link-dark px-2">View</a></li>
          </ul>
          <ul class="nav">
            <?php
              if ($this->auth->loggedIn())
              {
                $loginLogoutLink = APP_NAME.'/users/logout';
                $loginLogoutText = 'Logout';
              } else
              {
                $loginLogoutLink = APP_NAME.'/users/login';
                $loginLogoutText = 'Login';  // code...
              }
            ?>
            <li class="nav-item"><a href="<?=$loginLogoutLink?>" class="nav-link px-2" style="color:darkgrey; text-decoration:none"><?=$loginLogoutText?></a></li>
          </ul>
      </div>

      </header>

      <header class="header" style="background-color: #f5f5f5;margin-top:0px; margin-bottom:20px; padding-top:5px;padding-bottom:7px; color:darkgrey;font-size:15px">
        <div class="container-fluid" style="padding-left:0px;padding-right:opx;">
          <div class="row">
            <div class="col-sm-6" style="padding-left:20px">
              <?= isset($statusMessage) ? $statusMessage : DEFAULT_STATUSMESSAGE?>
            </div>
            <div class="col-sm-3 ">
            &nbsp;
            </div>
            <div class="col-sm-3 ">
                <span id="day" class="float-right" style="float:right;display:block"><a href="/rule10/users/changeunits/" style="color:darkgrey; text-decoration:none"><?=$measurementUnits?></a></span>
            </div>
          </div>
        </div>
      </header>

    <div class="container">
      <?=$content?>
    </div>
    <!-- Optional JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

  </body>
</html>
