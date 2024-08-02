<?php
require_once('database.php');

// Get the course form data
// get student information from the form input
$courseID = filter_input(INPUT_POST, 'course_id');
$courseName = filter_input(INPUT_POST, 'course_name');


//check if the input is valid
if (!$courseID || !$courseName) {
    // show error msg when missing value or invalid of the input
    $error = 'Invalid course information, please try again!';
    // display error page
    include('error.php');
} else {
    // Add the course to the database  
    // insert query for adding a course record
    $queryInsert = 'INSERT INTO 
                                sk_courses (courseID, courseName)
                            VALUES
                                (:courseID, :courseName)';
    $statement = $db->prepare($queryInsert);
    $statement->bindvalue(':courseID', $courseID);
    $statement->bindvalue(':courseName', $courseName);
    $statement->execute();
    $statement->closeCursor();

    // Display the Course List page
    include('course_list.php');
}
