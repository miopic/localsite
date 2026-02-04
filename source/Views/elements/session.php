<div class="container">
        <?php 
        if (isset($_SESSION['user']))
        {
          $headings = array( 'field'=>array(),'value'=>array()
          );
        ?>

          <div class="row">
              <?=$this->tag->open('table',array('class'=>'table table100 table-bordered table-hover'))?>
              <?=$this->tag->open('thead',array('class'=>''))?>
                   <?= $this->tag->headings($headings, array('style'=>''))?>
              <?=$this->tag->close('thead')?>
              <?=$this->tag->open('tbody')?>
<?php        
              foreach($_SESSION['user'] as $key=>$value)
              {
                 echo $this->tag->open('tr');
                  echo $this->tag->open('td');
                  echo $key;
                  echo $this->tag->close('td');
                  echo $this->tag->open('td');
                  
                   if ($key == 'expires_at')
                {
                  echo '['.date('Y-m-d H:i:s',$value).']['.$value.']';
                } else
                {
                  echo $value;
                }
                  echo $this->tag->close('td');
                

                echo $this->tag->close('tr');

              }
              echo $this->tag->close('tbody');
          echo $this->tag->close('table');

        }
?>
      
      </div>  