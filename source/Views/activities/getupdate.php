<div class="row">
  <table class="table table-striped table-bordered table-hover table-responsive">
    <thead class="table-dark ">
      <tr>
        <th scope="col">field</th>
        <th scope="col">type</th>
        <th scope="col">old</th>
        <th scope="col">changes</th>
      </tr>
    </thead>
  </thead>
  <tbody>
<?php
    foreach ($record as $key => $value)
    {
      if ($key == 'hrzones')
      {
        $value = json_encode($value);
      }
?>							<tr>
        <td style="word-wrap: break-word;min-width: 75px;max-width: 75px;"><?=$key?></td>
        <td style="word-wrap: break-word;min-width: 75px;max-width: 75px;"><?=gettype($key)?></td>
        <td style="word-wrap: break-word;min-width: 200px;max-width: 200px;"><?=$value?></td>
        <td style="word-wrap: break-word;min-width: 200px;max-width: 200px;">&nbsp;</td>
      </tr>
<?php
    }
?>
  </tbody>
</table>
</div>
