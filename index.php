<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NDC Matos</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="container">

    <div class="header">
        <img src="./logo.svg" alt="">
        <h1>NDC Matos</h1>
    </div>
    <p class="subtitle">Répartition du matériel de plongée</p>

    <form action="upload.php" method="POST" enctype="multipart/form-data">

        <div class="card">
            <label for="csv">Ajouter le fichier CSV :</label>
            <input type="file" name="csv" id="csv" accept=".csv" required>
        </div>

        <button type="submit">
            Générer la répartition
        </button>

    </form>

</div>

</body>
</html>
