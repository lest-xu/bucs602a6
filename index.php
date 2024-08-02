<?php
require_once('database.php');

// get course ID from URL
$courseID = isset($_GET['course_id']) ? $_GET['course_id'] : '';

// default query to get all students for the queryStudents
$queryStudents = 'SELECT * FROM `sk_students`';
// add filter condition if a course_id is in the URL
if ($courseID) {
    $queryStudents .= ' WHERE `courseID` = :courseID';
}
// default ordering for the queryStudents
$queryStudents .= ' ORDER BY `studentID` ASC';

// prepare and execute the query for students
$statementStudents = $db->prepare($queryStudents);

// check if the course_id in the URL
if ($courseID) {
    $statementStudents->bindValue(':courseID', $courseID);
}

$statementStudents->execute();
$students = $statementStudents->fetchAll();
$statementStudents->closeCursor();

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
<header><h1>Course Manager</h1></header>
<main>
    <center><h1>Student List</h1></center>

    <aside>
        <!-- display a list of Courses -->
        <h2>Courses</h2>
        <nav>
        <ul>
        <?php foreach ($courses as $key => $value): ?>
            <li>
                <a href="?course_id=<?php echo urlencode($value['courseID']); ?>">
                    <?php echo $value['courseID']; ?>
                </a>
            </li>
        <?php endforeach; ?>
        </ul>
        </nav>          
    </aside>

    <section>
        <!-- display a table of Students -->
        
        <table>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>&nbsp;</th>
            </tr>
            <!-- loop through all the students  -->
            <?php foreach ($students as $key => $value): ?>
                <tr>
                    <td><?php echo $value['firstName'] ?></td>
                    <td><?php echo $value['lastName'] ?></td>
                    <td><?php echo $value['email'] ?></td>
                    <td>
                        <form action="delete_student.php" method="post">
                            <input type="hidden" name="courseID" value="<?php echo $value['courseID']; ?>">
                            <input type="hidden" name="studentID" value="<?php echo $value['studentID']; ?>">
                            <input type="submit" value="Delete">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            
        </table>

        <p><a href="add_student_form.php">Add Student</a></p>
         <!-- if there is a course id filter then show the link -->
         <?php if ($courseID): ?>
            <p><a href="index.php">View All Students</a></p>
        <?php endif; ?>

        <p><a href="course_list.php">List Courses</a></p>    

    </section>
</main>

<footer>
    <p>&copy; <?php echo date("Y"); ?> BU CS 602 S2 Assignment 5 - Li Xu . All rights reserved.</p>
</footer>
</body>
</html>