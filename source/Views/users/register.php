<?php
  $this->set('title', 'Register');
  $this->set('nav', '');
?>
<style type="text/css">
  .log-in-form {
  border: 1px solid #cacaca;
  padding: 2rem;
  margin-top:50px;
  border-radius: 3px; }
</style>

<?=$this->element('bootactivitiesSubHeader')?>
<div class="container" style="margin-top:20px">
  <div class="row">

        <form action="<?=APP_NAME?>users/register" method="POST">
            <div class="form-group">
              <input type="text"  class="form-control" id="username" name="username" placeholder="username">
            </div>
            <div class="form-group">
              <input type="password" class="form-control" id="password"  name="pw" placeholder="password">
            </div>
            <div class="form-group">
              <input type="text"  class="form-control" id="username" name="email" placeholder="blah@myplace.com">
            </div>
            <button type="submit" class="btn btn-primary float-right">Submit</button>
        </form>

  </div>
</div>
