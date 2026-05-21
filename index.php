<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NDC Matos</title>
  <link rel="stylesheet" href="assets/global.css">
  <link rel="stylesheet" href="assets/index.css">
</head>

<body>

  <div class="container">

    <div class="header">
      <img src="./logo.svg" alt="">
      <h1>NDC Matos</h1>
    </div>
    <p class="subtitle">Répartition du matériel de plongée</p>

    <div class="card">
      <form action="upload.php" method="POST">
        <div class="button-container">
          <button type="submit" name="jour" value="mercredi">
            Récupérer le mercredi
          </button>

          <button type="submit" name="jour" value="samedi">
            Récupérer le samedi
          </button>
        </div>

        <label for="automatic_association">
          <input type="checkbox" name="automatic_association" id="automatic_association">
          Laisser l'algorithme gérer l'association du matériel
          <span class="info-icon" data-tooltip="C'est expérimental, faites-moi vos retour.&#10;Quand ceci est coché, l'algorithme assignera automatiquement le matériel aux utilisateurs.&#10;⚠️La répartition n'est pas sauvegardé, à chaque envoi du formulaire elle change⚠️">ℹ️</span>
        </label>
      </form>
    </div>
  </div>
</body>

</html>