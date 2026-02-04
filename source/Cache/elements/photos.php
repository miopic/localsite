<div class="row">
  <div class="col-md-2">
    &nbsp;
  </div>
  <div class="col-md-8">
    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel" style="">
<?php
  // show indicators if there is more than 1 photo
      if (isset($photos) && (count($photos) > 1))
      {
?>
        <div class="carousel-indicators">
<?php
          for($i = 0; $i < count($photos); $i++)
          {
?>
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="<?=$i?>" <?=$i == 0 ? 'class="active"' : ''?> <?=$i == 0 ? 'aria-current="true"' : ''?> aria-label="Slide <?=$i?>"></button>
<?php
          }
?>
        </div>
        <div class="carousel-inner" style="width:80%; height:80%">
<?php
  foreach ($photos as $i=>$photo)
  {
    $img = $photo['url']['128'];
    $img = str_replace('128x128', '1024x1024', $img);

?>
    <div class="carousel-item <?=$i == 0 ? 'active' :''?>">

        <img src="<?=$img?>" class="rounded mx-auto d-block w-100" style="border-top:20px; border-left:20px">
  <?php
        if  (isset($photo['caption']) && ($photo['caption'] != ''))
        {
  ?>
        <div class="carousel-caption">
          <h5><?=$photo['caption'] ?></h5>
        </div>
<?php
        }
?>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>

<?php
  }
?>
    </div>

  </div>

</div>
  <div class="col-md-2">
    &nbsp;
  </div>
</div>


<!--
<?php
}

?>
-->
