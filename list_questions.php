<?php
include('database.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fonction pour trier les questions par pourcentage de réussite
function sortBySuccessPercentage($a, $b) {
    return $a['success_percentage'] - $b['success_percentage'];
}

$questions = getAllQuestions();

// Vérifie si un tri est demandé
$sortOrder = isset($_GET['order']) ? $_GET['order'] : 'desc';

// Trier les questions par pourcentage de réussite
usort($questions, 'sortBySuccessPercentage');

// Inverser l'ordre si demandé
if ($sortOrder === 'desc') {
    $questions = array_reverse($questions);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des questions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
<body>
    <h1>Liste des questions</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Question</th>
                <th><a href="?order=<?php echo ($sortOrder === 'asc') ? 'desc' : 'asc'; ?>">Pourcentage de réussite</a></th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($questions as $question): ?>
                <tr>
                    <td><?php echo $question['question']; ?></td>
                    <td><?php echo $question['success_percentage']; ?>%</td>
                    <td>
                        <form method="post" action="delete_question.php">
                            <input type="hidden" name="question_id" value="<?php echo $question['id']; ?>">
                            <button type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Bouton pour télécharger les tables en CSV -->
    <form method="post" action="download_tables.php">
        <button type="submit">Télécharger les tables en CSV</button>
    </form>
</body>
</html>
