<?php if($this->wrong_password) { ?>
      <h4>������������ ������</h4>
<?php $this->wrong_password = false;
} else { ?>
	  <h4>������� ������:</h4>
<?php } ?>
  <form id="tlogin" class="left_panel" name="tlogin" method="post" action="auth.php">
  <p>�����<br />
      <input type="text" name="name" />
      <br />
    ������<br />
      <input type="password" name="password" />
      <br />
      <input class="submit" name="auth" type="submit" value="�����" />
  </p>
  </form>