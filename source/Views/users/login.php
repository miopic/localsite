<?php
  $this->set('title', 'Login');
  $this->set('nav', '');
?>

<style type="text/css">
  .log-in-form {
  border: 1px solid #cacaca;
  padding: 2rem;
  margin-top:50px;
  border-radius: 3px; }
</style>


<div class="container">
  <div class="row">

    <div class="col-sm">
    </div>
    <div class="col-sm">
    </div>
    <div class="col-sm">
    </div>
    <div class="col-sm">
        <form action="<?=APP_NAME?>/users/login" method="POST">
            <div class="form-group">
              <input type="text"  class="form-control" id="username" name="username" placeholder="username">
            </div>
            <div class="form-group">
              <input type="password" class="form-control" id="password"  name="pw" placeholder="password">
            </div>
            <button type="submit" class="btn btn-primary float-right">Submit</button>
        </form>
    </div>

  </div>
</div>
