<?php
    
    require_once('database.php');

    // Get the student form data
    // get student information from the form input
    $courseID = filter_input(INPUT_POST, 'courseID');
    $firstName = filter_input(INPUT_POST, 'first_name');
    $lastName = filter_input(INPUT_POST, 'last_name');
    $email = filter_input(INPUT_POST, 'email');

    //check if the input is valid
    if (!$courseID || !$firstName ||! $lastName || !$email) {
        // show error msg when missing value or invalid of the input
        $error = 'Invalid student information, please try again!';
        // display error page
        include('error.php');
    } else {
        // Add the student to the database  
        // insert query for adding a student record
        $queryInsertStudent = 'INSERT INTO 
                                    sk_students (courseID, firstName, lastName, email)
                                VALUES
                                    (:courseID, :firstName, :lastName, :email)';
        $statementStudent = $db->prepare($queryInsertStudent);
        $statementStudent->bindvalue(':courseID', $courseID);
        $statementStudent->bindvalue(':firstName', $firstName);
        $statementStudent->bindvalue(':lastName', $lastName);
        $statementStudent->bindvalue(':email', $email);
        $statementStudent->execute();
        $statementStudent->closeCursor();

        // Display the Student List page
        include('index.php');

    }
    
?>