<?php
// index.php

// zjistí aktuální stránku (pokud není, nastaví výchozí)
$page = $_GET['page'] ?? 'home';

// seznam povolených stránek (bezpečnost)
$allowed_pages = ['home', 'articles', 'program', 'login', 'registration', 'userSettings', 'upload'];

if (!in_array($page, $allowed_pages)) {
    $page = 'home';
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Konference</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="style.css" rel="stylesheet">
  
</head>
<body>
<header>
  <nav class="navbar navbar-expand-lg navbar-dark container">
    <a class="navbar-brand" href="index.php"><span>Konference</span></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">

        <li class="nav-item">
          <a class="nav-link <?= ($page === 'home') ? 'active fw-bold' : '' ?>" href="index.php">O nás (Domů)</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= ($page === 'articles') ? 'active fw-bold' : '' ?>" href="index.php?page=articles">Články</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= ($page === 'program') ? 'active fw-bold' : '' ?>" href="index.php?page=program">Program konference</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= ($page === 'upload') ? 'active fw-bold' : '' ?>" href="index.php?page=upload">Nahrát článek</a>
        </li>

      </ul>
      <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle <?= ($page === 'login') ? 'active fw-bold' : '' ?>" href="#" role="button" data-bs-toggle="dropdown">Login</a>
          <ul class="dropdown-menu dropdown-menu-end p-3" style="min-width: 250px;">
            <form>
              <div class="mb-3">
                <input type="text" class="form-control" placeholder="Login">
              </div>
              <div class="mb-3">
                <input type="password" class="form-control" placeholder="Heslo">
              </div>
              <button type="submit" class="btn btn-primary w-100">Přihlásit</button>
              <a href="#" class="d-block mt-2">Obnovit heslo</a>
            </form>
          </ul>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= ($page === 'registration') ? 'active fw-bold' : '' ?>" href="index.php?page=registration">Registrace</a>
        </li>

        <li class="nav-item">
          <a class="nav-link <?= ($page === 'userSettings') ? 'active fw-bold' : '' ?>" href="index.php?page=userSettings">Účet</a>
        </li>
      </ul>
    </div>
  </nav>
</header>


<main class="container mt-4">
     <?php
switch ($page): 
case 'about': 
    include "pages/aboutSite.php";?>
    <?php break; 

case 'login': ?>
    <?php break; 

case 'program':
    include "pages/programSite.php";?>
    <?php break;

case 'articles':
    include "pages/articlesSite.php";?>
    <?php break;

case 'userSettings':
        include "pages/userSettings.php";?>
    <?php break; 
    
case 'upload':
        include "pages/uploadSite.php";?>
    <?php break; 
case 'registration': 
        include "pages/registrationSite.php";?>
    <?php break; 

default:
        include "pages/homeSite.php";?>
<?php endswitch; ?>

</main>

<footer>
  © 2025 Janchal4
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
</body>
</main>
</html>
