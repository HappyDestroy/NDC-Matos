<?php

if (!isset($_POST['jour'])) {
  header("Location: index.php");
  exit();
}

require_once 'classes/Repartition.php';
require_once 'config.php';

$jour = strtolower($_POST['jour']);

if (!isset(GOOGLE_SHEET_ID[$jour])) {
  die("Jour non configuré.");
}


$google_sheet = file_get_contents(
  "https://docs.google.com/spreadsheets/d/" . GOOGLE_SHEET_ID[$_POST['jour']]['id'] . "/export?format=csv&gid=" . GOOGLE_SHEET_ID[$_POST['jour']]['gid']
);

$repartition = new Repartition();

$resultat = $repartition->traiterCSV($google_sheet);

$hide_association = true;
if (isset($_POST['automatic_association'])) {
  $hide_association = false;
}

$show_comment = false;
if (isset($_POST['show_comment'])) {
  $show_comment = true;
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Alors qui prend quoi...</title>
  <link rel="stylesheet" href="assets/global.css">
  <link rel="stylesheet" href="assets/upload.css">
  <script src="assets/upload.js"></script>
</head>

<body>

  <div class="container">

    <h1>Répartition du matériel pour la plongée du <?= $jour ?></h1>

    <?php if (!empty($resultat['lieu'])): ?>
      <p class="lieu">Lieu : <strong><?= $resultat['lieu'] ?></strong></p>
    <?php endif; ?>

    <div class="actions-btn">
      <button onclick="switchDisplay()" class="switch-display">
        Changer l'affichage
      </button>

      <button onclick="window.print()" class="print-btn">
        🖨️ Imprimer
      </button>
    </div>

    <div class="switch-direction">
      <button onclick="switchDirection('aller')" id="btn-aller" class="aller active">
        Aller
      </button>

      <button onclick="switchDirection('retour')" id="btn-retour" class="retour">
        Retour
      </button>
    </div>


    <div class="result-container">
      <div id="bloc-aller" class="bloc-aller show">
        <?php include 'templates/bloc_aller.php'; ?>
      </div>
      <div id="bloc-retour" class="bloc-retour hidden">
        <?php include 'templates/bloc_retour.php'; ?>
      </div>
    </div>

    <a href="index.php" class="back-btn">
      Retour
    </a>
  </div>
</body>

</html>