<html>
<body>

<form method = "GET" action ="managerPlus.php">
    <input type="hidden" id = "getManager" name="getManager">
    <input type="submit" name="ShowCustomerName"
           class="button" value="ShowCustomerName" />
</form>

<form method = "GET" action ="managerPlus.php">
    <input type="hidden" id = "getCustomerManager" name="getCustomerManager">
    <input type="submit" name="ShowDependent"
           class="button" value="ShowDependent" />
</form>


<hr/>

<form method = "POST" action ="managerPlus.php">
    <input type="hidden" id="showRequest" name="showRequest">
    <label for="search">Customer ID:</label><br>
    <input type="text" id="customerId" name="customerId"><br>
    <label for="ShopID">Dependent ID:</label><br>
    <input type="text" id="dependentId" name="dependentId"><br>
    <input type="submit" name="ShowInformation"
           class="button" value="ShowInformation" />
</form>

<hr/>

<form method="POST" action="managerPlus.php">
    <input type="hidden" id="updateRequest" name="updateRequest">
    <label for="name">Name:</label><br>
    <input type="text" id="CustomerID" name="CustomerID" placeholder="Customer ID*">
    <input type="text" id="Name" name="Name" placeholder="New Name*"><br /><br />
    <input type="submit" id="newName" name = "newName"
           class="button" value = "update Name"/>  <br /><br />
</form>

<hr/>
<form method="POST" action="managerPlus.php">
    <input type="hidden" id="deleteCustomerRequest" name="deleteCustomerRequest">
    <label for="pwd">Delete Customer:</label><br>
    <input type="text" id="deleteID" name="deleteID" placeholder="CustomerID">
    <input type="submit" id="deleteCustomer" name = "deleteCustomer"
           class="button" value = "Delete"/>  <br /><br />
</form>

<form method = "POST" action ="manager.php">
    <input type="submit" name="back"
           class="button" value="back" />
</form>

<?php

$success = True;
$db_conn = NULL;
$show_debug_alert_messages = False;

function debugAlertMessage($message) {
    global $show_debug_alert_messages;

    if ($show_debug_alert_messages) {
        echo "<script type='text/javascript'>alert('" . $message . "');</script>";
    }
}

function executePlainSQL($cmdstr) {
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

function executeBoundSQL($cmdstr, $list) {

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

function printResult($result) {
    echo "<br>Retrieved data from table demoTable:<br>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Name</th></tr>";

    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row["NID"] . "</td><td>" . $row["NAME"] . "</td></tr>";
    }

    echo "</table>";
}

function connectToDB() {
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

function disconnectFromDB() {
    global $db_conn;

    debugAlertMessage("Disconnect from Database");
    OCILogoff($db_conn);
}

function handleShowRequest(){
    global $db_conn;
    $CustomerId = (int) $_POST['customerId'];
    $DependentId = (int) $_POST['dependentId'];
    $result = executePlainSQL("SELECT CUSTOMER.CUSTOMERID, NAME, MANAGERNAME, DEPENDENTID,CUSTOMERTYPE,LOCATION
FROM CUSTOMER JOIN DEPENDENT_FEE
                       ON CUSTOMER.CUSTOMERID = DEPENDENT_FEE.CUSTOMERID
                       WHERE CUSTOMER.CUSTOMERID = $CustomerId AND DEPENDENTID = $DependentId");
    if (($row = oci_fetch_row($result)) != false) {
        printMemberships($result,$row);
    }
}

function handleUpdateRequest(){
    global $db_conn;
    $CustomerId = (int) $_POST['CustomerID'];
    $Name = $_POST['Name'];
    executePlainSQL("UPDATE CUSTOMER SET NAME = '$Name' WHERE CustomerID = $CustomerId");
    echo "Now Change the Name!";
//    $result = executePlainSQL("SELECT * FROM CUSTOMER");
//    if (($row = oci_fetch_row($result)) != false) {
//        printCustomer($result,$row);
//    }
    OCICommit($db_conn);
}

function handledeleteRequest(){
    global $db_conn;
    $DeleteId = $_POST['deleteID'];
    $q = "DELETE FROM DEPENDENT_FEE WHERE CUSTOMERID = $DeleteId";
    echo $q;
    executePlainSQL($q);
    echo "Now Delete the customer's membership!";
//    $result = executePlainSQL("SELECT * FROM DEPENDENT_FEE");
//    if (($row = oci_fetch_row($result)) != false) {
//    printCustomer($result,$row);
//    }
    OCICommit($db_conn);
}

function printMemberships($result,$first) {
    echo "<br>Retrieved data from table:<br>";
    echo "<table>";
    echo "<tr><th>Customer ID </th><th> Name </th><th> Manager Name </th><th> Dependent ID </th><th> Membership Type 
</th><th> Location </th></tr>";
    echo "<tr><td>" . $first[0] . "</td><td>" . $first[1] . "</td><td>" . $first[2] . "</td><td>" . $first[3] .
        "</td><td>" . $first[4] . "</td><td>" . $first[5] . "</td></tr>";
    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr>";
    }

    echo "</table>";
}

function printCustomer($result,$first) {
    echo "<br>Retrieved data from table:<br>";
    echo "<table>";
    echo "<tr><th>Customer ID </th><th> Manager Name </th><th> Real Name </th></tr>";
    echo "<tr><td>" . $first[0] . "</td><td>" . $first[1] . "</td><td>" . $first[2] . "</td></tr>";
    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr>";
    }
    echo "</table>";
}

function printDependent($result,$first) {
    echo "<br>Retrieved data from table:<br>";
    echo "<table>";
    echo "<tr><th>Customer ID </th><th> Dependent Id </th><th> Customer Type </th><th> Location </th></tr>";
    echo "<tr><td>" . $first[0] . "</td><td>" . $first[1] . "</td><td>" . $first[2] .
        "</td><td>" . $first[3] ."</td></tr>";
    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] .
            "</td><td>" . $row[3] ."</td></tr>";
    }
    echo "</table>";
}


function handleShowCustomer() {
    global $db_conn;
    echo "<br>show all Customers here<br>";

    $result = executePlainSQL("SELECT * FROM CUSTOMER");
    if (($row = oci_fetch_row($result)) != false) {
        printCustomer($result,$row);
    }
}

function handleShowDependent() {
    global $db_conn;
    echo "<br>show all Dependents here<br>";

    $result = executePlainSQL("SELECT * FROM DEPENDENT_FEE");
    if (($row = oci_fetch_row($result)) != false) {
        printDependent($result,$row);
    }
}

function handlePOSTRequest() {
    if (connectToDB()) {
        if (array_key_exists('updateRequest', $_POST)) {
            handleUpdateRequest();
        } else if (array_key_exists('showRequest', $_POST)) {
            handleShowRequest();
        }
        else if (array_key_exists('deleteCustomerRequest', $_POST)) {
            handledeleteRequest();
        }
        disconnectFromDB();
    }
}


function handleGETRequest() {
    if (connectToDB()) {
        if (array_key_exists('getManager', $_GET)) {
            handleShowCustomer();
        }else if (array_key_exists('getCustomerManager', $_GET)) {
            handleShowDependent();
        }
        disconnectFromDB();
    }
}


if (isset($_GET['ShowCustomerName'])|| isset($_GET['ShowDependent'])) {
    handleGETRequest();
} else if(isset($_POST['ShowInformation']) || isset($_POST['newName']) || isset($_POST['deleteCustomer'])){
    handlePOSTRequest();
}
?>

</body>
</html>
