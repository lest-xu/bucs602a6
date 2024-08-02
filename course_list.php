<?php
require_once('database.php');

// Get all courses
// get all courses from mysql
$queryAllcourses = 'SELECT * FROM `sk_courses` ORDER BY `sk_courses`.`courseID` ASC';

$statementCourses = $db->prepare($queryAllcourses);
$statementCourses->execute();
$courses = $statementCourses->fetchAll();
$statementCourses->closeCursor();

?>
<!DOCTYPE html>
<html>

<!-- the head section -->

<head>
    <title>My Course Manager</title>
    <link rel="stylesheet" type="text/css" href="main.css" />
</head>

<!-- the body section -->

<body>
    <header>
        <h1>Course Manager</h1>
    </header>
    <main>
        <h1>Course List</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
            </tr>

            <!-- add code for the rest of the table here -->
            <!-- loop through all the courses  -->
            <?php foreach ($courses as $key => $value) : ?>
                <tr>
                    <td><?php echo $value['courseID'] ?></td>
                    <td><?php echo $value['courseName'] ?></td>
                </tr>
            <?php endforeach; ?>

        </table>
        <p>
        <h2>Add Course</h2>

        <form action="add_course.php" method="post" id="add_course_form">

            <label>Course Id:</label>
            <input type="text" name="course_id"><br>
            <label>Course Name:</label>
            <input type="text" name="course_name" width="200"><br>

            <label>&nbsp;</label>
            <input type="submit" value="Add Course"><br>

        </form>


        <br>
        <p><a href="index.php">List Students</a></p>

    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> BU CS 602 S2 Assignment 5 - Li Xu . All rights reserved.</p>
    </footer>
</body>

</html>