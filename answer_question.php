<?php
include('database.php');

// Récupérer le lien depuis la requête GET
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['link'])) {
    $link = $_GET['link'];

    // Fonction pour récupérer la question par le lien
    $question = getQuestionByLink($link);

    // Si la question existe
    if ($question) {
        // Si le formulaire est soumis
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_answer = $_POST['user_answer'];

            // Appel de la fonction pour calculer si la réponse est correcte
            $is_correct = calculateSuccess($question['answer'], $user_answer);

            // Enregistrer la tentative dans la table attempts
            recordAttempt($question['id'], $user_answer, $is_correct);

            // Mettre à jour la table questions avec le message approprié
            $message_column = $is_correct ? 'success_message' : 'fail_message';
            $message = $question[$message_column];

            // Mettre à jour la question dans la base de données
            updateQuestionMessage($question['id'], $message_column, $message);

            // Afficher le message
            echo "<p>$message</p>";
        }
    } else {
        // Si le lien est invalide, ne rien afficher
        exit();
    }
} else {
    // Si le lien n'est pas fourni, ne rien afficher
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Répondre à une question</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
<body>
    <?php if (isset($question)): ?>
        <h1>Répondre à une question</h1>
        <p>Question : <?php echo $question['question']; ?></p>

        <!-- Afficher le formulaire uniquement si le message n'est pas défini -->
        <?php if (empty($message)): ?>
            <form method="post" action="answer_question.php?link=<?php echo $link; ?>">
                <label for="user_answer">Votre réponse :</label>
                <input type="text" name="user_answer" required>
                <button type="submit">Valider</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>

<?php
// Fonction pour mettre à jour le message d'une question dans la table questions
function updateQuestionMessage($questionId, $column, $message) {
    global $conn;
    $stmt = $conn->prepare("UPDATE questions SET $column = ? WHERE id = ?");
    $stmt->bind_param("si", $message, $questionId);
    $stmt->execute();
    $stmt->close();
}
?>
