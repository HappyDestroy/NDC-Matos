<?php

class Repartition
{
    // Indices des colonnes CSV
    private const COL_LIEU = 2;
    private const COL_PRENOM = 3;
    private const COL_NOM = 4;
    private const COL_RESPONSABLE = 8;
    private const COL_ALLER = 9;
    private const COL_MATERIEL = 10;
    private const COL_COMMENTAIRE = 12;
    
    private const SKIP_HEADER_ROWS = 3;

    public function traiterCSV($csv)
    {
        $handle = $this->ouvrirCSV($csv);
        if (!$handle) {
            die("Impossible de lire le contenu CSV.");
        }

        // Parse les données du CSV
        list($volontaires, $demandes, $lieu) = $this->parserCSV($handle);
        fclose($handle);

        // Répartit le matériel entre les volontaires
        $repartition = $this->repartirMateriel($volontaires, $demandes);

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

        $volontaires = [];
        $demandes = [];
        $lieu = '';

        while (($data = fgetcsv($handle, 1000, ',')) !== false) {

            if(trim($data[self::COL_MATERIEL] ?? '') == "Aucun besoin") {
                continue;
            }

            // Capture le lieu depuis la première ligne de données
            $lieu = $lieu ?: trim($data[self::COL_LIEU] ?? '');
            
            $nomComplet = trim(
                ($data[self::COL_PRENOM] ?? '') . ' ' . ($data[self::COL_NOM] ?? '')
            );
            $aller = strtolower(trim($data[self::COL_ALLER] ?? ''));
            $materiel = trim($data[self::COL_MATERIEL] ?? '');
            $responsable = trim($data[self::COL_RESPONSABLE] ?? '');
            $commentaire = trim($data[self::COL_COMMENTAIRE] ?? '');

            // Ajoute les volontaires
            if ($aller === 'aller') {
                $volontaires[] = [
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

        return [$volontaires, $demandes, $lieu];
    }

    /**
     * Répartit le matériel entre les volontaires de manière équitable
     */
    private function repartirMateriel($volontaires, $demandes)
    {
        if (empty($volontaires)) {
            return [];
        }
        // Initialise la répartition avec le responsable et une liste vide de matériels
        $repartition = [];
        foreach ($volontaires as $v) {
            $repartition[$v['nom']] = [
                'responsable' => $v['responsable'] ?? '',
                'materiels' => []
            ];
        }

        // Mélange les volontaires pour une répartition aléatoire
        shuffle($volontaires);

        // Attribue le matériel en tournant parmi les volontaires
        foreach ($demandes as $idx => $demande) {
            $personne = $volontaires[$idx % count($volontaires)]['nom'];
            $repartition[$personne]['materiels'][] = $demande;
        }

        return $repartition;
    }
}
