<?php
  $chartType = isset($chartType) ? $chartType : 'Line';
  $chartBackground = isset($chartBackground) ? $chartBackground : 'white';
  $chartDiv  = isset($chartDiv) ? $chartDiv : 'chart_div';
  $title     = isset($title) ? $title : '';
  $divHeight = isset($divHeight) ? $divHeight : '96%';
  $divWidth = isset($divWidth) ? $divWidth : '100%';
  $height    = isset($height) ? $height : '90%';
  $width     = isset($width) ? $width : '100%';
  $chartAreaHeight = isset($chartAreaHeight) ? $chartAreaHeight : '75%';
  $chartAreaWidth  = isset($chartAreaWidth) ? $chartAreaWidth : '92%';
  $chartAreaTop = isset($chartAreaTop) ? $chartAreaTop : '30';
  $chartAreaLeft = isset($chartAreaLeft) ? $chartAreaLeft : '80';
  $legend    = isset($legend) ? $legend : 'none';
  $columns   = isset($columns) ? $columns : array('Topping'=>'string', 'Slices'=>'number');
  $verticalHLabels =  isset($verticalHLabels) && $verticalHLabels ? 'slantedText:true, slantedTextAngle:90,' : '';
  $dataJson = isset($data) ? json_encode(array_values($data)) : '';
  $fontSize = isset($fontSize) ? $fontSize : 18;
  $vAxisFontSize =  isset($vAxisFontSize) ? $vAxisFontSize : 6;
  $hAxisFontSize =  isset($hAxisFontSize) ? $hAxisFontSize : 6;
  $vAxisTitle = isset($vAxisTitle) ? $vAxisTitle : 'metric';
  $dataJson = json_encode($data);
  $colors = isset($colors) ? $colors : "['red', 'blue', 'orange', 'green', 'purple']";
  $chartFunction = isset($chartFunction) ? $chartFunction : $chartDiv;
?>
<script type="text/javascript">
  // Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(<?=$chartFunction?>);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function <?=$chartFunction?>() {


 var data = google.visualization.arrayToDataTable(<?=$dataJson?>);


        // Set chart options
        var options = {
          isStacked: true,
          'title':'<?=$title?>',
           'colors': <?=$colors?>,
           'curveType': 'function',
          'backgroundColor': '<?=$chartBackground?>',
          'legend': '<?=$legend?>',

          'width':'<?=$width?>',
          'height':'<?=$height?>',
          'chartArea':{'top':'<?=$chartAreaTop?>', 'left':'<?=$chartAreaLeft?>',
          'width':'<?=$chartAreaWidth?>',
          'height':'<?=$chartAreaHeight?>'},  //In percentage
          'hAxis':
          {
             <?=$verticalHLabels?>
            fontSize: "<?=$hAxisFontSize?>"
          },
          'vAxis':
          {
            'title': "<?=$vAxisTitle?>",
            'fontSize': "<?=$vAxisFontSize?>"
          },
            };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.<?=$chartType?>Chart(document.getElementById('<?=$chartDiv?>'));
        chart.draw(data, options);
      }
    </script>
<div id="<?=$chartDiv?>" style="margin-bottom: 10px;  height:<?=$divHeight?>; width:<?=$divWidth?>"></div>
