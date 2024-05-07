<?php
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle login form submission
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username and password match
    if ($username == 'fcds' && $password == '1234') {
        // Set session variables
        $_SESSION['loggedin'] = true;

        // Redirect to avoid form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        // Display login form with error message
        echo "<p style='color: red; text-align: center;'>Invalid credentials. Please try again.</p>";
        displayLoginForm();
    }
} elseif (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    // Display student list if session is active
    displayStudentList();
} else {
    // Display login form initially
    displayLoginForm();
}

// Function to display login form
function displayLoginForm()
{
    echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>FCDS - Login</title> <!-- Title for login page -->
            <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css'> <!-- Font Awesome CSS -->
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f7f7f7;
                    color: #333;
                    position: relative; /* Add position relative to the body */
                }
                
                h1 {
                    text-align: center;
                    margin-top: 30px;
                    color: #555;
                }
                
                form {
                    width: 300px;
                    margin: 0 auto;
                    padding: 20px;
                    background-color: #fff;
                    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
                }
                
                input[type='text'],
                input[type='password'] {
                    width: 100%;
                    padding: 10px;
                    margin-bottom: 10px;
                    box-sizing: border-box;
                }
                
                button {
                    width: 100%;
                    padding: 10px;
                    background-color: #007bff;
                    color: #fff;
                    border-radius: 15px;
                    cursor: pointer;
                    transition: 0.7s;
                }
                
                button:hover {
                    background-color: #0056b3;
                }

                /* Style for the icon */
                .fab.fa-facebook {
                    font-size: 4em; /* Make the icon bigger */
                    position: absolute; /* Position absolutely */
                    top: 20px; /* Adjust top position */
                    left: 20px; /* Adjust right position */
                }
                
                .fas.fa-globe {
                    font-size: 4em; /* Make the icon bigger */
                    position: absolute; /* Position absolutely */
                    top: 100px; /* Adjust top position */
                    left: 20px;
                    bottom: 20px; /* Adjust right position */
                }

                /* Style for the main section */
                .main {
                    width: 100%;
                    min-height: 100vh;
                    display: grid;
                    align-items: center;
                    background: url(photo/ph.jpg) no-repeat;
                    background-size: cover;
                    background-position: center;
                }
            </style>
        </head>
        <body class='main'>
            
            <h1>FCDS - Login</h1>
            <form method='post' action='" . $_SERVER['PHP_SELF'] . "'>
                <input type='text' name='username' placeholder='Username' required><br>
                <input type='password' name='password' placeholder='Password' required><br>
                <button type='submit'>Login</button>
            </form>
           
        <div>
            <a href='https://www.facebook.com/FCDS.AlexU?mibextid=ZbWKwL' target='_blank'><i class='fab fa-facebook'></i></a>
            <a href='https://gs.alexu.edu.eg/FCDS/index.php' target='_blank'> <i class='fas fa-globe'></i></a>
        </div>
        
        
        </body>
        </html>";
}

// Function to display student list
function displayStudentList()
{
    // Redirect to login page if not logged in
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("Location: index.php");
        exit();
    }

    // Database connection parameters
    $servername = "mysql-db"; // Use the service name defined in Docker Compose
    $username = "root";
    $password = "hamdy423651";
    $dbname = "FCDS";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // SQL query to retrieve student data
    $sql = "SELECT ID, Name, AGE, CGPA FROM Student";
    $result = $conn->query($sql);

    echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>FCDS - Student List</title> <!-- Title for student list page -->
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f7f7f7;
                    color: #333;
                }
                
                h1 {
                    text-align: center;
                    margin-top: 30px;
                    color: #555;
                }
                
                table {
                    width: 80%;
                    margin: 20px auto;
                    border-collapse: collapse;
                    background-color: #fff;
                    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
                }
                
                th,
                td {
                    padding: 12px;
                    border-bottom: 1px solid #ddd;
                    text-align: left;
                    cursor: pointer;
                    /* Add cursor pointer for clickable headers */
                }
                
                th {
                    background-color: #f2f2f2;
                }
                
                button {
                    margin-top: 20px;
                    padding: 10px;
                    background-color: #007bff;
                    color: #fff;
                    border-radius: 15px;
                    transition: 0.7s;

                    cursor: pointer;
                }
                
                button:hover {
                    background-color: #0056b3;
                }
            </style>
        </head>
        <body>
            <h1>FCDS - Student List</h1>";

    if ($result->num_rows > 0) {
        // Display table header with sorting functionality
        echo "<table id='studentTable'>";
        echo "<tr><th onclick='sortTable(0)'>ID</th><th onclick='sortTable(1)'>Name</th><th onclick='sortTable(2)'>Age</th><th onclick='sortTable(3)'>CGPA</th></tr>";

        // Output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["ID"] . "</td>";
            echo "<td>" . $row["Name"] . "</td>";
            echo "<td>" . $row["AGE"] . "</td>";
            echo "<td>" . $row["CGPA"] . "</td>";
            echo "</tr>";
        }

        echo "</table>";

        // Add logout button
        echo "<form method='post' action='" . $_SERVER['PHP_SELF'] . "'><button type='submit' name='logout'>Logout</button></form>";

        // Handle logout
        if (isset($_POST['logout'])) {
            // Destroy the session
            session_destroy();
            // Redirect to login page
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        }
    } else {
        echo "<p>No students found.</p>";
    }

    echo "
        <script>
            var sortOrder = 1; // 1 for ascending, -1 for descending

            function sortTable(colIndex) {
                var table, rows, switching, i, x, y, shouldSwitch;
                table = document.getElementById('studentTable');
                switching = true;

                // Set the sorting order on each click
                if (sortOrder === 1) {
                    sortOrder = -1; // Change to descending on the first click
                } else {
                    sortOrder = 1; // Change to ascending on the second click
                }

                while (switching) {
                    switching = false;
                    rows = table.rows;

                    for (i = 1; i < (rows.length - 1); i++) {
                        shouldSwitch = false;
                        x = rows[i].getElementsByTagName('td')[colIndex];
                        y = rows[i + 1].getElementsByTagName('td')[colIndex];

                        if (sortOrder === 1) {
                            if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                                shouldSwitch = true;
                                break;
                            }
                        } else {
                            if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                                shouldSwitch = true;
                                break;
                            }
                        }
                    }

                    if (shouldSwitch) {
                        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                        switching = true;
                    }
                }
            }
        </script>
        </body>
        </html>";

    // Close connection
    $conn->close();
}
?>