<?php
include('database.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Vérifier si le lien est fourni dans la requête GET
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['link'])) {
    $link = $_GET['link'];

    // Fonction pour récupérer la question par le lien
    $question = getQuestionByLink($link);

    if (!$question) {
        // Afficher un message d'erreur si le lien est invalide
        echo "Erreur : Lien invalide";
        exit();
    }
} else {
    // Afficher un message d'erreur si le lien n'est pas fourni
    echo "Erreur : Lien non fourni";
    exit();
}

$success_message = '';
$user_answer = '';

// Gérer la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_answer = $_POST['user_answer'];

    // Appel de la fonction pour calculer si la réponse est correcte
    $is_correct = calculateSuccess($question['answer'], $user_answer);

    // Définir le message de succès ou d'échec
    $success_message = $is_correct ? $question['success_message'] : $question['fail_message'];

    // Enregistrer la tentative dans la table attempts
    recordAttempt($question['id'], $user_answer, $is_correct);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Répondre à une question</title>
</head>
<body>
    <?php if (isset($question)): ?>
        <h1>Répondre à une question</h1>
        <p>Question : <?php echo $question['question']; ?></p>

        <?php if (empty($success_message)): ?>
            <form method="post" action="answer_question.php?link=<?php echo $link; ?>">
                <label for="user_answer">Votre réponse :</label>
                <input type="text" name="user_answer" required>
                <button type="submit">Valider</button>
            </form>
        <?php else: ?>
            <p><?php echo $success_message; ?></p>
        <?php endif; ?>
    <?php else: ?>
        <p>Erreur : Question non trouvée</p>
    <?php endif; ?>
</body>
</html>
