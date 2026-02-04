<?php
include('elements/header.php');
$bday = "January 1, 1960 11:30AM";
$today = strtotime(date("Y-m-d H:i:s"));
$alive = $today - strtotime($bday);
?>
    <main role="main" class="container">
      <div class="row ps-5">
        <div class="col">
          <h1 class="mt-5 text-xxxl">dwm</h1><br>
          <h4>Best Human Inventions</h4>
          <ol>
            <li>Ice Cream and Cheese</li>
            <li>The Zipper</li>
            <li>Velcro</li>
            <li>Post It Notes</li>
            <li>The Bicycle</li>
          </ol>
          <h4>Worst Human Inventions</h4>
          <ol>
            <li>Weapons</li>
            <li>The Mobile SmartPhone</li>
            <li>Social Media</li>
            <li>Television</li>
          </ol>
        </div>
        <div class="col-8">
          &nbsp;
        </div>
    </div>
    </main>




<?php
include('elements/footer.php');
?>


</body>
</html>
