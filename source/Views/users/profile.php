<table class="table table-striped table-hover">
  <thead>
    <tr>
      <th scope="col">field</th>
      <th scope="col">value</th>

    </tr>
  </thead>
  <tbody>
<?php


  foreach($user as $key=>$value)
  {
    echo "<tr><td>".$key."</td><td>".$value."</td></tr>";
  }
?>
  </tbody>
</table>
