<?php
include('database.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['question_id'])) {
    $questionId = $_POST['question_id'];
    deleteQuestion($questionId);
    // Rediriger vers la liste des questions après la suppression
    header("Location: list_questions.php");
    exit();
}
?>
