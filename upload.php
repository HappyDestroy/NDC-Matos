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

    <?php if ($hide_association): ?>
      <div class="liste-matos">
        À répartir :

        <ul>
          <?php foreach ($resultat['repartition'] as $entry): ?>
            <?php foreach ($entry['materiels'] ?? [] as $m): ?>
              <li>
                <strong>
                  <?= htmlspecialchars($m['demandeur']) ?>
                </strong>
                :
                <?= htmlspecialchars($m['materiel']) ?>
                <?php if (!empty($m['commentaire'])): ?>
                  <div style="color:#666;font-size:0.9em;margin-top:4px;">Commentaire: <?= htmlspecialchars($m['commentaire']) ?></div>
                <?php endif; ?>
              </li>
            <?php endforeach ?>
          <?php endforeach ?>
        </ul>
      </div>
    <?php endif ?>

    <div class="bloc-container <?= $hide_association ? 'hide-association' : ''; ?>" id="bloc-container">
      <?php foreach ($resultat['repartition'] as $personne => $entry): ?>
        <?php $materiels = $entry['materiels'] ?? []; ?>
        <div class="bloc">
          <div>
            <b><?= htmlspecialchars($personne) ?></b>
            <?php if (!empty($entry['responsable'])): ?>
              <small style="color: #666;">Responsable <?= htmlspecialchars(strtolower($entry['responsable'])) ?></small>
            <?php endif; ?>
          </div>
          <div>Prend pour : </div>
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
                    <?php if (!empty($m['commentaire'])): ?>
                      <div style="color:#666;font-size:0.9em;margin-top:4px;">Commentaire: <?= htmlspecialchars($m['commentaire']) ?></div>
                    <?php endif; ?>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php elseif (!$hide_association): ?>
              Personne 🥳
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
        <?php foreach ($resultat['repartition'] as $personne => $entry): ?>
          <?php $materiels = $entry['materiels'] ?? []; ?>
          <tr>
            <td>
              <strong><?= htmlspecialchars($personne) ?></strong>
              <?php if (!empty($entry['responsable'])): ?>
                <small style="color: #666;">Responsable <?= htmlspecialchars(strtolower($entry['responsable'])) ?></small>
              <?php endif; ?>
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
                      <?php if (!empty($m['commentaire'])): ?>
                        <div style="color:#666;font-size:0.9em;margin-top:4px;">Commentaire: <?= htmlspecialchars($m['commentaire']) ?></div>
                      <?php endif; ?>
                    </li>
                  <?php endforeach; ?>
                </ul>
              <?php elseif (!$hide_association): ?>
                Personne 🥳
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