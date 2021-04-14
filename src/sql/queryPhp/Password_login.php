<!--<html>-->
<!--<body>-->
<!--<form method="POST" action="Password_login.php">-->
<!--    <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">-->
<!---->
<!--    <label for="Registration">Log In: As Customer or Manager</label><br>-->
<!---->
<!--    <label for="Username">User Name:</label><br>-->
<!--    <input type="text" id="Username" name="Username" placeholder="Username*"> <br /><br />-->
<!---->
<!--    <label for="Password">PassWord:</label><br>-->
<!--    <input type="text" id="Password" name="Password" placeholder="Password*"> <br /><br />-->
<!---->
<!--    <p>You are a customer or a Manager?:</p>-->
<!---->
<!--    <input type="radio" name="query" value="Customer">Customer<br>-->
<!--    <input type="radio" name="query" value="Manager">Manager<br>-->
<!--    <hr/>-->
<!---->
<!--    <input type="submit" name="Login"-->
<!--           class="button" value="Login" />-->
<!---->
<!--</form>-->
<!---->
<!---->
<!--<hr/>-->
<!---->
<!--<form method = "POST" action ="index.php">-->
<!--    <input type="submit" name="Back"-->
<!--           class="button" value="Back" />-->
<!--</form>-->
<!---->
<!--<hr/>-->
<!---->
<!--<form method = "POST" action ="Own_registration.php">-->
<!--    <label for="Register">Don't have an account?</label><br>-->
<!--    <input type="submit" name="Register"-->
<!--           class="button" value="Register" />-->
<!--</form>-->

<!DOCTYPE html>
<html>
<!-- <body>
<form method="POST" action="Password_login.php">
    <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">

    <label for="Registration">Log In To Your Account!</label><br>

    <label for="Username">User Name:</label><br>
    <input type="text" id="Username" name="Username" placeholder="Username*"> <br /><br />

    <label for="Password">PassWord:</label><br>
    <input type="text" id="Password" name="Password" placeholder="Password*"> <br /><br />

    <p>You are a Customer or a Manager?:</p>

    <input type="radio" name="query" value="Customer">Customer<br>
    <input type="radio" name="query" value="Manager">Manager<br>
    <hr/>

    <input type="submit" name="Login"
           class="button" value="Login" />

</form>


<hr/> -->

<?php
session_start(); ?>

<head>
    <meta charset="utf-8">
    <title>Log in | BlogSite Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro" rel="stylesheet">
    <link rel="stylesheet" href="stylesheet/login.css">
</head>

<body>
    <div class="container">
        <div class="logo-and-name">
            <h1>BlogSite-Manager</h1><br>
        </div>

        <div class="greeting">
            <h2>Log in to your account</h2><br>
        </div>
        <form id = "Password_login" method="POST" action="Password_login.php">
            <div class="infos">
                <div class="form">
                    <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
                    <input type="text" placeholder="Username" name="username" id="username" required><br>
                    <input type="password" placeholder="Password" name="password" id="password" required><br>
                </div>
            </div>
            <div class="user-type">
                <label for="manager">
                    <input class="rdio" type="radio" id="manager" name="query" value="Manager" required checked>
                    <span class="type-radio"></span>
                    <span>Manager</span>
                </label>
                <label for="customer">
                    <input class="rdio" type="radio" id="customer" name="query" value="Customer" required>
                    <span class="type-radio"></span>
                    <span>Customer</span>
                </label>
            </div>
        </form>
        <div class="infos">
            <button type="submit" form="Password_login" value="login" name="Login" class="btn" href="homepage.html"> Log in</button>
            <div class="signup">
                <p>Don't have an account?<a href="Own_registration.php"> Sign up</a></p>
            </div>
        </div>
    </div>

<?php

$success = True;
$db_conn = NULL;
$show_debug_alert_messages = False;
global $userName;
global $passWord;
$color = "green";

function debugAlertMessage($message)
{
    global $show_debug_alert_messages;

    if ($show_debug_alert_messages) {
        echo "<script type='text/javascript'>alert('" . $message . "');</script>";
    }
}

function executePlainSQL($cmdstr)
{
    global $db_conn, $success;

    $statement = OCIParse($db_conn, $cmdstr);


    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn);
        echo htmlentities($e['message']);
        $success = False;
    }

    $r = OCIExecute($statement, OCI_DEFAULT);
    if (!$r) {
        echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
        $e = oci_error($statement);
        echo htmlentities($e['message']);
        $success = False;
    }

    return $statement;
}

function executeBoundSQL($cmdstr, $list)
{
    global $db_conn, $success;
    $statement = OCIParse($db_conn, $cmdstr);

    if (!$statement) {
        echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
        $e = OCI_Error($db_conn);
        echo htmlentities($e['message']);
        $success = False;
    }

    foreach ($list as $tuple) {
        foreach ($tuple as $bind => $val) {
            OCIBindByName($statement, $bind, $val);
            unset ($val);
        }

        $r = OCIExecute($statement, OCI_DEFAULT);
        if (!$r) {
            echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
            $e = OCI_Error($statement);
            echo htmlentities($e['message']);
            echo "<br>";
            $success = False;
        }
    }
}

function printResult($result)
{
    echo "<br>Retrieved data from table demoTable:<br>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Name</th></tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row["NID"] . "</td><td>" . $row["NAME"] . "</td></tr>"; //or just use "echo $row[0]"
    }

    echo "</table>";
}

function connectToDB()
{
    global $db_conn;
    $db_conn = OCILogon("ora_haohe12", "a77901627",
        "dbhost.students.cs.ubc.ca:1522/stu");
    if ($db_conn) {
        debugAlertMessage("Database is Connected");
        return true;
    } else {
        debugAlertMessage("Cannot connect to Database");
        $e = OCI_Error();
        echo htmlentities($e['message']);
        return false;
    }
}

function disconnectFromDB()
{
    global $db_conn;

    debugAlertMessage("Disconnect from Database");
    OCILogoff($db_conn);
}

function handlePOSTRequest() {
    if (connectToDB()) {
        if (array_key_exists('insertQueryRequest', $_POST)) {
            handleLoginRequest();
        }
        disconnectFromDB();
    }
}

function handleLoginRequest() {
    $userName = $_POST['username'];
    $passWord = (int) $_POST['password'];

    if(!isset($_POST['query'])){
        echo "You chose no rule!";
        header("refresh:3");
    }
    if($_POST['query'] == "Customer"){
            // query for Customer
            $sql_select = executePlainSQL("SELECT Count(*) FROM PASSWORDSETTING where USERNAME = '$userName' AND PASSWORD = $passWord");
            $results = oci_fetch_row($sql_select);
            $number  = (int)$results[0];
            if($number == 0) {
                echo "Sorry, the CUSTOMER account is not found!";
                header("refresh:1");
            } else {
                $_SESSION['userName'] = $userName;
                echo "<script type='text/javascript'> document.location = 'Customer.php'; </script>";
            }
        }
    if($_POST['query'] == "Manager"){
            // query for Manager
        if ($passWord != "1234567") {
            echo "Sorry, the Manager Password is not correct!";
            header("refresh:1");
        }
        $sql_select = executePlainSQL("SELECT Count(*) FROM BLOGSITEMANAGER where MANAGERNAME = '$userName'");
        $results = oci_fetch_row($sql_select);
        $number  = (int)$results[0];
        if($number == 0) {
            echo "Sorry, the MANAGER account is not found!";
            header("refresh:1");
        } else {
            echo "Login Success!";
            echo "<script type='text/javascript'> document.location = 'manager.php'; </script>";
        }
    }
}

if (isset($_POST['Login'])) {
    handlePOSTRequest();
}

?>
</body>
</html>

