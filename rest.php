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

// map the course results to return only course ID and name
$courses = array_map(function ($course) {
    return [
        'courseID' => $course['courseID'],
        'courseName' => $course['courseName']
    ];
}, $courses);

// map the student results to return student info
$students = array_map(function ($item) {
    return [
        'studentID' => $item['studentID'],
        'courseID' => $item['courseID'],
        'firstName' => $item['firstName'],
        'lastName' => $item['lastName'],
        'email' => $item['email']
    ];
}, $students);


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

    // make sure the students are enrolled in the course
    $enrolledStudents = array_filter($students, function ($student) use ($courseId) {
        return $student['courseID'] == $courseId;
    });

    if (!empty($enrolledStudents)) {
        // found the course by ID and return the students from the course
        $response = array_values($enrolledStudents);
    } else {
        // course not found, return error response
        $response = ['error' => 'Course not found!'];
    }
} else {
    // return invalid action message
    $response = ['error' => 'Invalid action!'];
}


// set the appropriate content type based on the format
setContentType($format);

// check if the request format is json or xml
if ($format === 'json') {
    // set the output to json format
    echo toJsonFormat($response);
} else if ($format === 'xml') {
    // set the output to xml format
    $xmlOutput = toXmlFormat($response, $action);
    echo $xmlOutput;
} else {
    // output error
    echo "Error: invalid format!";
}

/// HELPER Functions
// 1. set the content type based on the format parameter
function setContentType($format)
{
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
function toJsonFormat($data)
{
    return json_encode($data, JSON_PRETTY_PRINT);
}

// 3. convert data to XML format
function toXmlFormat($data,  $action)
{
    // check the action if its students or courses and set the root element
    $rootElement = ($action === 'courses') ? 'courses' : 'students';
    // define the xml element
    $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8" ?><' . $rootElement . '/>');
    // loop through the resutls array
    foreach ($data as $item) {
        // set child element either student or course based on action using substr
        $itemElement = $xml->addChild(substr($rootElement, 0, -1));
        foreach ($item as $key => $value) {
            // set attributes of the child element
            $itemElement->addChild($key, htmlspecialchars($value));
        }
    }

    return $xml->asXML();
}
