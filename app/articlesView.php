<?php include "layout/header.php"; ?>

<h1>Články</h1>

<?php foreach ($articles as $a): ?>
    <p><?= $a["title"] ?></p>
<?php endforeach; ?>

<?php include "layout/footer.php"; ?>
