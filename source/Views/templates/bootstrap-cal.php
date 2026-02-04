<?php
$tabLabel = isset($tabLabel) ? $tabLabel : $_SERVER['HTTP_HOST'];
$navbarBrandValue = isset($navbarBrandValue) ? $navbarBrandValue : str_replace('.com', '', $_SERVER['HTTP_HOST']).'&nbsp;';

$this->set('navbarBrandValue', $navbarBrandValue);
if (isset($this->app))
{
  
}
$bootVersion = isset($bootVersion)? $bootVersion : '4.2.1';
$enableCharts = isset($enableCharts) ? $enableCharts : false;
$enableCharts = true;
if ($enableCharts)
{
  echo '<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>';
}
?>
<!doctype html>
<html lang="en">
  <head>
 
    <script>
      function startTime() {
        var today = new Date();
        var days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"]
        var y = today.getFullYear();
        var mo = today.getMonth();
        var month = new Array();
        month[0] = "January";
        month[1] = "February";
        month[2] = "March";
        month[3] = "April";
        month[4] = "May";
        month[5] = "June";
        month[6] = "July";
        month[7] = "August";
        month[8] = "September";
        month[9] = "October";
        month[10] = "November";
        month[11] = "December";

        var dt = today.getDate();
        var d = today.getDay();
        var h = today.getHours();
        var m = today.getMinutes();
        var s = today.getSeconds();
        var y = today.getFullYear();
        m = checkTime(m);
        s = checkTime(s);
        document.getElementById('clock').innerHTML =
        h + ":" + m + ":" + s;
        whichDay = days[d];       
        dt = checkTime(dt);
        
        document.getElementById('day').innerHTML = whichDay + ", "+ month[mo]  + " " + dt+ ", " + y;

        var t = setTimeout(startTime, 500);
      }
      function checkTime(i) {
        if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
        return i;
      }
  </script>

    <!-- Required meta tags -->
    <meta name="google-site-verification" content="LsBvvtLpj95vXoTkcRPbNSVj-fsxBwbKd63uG-7IyvQ" />
    <meta charset="utf-8">
    <!-- Responsive viewport tag -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/<?=$bootVersion?>/css/bootstrap.min.css">

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <!--<script src="/js/bootstrap/<?=$bootVersion?>/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>-->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/<?=$bootVersion?>/js/bootstrap.min.js"></script>
    
    <title><?=$tabLabel?></title>
    <link rel="stylesheet" href="/css/customBoot.css"  type = "text/css">
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
        margin-right:20px;
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
  </head>
  <body onload="startTime()">
    <header style="margin-bottom: 1px;">
      <!-- Fixed navbar -->
      <?
      ?>
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand"  href="/"><?=$navbarBrandValue?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse " id="navbarSupportedContent">
  <?
          if (($this->auth->loggedIn()))
          {
  ?>
          <?=$this->element('menus');?>
          


           <div class="col-sm-12 float-right" style="color: rgba(255,255,255,.5);">
<?php
          if (($this->auth->loggedIn()))
          {
?>
            <a class="nav-link float-right"   href="/users/profile/<?=$this->auth->user('username')?>"><?=$_SESSION['user']['username']?></a>
<?php
          } else
          {
?>
             <a class="nav-link "  style="color: rgba(255,255,255,.5);" href="/users/login/">login</a>
<?php
          }
?>
          </div>
        </div>
      </nav>
<?
      }
?>
    </header>
    <header class="header" style="background-color: #f5f5f5;margin-top:0px; margin-bottom:20px; padding-top:5px;padding-bottom:7px; color:darkgrey;font-size:15px">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6">
        <?= isset($statusMessage) ? $statusMessage : DEFAULT_STATUSMESSAGE?>
      </div>
      <div class="col-sm-2 ">
        
      </div>
      <div class="col-sm-3 ">
          <span id="day" class="float-right" style="float:right;display:block"></span>

      </div>
         <div  class="col-sm-1 ">
        <span id="clock" class="" style="display:block"></span>
      </div>
    </div>
  </div>
  <style>
  body
{
  margin:0;
}

#banner
{
  font-family:'Segoe UI','Helvetica Neue',Helvetica,Arial,sans-serif;
  background-color:#207BBC;
  line-height:60px;
  overflow:auto;
}

#banner,
#banner a,
#banner a:hover,
#banner a:active 
{
  color:white;
  text-decoration:none;
}

#banner .logo
{
  font-size:18px;
  float:left;
  font-weight:100;
}

#banner .logo span.fa
{
  font-size:26px;
  margin-right:10px;
  vertical-align: text-bottom;
}

#banner .menu
{
  float:right;
}

#banner .menu-button
{
  display:none;
  float:right;
}

#banner ul.menu {
  padding:0;
  margin:0;
}

#banner ul.menu  li
{
  display:inline-block;
  list-style:none;
  padding:0 15px;
  cursor:pointer;
  font-size:14px;
  position:relative;
}

#banner ul.menu  li:hover
{
  background-color:#2180C4;
}

#banner ul.menu  li.selected {
  box-shadow: inset 0 -3px 0 white;
}

#banner ul.menu  li span {
  margin-top: 21px;
  margin-bottom: 21px;
  font-size: 17px;
}

#title
{
  background-color:#303030;
  height:200px;
  color:#ccc;
  text-align:center;
}

#main > .site-content > span.fa-spinner
{
  font-size:60px;
  margin: 40px auto;
  display:block;
  width: 60px;
  height: 60px;
}

#main .site-content img {
  width:100%;
}

#footer
{
  background-color:#E5E5E5;
  clear:both;
}

.site-content
{
  width:800px;
  margin:auto;
}

#main > .site-content.full
{
  padding-top:0;
  width:100%;
  display:table-row;
}

.left-menu {
  display:table-cell;
  background-color: #eee;
  border-left: 1px solid #ccc;
  border-right: 1px solid #ccc;
  border-bottom: 1px solid #ccc;
  box-shadow: 0px 0px 5px rgba(0, 0, 0, .2);
  min-height:400px;
}

.site-content.full > .left-menu {
  border-left:0;
}

.left-menu ul {
  margin:0;
  padding:0;
  min-height: 200px;
}

.left-menu ul li {
  list-style: none;
  padding: 6px 25px 6px 15px;
  cursor: pointer;
  white-space:nowrap;
}

.left-menu ul li:hover {
  background-color:#ddd;
}

.left-menu ul li.selected {
  border-right:3px solid #aaa;
}

.sub-content-container 
{
  display:table-cell;
  padding-left:20px;
  width:100%;
}

.sub-content  > span.fa-spinner
{
  font-size:40px;
  margin: 40px auto;
  display:block;
  width: 40px;
  height: 40px;
}

.center {
  text-align:center;
}

.underline-title {
  border-bottom:1px solid #ccc;
}

.new-version {
  display:inline-block;
  position: absolute;
  font-size: 12px;
  padding: 5px;
  background-color: green;
  color: white;
  border-radius: 4px;
  box-shadow: #222 0 0 10px;
  margin-top:20px;
  margin-left:5px;
}

.parameter,
.default-value,
.returns,
.related-methods {
  font-size: 15px;
  font-family: consolas;
}

.parameter {
  color: #006696;
}

.default-value,
.returns {
  color: #3C7202;
}

.related-methods {
  color:#7C0000;
}

span.link {
  font-family: consolas;
  font-size:13px;
  color: blue;
}

.parameter > ul > li{
  font-size: 12px;
}

code {
  display:block;
  margin:5px 0;
  padding: 12px;
}

code.inline {
  display:inline;
  padding: 0;
  background-color: none;
}

h1,
h2,
h3,
h4,
h5
{
  font-family:'Segoe UI','Helvetica Neue',Helvetica,Arial,sans-serif;

}

h1,
h2
{
  font-weight:100;
}

h1
{
  padding: 30px 0 15px 0;
  margin: 0;
  font-size:60px;
  display:inline-block;
}

h4 {
  font-size:16px;
  font-weight:bold;
}

p {
  margin: 15px 0;
}

li {
  list-style-type:square;
}

hr {
  margin:20px 80px;
}

@media (min-width: 1024px) {
  .site-content
  {
    width:800px;
  }
}

@media (min-width: 768px) and (max-width: 1024px) {
  .site-content
  {
    width:650px;
  }

  #banner ul.menu  li
  {
    padding:0 7px;
  }

  #title {
    height:160px;
  }

  h1 {
    padding: 20px 0 10px 0;
    font-size:50px;
  }

  h2 {
    font-size:25px;
  }
}


@media (max-width: 768px) {
  .site-content
  {
    width:100%;
    min-width:200px;
    padding:0 5px;
  }

  #banner .menu-button
  {
    display:inline-block;
    cursor:pointer;
    font-size:21px;
    padding:18px;
  }

  #banner ul.menu  {
    display:none;
    float:none;
    clear:both;
    left:0;
  }

  #banner ul.menu  li {
    display:block;
    width:100%;
  }

  #banner ul.menu  li.selected {
    box-shadow: inset 3px 0 0 white;
  }

  #title {
    height:120px;
  }

  h1 {
    padding: 15px 0 0 0;
    font-size:30px;
  }

  h2 {
    font-size:17px;
  }

  .new-version {
    right:5px;
    margin-top: -10px;
  }
}
</style>
<!-bootstrap cal-->
<script type="text/javascript">
  function editEvent(event) {
    $('#event-modal input[name="event-index"]').val(event ? event.id : '');
    $('#event-modal input[name="event-name "]').val(event ? event.name : '');
    $('#event-modal input[name="event-location"]').val(event ? event.location : '');
    $('#event-modal input[name="event-start-date"]').datepicker('update', event ? event.startDate : '');
    $('#event-modal input[name="event-end-date"]').datepicker('update', event ? event.endDate : '');
    $('#event-modal').modal();
}

function deleteEvent(event) {
    var dataSource = $('#calendar').data('calendar').getDataSource();

    for(var i in dataSource) {
        if(dataSource[i].id == event.id) {
            dataSource.splice(i, 1);
            break;
        }
    }
    
    $('#calendar').data('calendar').setDataSource(dataSource);
}

function saveEvent() {
    var event = {
        id: $('#event-modal input[name="event-index"]').val(),
        name: $('#event-modal input[name="event-name"]').val(),
        location: $('#event-modal input[name="event-location"]').val(),
        startDate: $('#event-modal input[name="event-start-date"]').datepicker('getDate'),
        endDate: $('#event-modal input[name="event-end-date"]').datepicker('getDate')
    }
    
    var dataSource = $('#calendar').data('calendar').getDataSource();

    if(event.id) {
        for(var i in dataSource) {
            if(dataSource[i].id == event.id) {
                dataSource[i].name = event.name;
                dataSource[i].location = event.location;
                dataSource[i].startDate = event.startDate;
                dataSource[i].endDate = event.endDate;
            }
        }
    }
    else
    {
        var newId = 0;
        for(var i in dataSource) {
            if(dataSource[i].id > newId) {
                newId = dataSource[i].id;
            }
        }
        
        newId++;
        event.id = newId;
    
        dataSource.push(event);
    }
    
    $('#calendar').data('calendar').setDataSource(dataSource);
    $('#event-modal').modal('hide');
}

$(function() {
    var currentYear = new Date().getFullYear();

    $('#calendar').calendar({ 
        enableContextMenu: true,
        enableRangeSelection: true,
        contextMenuItems:[
            {
                text: 'Update',
                click: editEvent
            },
            {
                text: 'Delete',
                click: deleteEvent
            }
        ],
        selectRange: function(e) {
            editEvent({ startDate: e.startDate, endDate: e.endDate });
        },
        mouseOnDay: function(e) {
            if(e.events.length > 0) {
                var content = '';
                
                for(var i in e.events) {
                    content += '<div class="event-tooltip-content">'
                                    + '<div class="event-name" style="color:' + e.events[i].color + '">' + e.events[i].name + '</div>'
                                    + '<div class="event-location">' + e.events[i].location + '</div>'
                                + '</div>';
                }
            
                $(e.element).popover({ 
                    trigger: 'manual',
                    container: 'body',
                    html:true,
                    content: content
                });
                
                $(e.element).popover('show');
            }
        },
        mouseOutDay: function(e) {
            if(e.events.length > 0) {
                $(e.element).popover('hide');
            }
        },
        dayContextMenu: function(e) {
            $(e.element).popover('hide');
        },
        dataSource: [
            {
                id: 0,
                name: 'Google I/O',
                location: 'San Francisco, CA',
                startDate: new Date(currentYear, 4, 28),
                endDate: new Date(currentYear, 4, 29)
            },
            {
                id: 1,
                name: 'Microsoft Convergence',
                location: 'New Orleans, LA',
                startDate: new Date(currentYear, 2, 16),
                endDate: new Date(currentYear, 2, 19)
            },
            {
                id: 2,
                name: 'Microsoft Build Developer Conference',
                location: 'San Francisco, CA',
                startDate: new Date(currentYear, 3, 29),
                endDate: new Date(currentYear, 4, 1)
            },
            {
                id: 3,
                name: 'Apple Special Event',
                location: 'San Francisco, CA',
                startDate: new Date(currentYear, 8, 1),
                endDate: new Date(currentYear, 8, 1)
            },
            {
                id: 4,
                name: 'Apple Keynote',
                location: 'San Francisco, CA',
                startDate: new Date(currentYear, 8, 9),
                endDate: new Date(currentYear, 8, 9)
            },
            {
                id: 5,
                name: 'Chrome Developer Summit',
                location: 'Mountain View, CA',
                startDate: new Date(currentYear, 10, 17),
                endDate: new Date(currentYear, 10, 18)
            },
            {
                id: 6,
                name: 'F8 2015',
                location: 'San Francisco, CA',
                startDate: new Date(currentYear, 2, 25),
                endDate: new Date(currentYear, 2, 26)
            },
            {
                id: 7,
                name: 'Yahoo Mobile Developer Conference',
                location: 'New York',
                startDate: new Date(currentYear, 7, 25),
                endDate: new Date(currentYear, 7, 26)
            },
            {
                id: 8,
                name: 'Android Developer Conference',
                location: 'Santa Clara, CA',
                startDate: new Date(currentYear, 11, 1),
                endDate: new Date(currentYear, 11, 4)
            },
            {
                id: 9,
                name: 'LA Tech Summit',
                location: 'Los Angeles, CA',
                startDate: new Date(currentYear, 10, 17),
                endDate: new Date(currentYear, 10, 17)
            }
        ]
    });
    
    $('#save-event').click(function() {
        saveEvent();
    });
});

</script>
</header>
<?php
    if (count($this->app->alerts) > 0)
    {
      echo '<div class="container-fluid">';
      while ($a = $this->app->popAlert())
      {
        echo '<div class="alert alert-dismissible fade show '.$a['type'].'" role="alert">';
        echo $a['message'];
        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
        echo '<span aria-hidden="true">&times;</span>';
        echo '</button></div>';
      }
      echo '</div>';
    }

?>   
    <main role="container" class="container-fluid" style="min-height: 500px" >
    
      <div class="container-fluid" style="margin-top:10px">
        <?=$content?>
      </div>
      
    </main>
     <?=$this->element('bootFooter');?>
    <!-- Optional JavaScript -->
   
  </body>
</html>