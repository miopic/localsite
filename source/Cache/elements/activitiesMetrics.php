<style>
      body
      {
        font-size:13px;
      }
      .text-title1
      {
        font-size: 28px;
        line-height: 34px;
      }
      .time
      {
        line-height:3;
      }
      .inline-stats {
        clear: none;
        white-space: nowrap;
      }
      .inline-stats li
      {
        margin-left:10px;
        margin-right:10px;
        list-style-type: none;
        display: inline-block;
       }
     .metric
      {
        font-size: 28px;
        display: block;
        font-weight: 300;
      }
      .unit
      {
        font-size: 0.65em;
      }
      .metric-label
      {
        font-size:12px;
        color: #99999e;
      }
  </style>
<ul class="inline-stats ">
  <li>
    <div class="metric text-left" style="">
      <?=$iconInTitle?>
    </div>
    <div class="metric-label text-center">
      &nbsp;
    </div>
  </li>
  <li>
    <div class="metric text-left" style="">
      <?=$duration?>
    </div>
    <div class="metric-label text-center">
      moving
    </div>
  </li>
  <li class="text-center">
    <div class="metric text-center" style="">
      <?=$distance?>
    </div>
    <div class="metric-label text-center">
      distance
    </div>
  </li>
  <li>
    <div class="metric text-center" style="">
      <?=$speed?>
    </div>
    <div class="metric-label text-center">
      speed
    </div>
  </li>
  <li>
    <div class="metric text-center" style="margin-bottom:0px">
      <?=$total_elevation_gain?>
    </div>
    <div class="metric-label text-center">
      ascent
    </div>
  </li>
  <li>
    <div class="metric text-center" style="margin-bottom:0px">
      <?=$calories?>
    </div>
    <div class="metric-label text-center">
      calories
    </div>
  </li>
  <li>
    <div class="metric text-center" style="margin-bottom:0px">
      <?=$record['kudos_count']?>
    </div>
    <div class="metric-label text-center">
      kudos
    </div>
  </li>
</ul>
