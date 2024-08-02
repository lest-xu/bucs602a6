<?php
require_once('database.php');

// get student id and course id from the form hidden input
$studentID = filter_input(INPUT_POST, 'studentID', FILTER_VALIDATE_INT);
$courseID = filter_input(INPUT_POST, 'courseID');

// Delete the student from the database
if ($studentID && $courseID) {
    // delete query
    $query = 'DELETE FROM sk_students WHERE studentID = :studentID';
    $statement = $db->prepare($query);
    $statement->bindvalue(':studentID', $studentID);
    $statement->execute();
    $statement->closeCursor();

    // Display the Home page
    include('index.php');

} else {
    // show error page
    $error = 'Someting went wrong, please try again!';
    // display error page
    include('error.php');
}

?>