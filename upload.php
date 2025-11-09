<?php
// složka, kam se soubory uloží
$uploadDir = __DIR__ . "/uploads/";

// pokud složka neexistuje, vytvoří ji
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// kontrola, zda soubor byl nahrán
if (isset($_FILES['pdfFile']) && $_FILES['pdfFile']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['pdfFile']['tmp_name'];
    $fileName = basename($_FILES['pdfFile']['name']);
    $fileSize = $_FILES['pdfFile']['size'];
    $fileType = $_FILES['pdfFile']['type'];

    // přípona
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // povolené přípony
    $allowedExtensions = ['pdf'];

    if (in_array($fileExtension, $allowedExtensions) && $fileType === 'application/pdf') {
        // unikátní jméno souboru (aby nepřepsal jiný)
        $newFileName = uniqid('pdf_', true) . '.' . $fileExtension;
        $destPath = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            echo "<p style='color:green'>Soubor byl úspěšně nahrán jako: $newFileName</p>";

            // uložení názvů a časů do souboru
            $file = fopen("uploaded.csv","a");
            fwrite($file, "\n$fileName:$newFileName");
            sleep(2);
            header("Location: index.php");



        } else {
            echo "<p style='color:red'>Chyba při přesunu souboru.</p>";
        }
    } else {
        echo "<p style='color:red'>Povolený je pouze PDF soubor.</p>";
        sleep(2);
        header("Location: index.php?page=upload");
    }
} else {
    echo "<p style='color:red'>Nebylo vybráno žádné PDF nebo nastala chyba při uploadu.</p>";
}
?>