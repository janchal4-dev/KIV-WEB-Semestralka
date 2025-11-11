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
                    <a class="nav-link <?= ($_GET["page"] ?? "home") === "home" ? "active fw-bold" : "" ?>"
                       href="index.php">O nás (Domů)</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= ($_GET["page"] ?? "") === "articles" ? "active fw-bold" : "" ?>"
                       href="index.php?page=articles">Články</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= ($_GET["page"] ?? "") === "program" ? "active fw-bold" : "" ?>"
                       href="index.php?page=program">Program konference</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= ($_GET["page"] ?? "") === "upload" ? "active fw-bold" : "" ?>"
                       href="index.php?page=upload">Nahrát článek</a>
                </li>

            </ul>

            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle <?= ($page === 'login') ? 'active fw-bold' : '' ?>"
                       href="#" id="loginDropdown"
                       role="button"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">
                        Login
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end p-3">
                        <form action="/login" method="post">
                            <div class="mb-3">
                                <input type="text" name="login" class="form-control" placeholder="Login">
                            </div>
                            <div class="mb-3">
                                <input type="password" name="password" class="form-control" placeholder="Heslo">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Přihlásit</button>
                            <a href="#" class="d-block mt-2">Obnovit heslo</a>
                        </form>
                    </ul>
                </li>


                <li class="nav-item">
                    <a class="nav-link <?= ($_GET["page"] ?? "") === "registration" ? "active fw-bold" : "" ?>"
                       href="index.php?page=registration">Registrace</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= ($_GET["page"] ?? "") === "userSettings.twig" ? "active fw-bold" : "" ?>"
                       href="index.php?page=userSettings">Účet</a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<main class="container mt-4">
