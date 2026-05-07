<?php

class Repartition
{
    public function traiterCSV($fichier)
    {
        $handle = fopen($fichier, 'r');

        if (!$handle) {
            die("Impossible de lire le fichier CSV.");
        }

        $volontaires = [];
        $demandes = [];

        // Ignore les 4 premières lignes
        for ($i = 0; $i < 4; $i++) {
            fgetcsv($handle, 1000, ',');
        }

        while (($data = fgetcsv($handle, 1000, ',')) !== false) {

            $prenom = trim($data[3] ?? '');
            $nom = trim($data[4] ?? '');

            $aller = trim($data[9] ?? '');
            $materiel = trim($data[10] ?? '');

            $nomComplet = trim($prenom . ' ' . $nom);

            // Personnes allant au local
            if (strtolower($aller) === 'aller') {
                $volontaires[] = $nomComplet;
            }

            // Demande complète de matériel
            if (!empty($materiel)) {

                $demandes[] = [
                    'demandeur' => $nomComplet,
                    'materiel' => $materiel
                ];
            }
        }

        fclose($handle);

        // Initialisation répartition
        $repartition = [];

        foreach ($volontaires as $v) {
            $repartition[$v] = [];
        }

        // Mélange aléatoire des volontaires
        shuffle($volontaires);

        // Attribution complète des demandes
        if (count($volontaires) > 0) {

            $i = 0;

            foreach ($demandes as $demande) {

                $personne = $volontaires[$i % count($volontaires)];

                $repartition[$personne][] = [
                    'demandeur' => $demande['demandeur'],
                    'materiel' => $demande['materiel']
                ];

                $i++;
            }
        }

        return [
            'volontaires' => $volontaires,
            'repartition' => $repartition
        ];
    }
}