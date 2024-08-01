<?php
require('database.php');


?>
<!DOCTYPE html>
<html>

<!-- the head section -->
<head>
    <title>My Course Manager</title>
    <link rel="stylesheet" type="text/css" href="main.css">
</head>

<!-- the body section -->
<body>
    <header><h1>Course Manager</h1></header>

    <main>
        <h1>Add Student</h1>
        <form action="add_student.php" method="post"
              id="add_student_form">

            <label>Course:</label>

            
            <label>First Name:</label>
            <input type="text" name="first_name"><br>

            <label>Last Name:</label>
            <input type="text" name="last_name"><br>

            <label>Email:</label>
            <input type="email" name="email"><br>


            <label>&nbsp;</label>
            <input type="submit" value="Add Student"><br>
        </form>
        <p><a href="index.php">View Student List</a></p>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> BU CS 602 S2 Assignment 5 - Li Xu . All rights reserved.</p>
    </footer>
</body>
</html>