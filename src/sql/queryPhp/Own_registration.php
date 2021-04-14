<!--<html>-->
<!--<body>-->
<!--<form method="POST" action="Own_registration.php">-->
<!--    <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">-->
<!--    <label for="Registration">Registration: Set Up New Account</label><br>-->
<!--    <label for="Username">User Name:</label><br>-->
<!--    <input type="text" id="Username" name="Username" placeholder="Username*"> <br /><br />-->
<!---->
<!--    <label for="Customer">Customer ID:</label><br>-->
<!--    <input type="text" id="CustomerId" name="CustomerId" placeholder="CustomerId*"> <br /><br />-->
<!---->
<!--    <label for="Password">PassWord:</label><br>-->
<!--    <input type="text" id="Password" name="Password" placeholder="Password*"> <br /><br />-->
<!---->
<!--    <label for="Confirm">Confirm Password:</label><br>-->
<!--    <input type="text" id="RepeatPassword" name="RepeatPassword" placeholder="RepeatPassword*"> <br /><br />-->
<!--    <hr/>-->
<!---->
<!--    <input type="submit" name="Register"-->
<!--           class="button" value="Register" />-->
<!--</form>-->
<!---->
<!--<form method = "POST" action ="Password_login.php">-->
<!--    <label for="Log In">Already have an account?</label><br>-->
<!--    <input type="submit" name="Log In"-->
<!--           class="button" value="Log In" />-->
<!--</form>-->

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Sign up | BlogSite Manager</title>
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro" rel="stylesheet">
    <link rel="stylesheet" href="stylesheet/registration.css">
</head>

<body>
    <div class="container">
        <div class="logo-and-name">
            <h1>Register</h1><br>
        </div>

        <div class="register-guide">
            <h2>Create you account</h2><br>
        </div>
        <form method="POST" action="Own_registration.php">
        <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">
        <div class="infos">
            <div class="form">
                <input type="text" placeholder="Username" name="Username" id="username" required><br>
                <input type="text" placeholder="CustomerId" name="CustomerId" id="customerid" required><br>
                <input type="password" placeholder="Password" name="Password" id="password" required><br>
                <input type="password" placeholder="Confirm Password" name="RepeatPassword" id="confirm-password" required><br>
            </div>
            <button type="submit" name="Register" class="btn">Register</button><br>
        </form>
            <div class="signin">
                <p>Already have an account? <a href="Password_login.php">Log in</a>.</p>
            </div>
        </div>
    </div>

<?php

$success = True;
$db_conn = NULL;
$show_debug_alert_messages = False;

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

    $db_conn = OCILogon("ora_dengzc", "a69240836",
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
            handleInsertRequest();
        }
        disconnectFromDB();
    }
}

function handleInsertRequest() {
    global $db_conn;
    $customerID = (int)$_POST['CustomerId'];
    $userName = $_POST['Username'];
    if (isset($_POST['CustomerId']) == false|| isset($_POST['Username']) == false ||
        isset($_POST['Password']) == false || isset($_POST['RepeatPassword']) == false) {
        echo "You must fill all blanks!";
        header("refresh:10");
    }
        $sql_select = executePlainSQL("SELECT Count(*) FROM CUSTOMER WHERE CUSTOMERID = $customerID");
        $results = oci_fetch_row($sql_select);
        $numbers = (int) $results[0];
        if($numbers == 0) {
            echo "Sorry, you are not a formal customer of our product";
            header("refresh:10");
        } if ($_POST['Password'] != $_POST['RepeatPassword']) {
            echo "The two times password are not matched!";
            header("refresh:10");
        } else {
            //Select Variables
            if ($userName != "" && $customerID != "") {
                $sql_select = executePlainSQL("SELECT Count(*) FROM BLOGACCOUNT WHERE USERNAME = '$userName'");
                $results_1 = oci_fetch_row($sql_select);
                $numbers = (int) $results_1[0];
                if($numbers != 0) {
                    echo "The user name has already been registered!";
                    header("refresh:10");
                } else {
                    $tuple = array (
                        ":bind1" => $userName,
                        ":bind2" => $customerID,
                    );

                    $tupleOne = array (
                        $tuple
                    );
                    executeBoundSQL("insert INTO BLOGACCOUNT values (:bind1, :bind2)", $tupleOne);

                    //Insert the new added account register information!
                    $passWordTime = rand (1000000000, 9999999999);

                    $tuple = array (
                        ":bind1" => $_POST['Username'],
                        ":bind2" => (int) $_POST['Password'],
                        ":bind3" => $passWordTime
                    );

                    $tupleTwo = array (
                        $tuple
                    );
                    executeBoundSQL("insert INTO PASSWORDSETTING values (:bind1, :bind2, :bind3)", $tupleTwo);
                    OCICommit($db_conn);
//                    echo "<script type='text/javascript'> document.location = 'Password_login.php'; </script>";
                }
            }
        }
    disconnectFromDB();
}

if (isset($_POST['Register'])) {
    handlePOSTRequest();
}


?>
</body>
</html>




