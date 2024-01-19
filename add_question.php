<?php
include('database.php');

// Fonction pour générer un lien unique
function generateUniqueLink() {
    return 'unique_link_' . uniqid(); // Modifiez cela selon vos besoins
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $question = $_POST['question'];
    $answer = $_POST['answer'];
    $success_message = $_POST['success_message'];
    $fail_message = $_POST['fail_message'];

    $link = generateUniqueLink();

    addQuestion($question, $answer, $success_message, $fail_message, $link);
    $answer_link = "answer_question.php?link=$link"; // Lien vers la page pour répondre

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une question</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</head>
<body>
    <h1>Ajouter une question</h1>
    <form method="post" action="add_question.php">
        <!-- Formulaire pour ajouter une question -->
        <label for="question">Question :</label>
        <input type="text" name="question" required><br>

        <label for="answer">Réponse attendue :</label>
        <input type="text" name="answer" required><br>

        <label for="success_message">Message de succès :</label>
        <input type="text" name="success_message" required><br>

        <label for="fail_message">Message de mauvaise réponse :</label>
        <input type="text" name="fail_message" required><br>

        <button type="submit">Ajouter la question</button>
    </form>

    <?php if (isset($answer_link)): ?>
        <p>Le lien pour répondre à la question est : <a href="<?php echo $answer_link; ?>"><?php echo $answer_link; ?></a></p>
    <?php endif; ?>
</body>
</html>
