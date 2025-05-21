<?php
function validerFormulaireReservation(array $trip, array $post): array
{
    $erreurs = [];

    if (!isset($post['date_depart']) || !isset($post['date_retour'])) {
        $erreurs[] = "Les dates de voyage sont obligatoires.";
        return $erreurs;
    }

    try {
        $date_depart = new DateTime($post['date_depart']);
        $date_retour = new DateTime($post['date_retour']);
        $today = new DateTime();
        $today->setTime(0, 0, 0);

        if ($date_depart < $today) {
            $erreurs[] = "La date de départ ne peut pas être dans le passé.";
        }

        if ($date_retour < $date_depart) {
            $erreurs[] = "La date de retour doit être après la date de départ.";
        }
        
    } catch (Exception $e) {
        $erreurs[] = "Dates invalides.";
    }

    if (!isset($post["hebergements"])) {
        $erreurs[] = "Veuillez sélectionner un hébergement.";
    }

    if (!isset($post['nb_personnes']) || !is_array($post['nb_personnes'])) {
        $erreurs[] = "Nombre de personnes non spécifié.";
        return $erreurs;
    }

    $hebergement = $post["hebergements"];
    $nb_personnes_hebergement = (int) ($post["nb_personnes"][$hebergement] ?? 0);

    if ($nb_personnes_hebergement < 1) {
        $erreurs[] = "Le nombre de personnes pour l'hébergement est invalide.";
    }

    if (isset($post["activites"])) {
        foreach ($post["activites"] as $activite) {
            $nb_pers_activite = (int) ($post["nb_personnes"][$activite] ?? 0);
            if ($nb_pers_activite > $nb_personnes_hebergement) {
                $erreurs[] = "$nb_pers_activite personne(s) pour l’activité « $activite », mais seulement $nb_personnes_hebergement prévues pour l’hébergement.";
            }
        }
    }

    return $erreurs;
}
