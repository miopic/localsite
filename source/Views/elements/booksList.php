<div class="row" >
  <table class="table table-hover">
    <thead class="">
        <tr>
             <th>img</th>
            <th>author</th>
            <th>title</th>
            <th>status</th>
            <th>type</th>
            <th>finished</th>
            <th>pages</th>

        </tr>
    </thead>
    <tbody>
<?php

    $pageNum = isset($pageNum) ? $pageNum  : '';
    foreach ($books as $i=>$book)
    {
        if (($book['smallThumbnail'] != '') && (!is_null($book['smallThumbnail'])))
        {
        $goo = strpos($book['smallThumbnail'], 'google' ) ? 'Y' : ""; 
    } else
    {
        $goo = '';
    }
?>
       <tr style="font-weight:normal; border-bottom:1px solid black; border-top:1px solid black">
         <td>
          <?php
          $actionLink = "view";
          if ($loggedIn)
          {
            $actionLink = "edit";
          }
          if ($book['smallThumbnail'] != '')
          {
          ?>
            <a href="/app/books/<?=$actionLink?>/<?=$book['id']?>/<?=$pageNum?>"><img  class="img-fluid"  src="<?=$book['smallThumbnail']?><?=$goo?>" height="<?=$height?>" width="<?=$width?>"></a>
          <?php
          } else
          {
          ?>
            <a style="text-decoration: none;" href="/app/books/<?=$actionLink?>/<?=$book['id']?>/<?=$pageNum?>">&nbsp;&nbsp;&nbsp;</a>
          <?php
          }
          ?>
        </td>
        
        
        <td><?=$book['author']?></td>
        
        <td><a href="/app/books/view/<?=$book['id']?>/<?=$pageNum?>" target="_blank"><?=$book['title']?></a></td>       
       
        <td>
          <?=$book['status']?>
        </td>
        
         <td class="" style="width:10%">
            <?=$book['type']?>
        </td>
        <?php
            if ($book['finished'] = '0000-00-00')
            {
                $book['finished'] = '';
            }

        ?>
        <td class="" style="width:10%">
            <?=$book['finished']?>
        </td>
        <td class="" style="width:10%">
           <?=$book['pageCount']?>
        </td>
        
      </tr>

<?php
  }

?>
    </tbody>
  </table>
  </div>
<!-- booksList -->