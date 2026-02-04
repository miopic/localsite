<div class="row">
<?php
    $this->set('dateFormat', YMDDYHI);
  if (isset($recordList) && count($recordList))
  {
?>
    <h2>Inserted</h2>
    <?=$this->element('activitiesRecords')?>
      </tbody>
    </table>
<?php
  } else {
?>
    <h2>No Records Inserted</h2>
<?php
  }
?>
</div>
