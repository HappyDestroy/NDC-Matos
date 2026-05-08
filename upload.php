<?php

require_once 'classes/Repartition.php';

if (!isset($_FILES['csv'])) {
  die("Aucun fichier envoyé.");
}

$tmpName = $_FILES['csv']['tmp_name'];

$repartition = new Repartition();

$resultat = $repartition->traiterCSV($tmpName);

$hide_association = false;
if (isset($_POST['empty_array'])) {
  $hide_association = true;
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

    <h1>Répartition du matériel pour la plongée</h1>

    <div class="actions-btn">
      <button onclick="switchDisplay()" class="switch-display">
        Changer l'affichage
      </button>

      <button onclick="window.print()" class="print-btn">
        🖨️ Imprimer
      </button>
    </div>

    <?php if ($hide_association): ?>
      <div class="liste-matos">
        À répartir :

        <ul>
          <?php foreach ($resultat['repartition'] as $materiels): ?>
            <?php foreach ($materiels as $m): ?>
              <li>
                <strong>
                  <?= htmlspecialchars($m['demandeur']) ?>
                </strong>
                :
                <?= htmlspecialchars($m['materiel']) ?>
              </li>
            <?php endforeach ?>
          <?php endforeach ?>
        </ul>
      </div>
    <?php endif ?>

    <div class="bloc-container <?= $hide_association ? 'hide-association' : ''; ?>" id="bloc-container">
      <?php foreach ($resultat['repartition'] as $personne => $materiels): ?>
        <div class="bloc">
          <div><b><?= htmlspecialchars($personne) ?></b> prend pour : </div>
          <div>
            <?php if (count($materiels) > 0 && !$hide_association): ?>
              <ul>
                <?php foreach ($materiels as $m): ?>
                  <li>
                    <strong>
                      <?= htmlspecialchars($m['demandeur']) ?>
                    </strong>
                    :
                    <?= htmlspecialchars($m['materiel']) ?>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php elseif (!$hide_association): ?>
              Personne (🥳)
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>



    <table class="repartition-table <?= $hide_association ? 'hide-association' : ''; ?>" id="repartition-table">
      <thead>
        <tr>
          <th colspan="2">Personne allant au local</th>
          <th>Matériel à récupérer</th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($resultat['repartition'] as $personne => $materiels): ?>
          <tr>
            <td>
              <strong><?= htmlspecialchars($personne) ?></strong>
            </td>
            <td>Prend pour&nbsp;:</td>
            <td>
              <?php if (count($materiels) > 0 && !$hide_association): ?>
                <ul>
                  <?php foreach ($materiels as $m): ?>
                    <li>
                      <strong>
                        <?= htmlspecialchars($m['demandeur']) ?>
                      </strong>
                      :
                      <?= htmlspecialchars($m['materiel']) ?>
                    </li>
                  <?php endforeach; ?>
                </ul>
              <?php elseif (!$hide_association): ?>
                Personne (🥳)
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <a href="index.php" class="back-btn">
      Retour
    </a>
  </div>
</body>

</html>