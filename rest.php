<?php

require_once('database.php');

// define the query to get all students
$queryStudents = 'SELECT * FROM `sk_students` ORDER BY `studentID` ASC';

// prepare and execute the query for students
$statementStudents = $db->prepare($queryStudents);
$statementStudents->execute();
$students = $statementStudents->fetchAll();
$statementStudents->closeCursor();


// get all courses from mysql
$queryAllcourses = 'SELECT * FROM `sk_courses` ORDER BY `sk_courses`.`courseID` ASC';

// prepare and execute the query for courses
$statementCourses = $db->prepare($queryAllcourses);
$statementCourses->execute();
$courses = $statementCourses->fetchAll();
$statementCourses->closeCursor();

// get the request format from url query
$format = isset($_GET['format']) ? $_GET['format'] : 'json';
// get the reuqest action from url query
$action = isset($_GET['action']) ? $_GET['action'] : 'none';
// get the courseId from the url query
$courseId = isset($_GET['course']) ? $_GET['course'] : '';

// define the output response as empty array
$response = [];

// check if the reuqest action is courses or students
if ($action === 'courses') {
    // return the courses
    $response = $courses;
} else if ($action === 'students' && !empty($courseId)) {
    // make sure the students enrolled to the course
    if (array_key_exists($courseId, $students)) {
        // found the course by id and return the students form the course
        $response = $students[$courseId];
    } else {
        // course not found return error response
        $response = ['error' => 'Error: course not found!'];
    }
} else {
    // return invalid action message
    $response = ['error' => 'Error: invalid action!'];
}

// set the appropriate content type
setContentType($format);

// check if the request format is json or xml
if ($format === 'json') {
    // set the output to json format
    echo toJsonFormat($response);
} else if ($format === 'xml') {
    // set the output to xml format
    echo toXmlFormat($response, 'response');
} else {
    // output error
    echo "Error: invalid format!";
}

/// HELPER Functions
// 1. set the content type based on the format parameter
function setContentType($format) {
    // check the reuqest format and set teh context type for output
    if ($format === 'json') {
        header('Content-Type: application/json');
    } else if ($format === 'xml') {
        header('Content-Type: application/xml');
    } else {
        header('Content-Type: text/plain');
    }
}

// 2. convert data to JSON format
function toJsonFormat($data) {
    return json_encode($data, JSON_PRETTY_PRINT);
}

// 3. convert data to XML format
function toXmlFormat($data, $rootElement = 'response', $xml = null) {
    if ($xml === null) {
        $xml = new SimpleXMLElement('<' . $rootElement . '/>');
    }
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            toXmlFormat($value, $key, $xml->addChild($key));
        } else {
            $xml->addChild($key, $value);
        }
    }
    return $xml->asXML();
}

?>
