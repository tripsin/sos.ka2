<?php if($this->wrong_password) { ?>
      <h4>Неправильные данные</h4>
<?php $this->wrong_password = false;
} else { ?>
	  <h4>Введите данные:</h4>
<?php } ?>
  <form id="tlogin" class="left_panel" name="tlogin" method="post" action="auth.php">
  <p>Логин<br />
      <input type="text" name="name" />
      <br />
    Пароль<br />
      <input type="password" name="password" />
      <br />
      <input class="submit" name="auth" type="submit" value="Войти" />
  </p>
  </form>