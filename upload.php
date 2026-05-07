<?php

require_once 'classes/Repartition.php';

if (!isset($_FILES['csv'])) {
    die("Aucun fichier envoyé.");
}

$tmpName = $_FILES['csv']['tmp_name'];

$repartition = new Repartition();

$resultat = $repartition->traiterCSV($tmpName);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultat répartition</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="container">

    <h1>Répartition du matériel</h1>

    <div class="section">

        <h2>Personnes allant au local</h2>

        <ul>
            <?php foreach ($resultat['volontaires'] as $v): ?>
                <li><?= htmlspecialchars($v) ?></li>
            <?php endforeach; ?>
        </ul>

    </div>

    <div class="section">

        <h2>Répartition</h2>

        <?php foreach ($resultat['repartition'] as $personne => $materiels): ?>

            <div class="card">

                <h3><?= htmlspecialchars($personne) ?></h3>

                <?php if (count($materiels) > 0): ?>

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

                <?php else: ?>

                    <p>Aucun matériel attribué</p>

                <?php endif; ?>

            </div>

        <?php endforeach; ?>

    </div>

    <a href="index.php" class="back-btn">
        Retour
    </a>

</div>

</body>
</html>
