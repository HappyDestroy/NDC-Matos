<?php

ini_set('xdebug.var_display_max_depth', 99);

class Repartition
{
    // Indices des colonnes CSV
    private const COL_LIEU = 2;
    private const COL_PRENOM = 3;
    private const COL_NOM = 4;
    private const COL_RESPONSABLE = 8;
    private const COL_ALLER = 9;
    private const COL_MATERIEL = 10;
    private const COL_RETOUR = 11;
    private const COL_COMMENTAIRE = 12;

    private const SKIP_HEADER_ROWS = 3;

    public function traiterCSV($csv)
    {
        $handle = $this->ouvrirCSV($csv);
        if (!$handle) {
            die("Impossible de lire le contenu CSV.");
        }

        // Parse les données du CSV
        list($volontaires_aller, $volontaires_retour, $demandes, $personne_retour, $lieu) = $this->parserCSV($handle);
        fclose($handle);

        // Répartit le matériel entre les volontaires
        $repartition = $this->repartirMateriel($volontaires_aller, $volontaires_retour, $demandes, $personne_retour);

        return [
            'repartition' => $repartition,
            'lieu' => $lieu
        ];
    }

    /**
     * Ouvre le CSV à partir d'un fichier, contenu brut ou ressource
     */
    private function ouvrirCSV($csv)
    {
        if (is_string($csv) && file_exists($csv)) {
            return fopen($csv, 'r');
        }

        if (is_string($csv)) {
            $handle = fopen('php://memory', 'r+');
            if ($handle) {
                fwrite($handle, $csv);
                rewind($handle);
            }
            return $handle;
        }

        return is_resource($csv) ? $csv : false;
    }

    /**
     * Parse le CSV et retourne les volontaires, demandes et lieu
     */
    private function parserCSV($handle)
    {
        // Saute les lignes d'en-tête
        for ($i = 0; $i < self::SKIP_HEADER_ROWS; $i++) {
            fgetcsv($handle, 1000, ',');
        }

        $volontaires_aller = [];
        $volontaires_retour = [];
        $demandes = [];
        $personne_retour = [];
        $lieu = '';

        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            $nomComplet = (trim($data[self::COL_PRENOM] ?? '')) . ' ' . trim(($data[self::COL_NOM] ?? ''));
            $retour = strtolower(trim($data[self::COL_RETOUR] ?? ''));

            if(empty($retour)) {
                $personne_retour[] = $nomComplet;
            }
       
            if (trim($data[self::COL_MATERIEL] ?? '') == "Aucun besoin") {
                continue;
            }

            // Capture le lieu depuis la première ligne de données
            $lieu = $lieu ?: trim($data[self::COL_LIEU] ?? '');

            $aller = strtolower(trim($data[self::COL_ALLER] ?? ''));
            $materiel = trim($data[self::COL_MATERIEL] ?? '');
            $responsable = trim($data[self::COL_RESPONSABLE] ?? '');
            $commentaire = trim($data[self::COL_COMMENTAIRE] ?? '');
        
            // Ajoute les volontaires
            if ($aller === 'aller') {
                $volontaires_aller[] = [
                    'nom' => $nomComplet,
                    'responsable' => $responsable
                ];
            }

            if ($retour === 'retour') {
                $volontaires_retour[] = [
                    'nom' => $nomComplet,
                    'responsable' => $responsable
                ];
            }

            // Ajoute les demandes de matériel
            if (!empty($materiel)) {
                $demandes[] = [
                    'demandeur' => $nomComplet,
                    'materiel' => $materiel,
                    'commentaire' => $commentaire
                ];
            }
        }

        return [$volontaires_aller, $volontaires_retour, $demandes, $personne_retour, $lieu];
    }

    /**
     * Répartit le matériel entre les volontaires de manière équitable
     */
    private function repartirMateriel($volontaires_aller, $volontaires_retour, $demandes, $demandes_retour)
    {
        if (empty($volontaires_aller) && empty($volontaires_retour)) {
            return [];
        }
        // Initialise la répartition avec le responsable et une liste vide de matériels
        $repartition = [];
        foreach ($volontaires_aller as $v) {
            $repartition['aller'][$v['nom']] = [
                'responsable' => $v['responsable'] ?? '',
                'materiels' => []
            ];
        }

        foreach ($volontaires_retour as $v) {
            $repartition['retour'][$v['nom']] = [
                'responsable' => $v['responsable'] ?? '',
                'materiels' => []
            ];
        }

        // Mélange les volontaires pour une répartition aléatoire
        shuffle($volontaires_aller);

        // Attribue le matériel en tournant parmi les volontaires
        foreach ($demandes as $idx => $demande) {
            $personne = $volontaires_aller[$idx % count($volontaires_aller)]['nom'];
            $repartition['aller'][$personne]['materiels'][] = $demande;
        }

        foreach($demandes_retour as $idx => $personne) {
            $volontaire_retour = $volontaires_retour[$idx % count($volontaires_retour)]['nom'];
            $repartition['retour'][$volontaire_retour]['materiels'][] = [
                'demandeur' => $personne,
                'materiel' => '',
                'commentaire' => ''
            ];
        }

        //var_dump($repartition['retour']);die;
        return $repartition;
    }
}
