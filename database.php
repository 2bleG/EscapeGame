<?php
// Connexion à la base de données (à configurer selon votre environnement)
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "escape_game";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function getAllQuestions() {
    global $conn;
    $questions = array();
    $sql = "SELECT * FROM questions";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $questions[] = $row;
        }
    }

    return $questions;
}

function addQuestion($question, $answer, $success_message, $fail_message, $link) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO questions (question, answer, success_message, fail_message, link) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $question, $answer, $success_message, $fail_message, $link);
    $stmt->execute();
    $stmt->close();
}

function getQuestionByLink($link) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM questions WHERE link = ?");
    $stmt->bind_param("s", $link);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

function calculateSuccessPercentage($questionId) {
    global $conn;

    // Calcul du pourcentage de réussite
    $totalAttempts = 0;
    $successAttempts = 0;

    $stmt = $conn->prepare("SELECT * FROM attempts WHERE question_id = ?");
    $stmt->bind_param("i", $questionId);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $totalAttempts++;
        if ($row['is_correct']) {
            $successAttempts++;
        }
    }

    return ($totalAttempts > 0) ? (($successAttempts / $totalAttempts) * 100) : 0;
}

function deleteQuestion($questionId) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM questions WHERE id = ?");
    $stmt->bind_param("i", $questionId);
    $stmt->execute();
    $stmt->close();
}
?>
