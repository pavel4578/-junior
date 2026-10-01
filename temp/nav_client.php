<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(90deg, rgba(60, 89, 185, 0.9), rgba(47, 25, 128, 0.9)) !important;">
  <div class="container-fluid">
    
       <a class="navbar-brand d-flex align-items-center text-white" href="#">
      <img src="img/logo.jpeg" alt="Логотип" width="40" height="40" class="d-inline-block align-text-top rounded-circle me-2" style="object-fit: cover;">
 «Junior Hunt» 
    </a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
              <a class="nav-link" href="index.php" style="color: white !important;">Главная</a>
        </li>
                 <li class="nav-item">
          <a class="nav-link" href="lich_cabinet.php" style="color: white !important;">Личный кабинет</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="resume_form.php" style="color: white !important;"> Моё Резюме</a>
        </li>
       
        <li class="nav-item">
          <a class="nav-link" href="logout.php" style="color: white !important;">Выйти  (<?php echo $_SESSION['fio'];?>)</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<div class="container pt-3"> 