<?php
  $chartType = isset($chartType) ? $chartType : 'Line';
  $chartBackground = isset($chartBackground) ? $chartBackground : 'white';
  $chartDiv  = isset($chartDiv) ? $chartDiv : 'chart_div';
  $title     = isset($title) ? $title : '';
  $width     = isset($width) ? $width : '1100px';
  $height    = isset($height) ? $height : '300px';
  $chartAreaHeight = isset($chartAreaHeight) ? $chartAreaHeight : '80';
  $chartAreaWidth  = isset($chartAreaWidth) ? $chartAreaWidth : '75';
  $chartAreaTop = isset($chartAreaTop) ? $chartAreaTop : '20';
  $chartAreaLeft = isset($chartAreaLeft) ? $chartAreaLeft : '20';
  $legend    = isset($legend) ? $legend : 'none';
  $columns   = isset($columns) ? $columns : array('Topping'=>'string', 'Slices'=>'number');
  $verticalHLabels =  isset($verticalHLabels) && $verticalHLabels ? 'slantedText:true, slantedTextAngle:90,' : '';
  $dataJson = isset($data) ? json_encode($data) : '';
  $fontSize = isset($fontSize) ? $fontSize : 18;
  $vAxisFontSize =  isset($vAxisFontSize) ? $vAxisFontSize : 10;
  $hAxisFontSize =  isset($hAxisFontSize) ? $hAxisFontSize : 10;
  $vAxisTitle = isset($vAxisTitle) ? $vAxisTitle : 'metric';
  $dataJson = json_encode($graphData);

?>
<script type="text/javascript">
  // Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {


 var data = google.visualization.arrayToDataTable(<?=$dataJson?>);


        // Set chart options
        var options = {
          'title':'<?=$title?>',
           'curveType': 'function',
          'backgroundColor': '<?=$chartBackground?>',
          'legend': '<?=$legend?>',
          'width':<?=$width?>,
          'height':<?=$height?>,
          'chartArea':{'top':<?=$chartAreaTop?>, 'left':<?=$chartAreaLeft?>, 'width':"<?=$chartAreaWidth?>", 'height':"<?=$chartAreaHeight?>%"},
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
<div id="<?=$chartDiv?>" style="margin-bottom: 10px;  height:175"></div>
