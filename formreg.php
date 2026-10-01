<?php
include 'temp/head.php';
include 'temp/nav.php';
include 'temp/bd.php';
?>
<form method="post" action="reg.php">
  <h1 class="mb-3 mt-3">Регистрация</h1>
  <div class="mb-3">
    <label for="fio" class="form-label">ФИО</label>
    <input type="text" class="form-control" id="fio" name="fio"  required pattern="[А-ЯЁа-яё\s\-]+" required >
  </div>
  <div class="mb-3">
    <label for="login" class="form-label">Логин</label>
    <input type="text" class="form-control" id="login" name="login" minlength="6" pattern="[a-zA-Z0-9]+" required>
  </div>
  <div class="mb-3">
    <label  for="pass">Пароль</label>
    <input type="password" class="form-control" id="pass" name="password" minlenght="8" required>
  </div>
  <div class="mb-3"> <label for="email">E-mail</label> <input type="email" class="form-control" id="email" name="email" required> 
   <div class="invalid-feedback"> Пожалуйста, введите корректный адрес электронной почты. </div> </div>
   <div class="mb-3">
    <label  for="tel">Телефон</label>
   <input type="tel" class="form-control" id="tel" name="phone" placeholder="8(XXX)XXX-XX-XX" required pattern="^8$?\d{3}$? ?\d{3}-?\d{2}-?\d{2}$" required >
  </div>
<button type="submit" class="btn border border-5 rounded-pill " style=" background-color: transparent; border: none; padding: 0.375rem 0.75rem;  Bootstrap */ font-size: 1rem; /* Размер шрифта */ line-height: 1.5; /* Межстрочный интервал */ background-image: linear-gradient( 90deg, rgba(38, 18, 94, 0.9), rgba(65, 71, 153, 0.9) ); color: #eaeef5 !important;  "> Зарегистрироватться </button>
</form>