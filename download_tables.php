<?php
include('database.php');

// Fonction pour télécharger une table en CSV
function downloadCSV($table, $filename) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen('php://output', 'w');
    fputcsv($output, array_keys($table[0])); // En-têtes

    foreach ($table as $row) {
        fputcsv($output, $row);
    }

    fclose($output);
}

// Télécharger la table "questions" en CSV
$questions = getAllQuestions();
downloadCSV($questions, 'questions.csv');

// Télécharger la table "attempts" en CSV
$attempts = getAllAttempts();
downloadCSV($attempts, 'attempts.csv');
?>
