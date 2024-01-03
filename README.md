# Project BD - Asso sportive et culturelle
Mehdi Ghoulam & Anthony Menghi

## Analyse & Conception

### Analyse
#### Couverture minimale

### Conception
#### Modèle conceptuel de données

#### Schéma relationnel
- Section (code_section, nom, debut_saison, nro_referant)
- Activite (nro_activite, libelle, description, jour, horaire, duree, tarif, lieu, capacite, adresse, #code_section)
- Benevole (nro_benevole, nom, prenom, telephone, email)
- Adherent (nro_adherent, nom, prenom, date_naissance, telephone, email, adresse, code_postal, ville)
- Membre (nro_benevole, code_section, fonction)
- Inscrit (nro_activite, nro_adherent, nro_cheque, date_inscription)
- Lien (nro_adherent1, nro_adherent2, nature)

## Algèbre relationnelle

### Activités de l'adhérent
SQL Query: SELECT a.nro_adherant, i.date_inscription, ac.libelle, s.nom FROM adherants a INNER JOIN inscrits i ON a.nro_adherant = i.nro_adherant INNER JOIN activites ac ON i.nro_activite = ac.nro_activite INNER JOIN sections s ON ac.code_section = s.code_section WHERE a.nro_adherant = ?

### Informations sur une section
SQL Query: SELECT s.code_section, s.nom, s.debut_saison, CONCAT(b.nom, ' ', b.prenom) as referent FROM sections s LEFT JOIN benevoles b ON s.nro_referent = b.nro_benevole;

### Infos bénévole d'une section
SQL Query: SELECT b.nro_benevole, b.nom, b.prenom, b.telephone, b.email, m.fonction FROM benevoles b JOIN membres m ON m.nro_benevole = b.nro_benevole WHERE m.code_section = ?

### Places restantes des activités
SQL Query: SELECT a.nro_activite, a.libelle, (a.capacite - COUNT(i.nro_adherant)) > 0 AS places_disponibles FROM activites a LEFT JOIN inscrits i ON a.nro_activite = i.nro_activite WHERE a.code_section = ? GROUP BY a.nro_activite;

## Réalisation

Ce projet utilise PHP 8.3 et a été conçu pour offrir une expérience utilisateur fluide. Il comprend une architecture bien définie pour garantir une maintenance optimale.

Des outils de visualisation ont été intégrés pour les pages dédiées à la base de données. Les captures d'écran fournissent une illustration visuelle succincte de l'interface.

[Site de démonstration](https://www.gelk.fr/project-bd/)
[Code source](https://github.com/gmed2b/php-db-project)
