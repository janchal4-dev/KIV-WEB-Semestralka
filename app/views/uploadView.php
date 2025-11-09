    <h1>Nahrát PDF soubor</h1>
    <form action="upload.php" method="post" enctype="multipart/form-data" class="p-3 border rounded bg-light">
      <div class="mb-3">
        <label for="pdfFile" class="form-label">Vyber PDF soubor:</label>
        <input class="form-control" type="file" name="pdfFile" id="pdfFile" accept="application/pdf" required>
      </div>
      <button type="submit" class="btn btn-primary">Nahrát</button>
    </form>