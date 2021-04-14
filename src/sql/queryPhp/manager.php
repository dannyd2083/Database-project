<html>
<body>

<form method = "GET" action ="manager.php">
    <input type="hidden" id = "getManager" name="getManager">
    <input type="submit" name="ShowManager"
           class="button" value="ShowManager" />
</form>

<form method = "GET" action ="manager.php">
    <input type="hidden" id = "getCustomerManager" name="getCustomerManager">
    <input type="submit" name="ShowAssignedManager"
           class="button" value="ShowAssignedManager" />
</form>

<form method = "GET" action ="manager.php">
    <input type="hidden" id = "analyzeCustomerByType" name="analyzeCustomerByType">
    <input type="submit" name="ByType"
           class="button" value="ByType" />
</form>

<form method = "GET" action ="manager.php">
    <input type="hidden" id = "analyzeCustomerByLocation" name="analyzeCustomerByLocation">
    <input type="submit" name="ByLocation"
           class="button" value="ByLocation" />
</form>

<form method = "GET" action ="manager.php">
    <input type="hidden" id = "getAllPrice" name="getAllPrice">
    <input type="submit" name="ShowMemberShip"
           class="button" value="ShowMemberShip" />
</form>

<form method = "GET" action ="manager.php">
    <input type="hidden" id = "getAllAccount" name="getAllAccount">
    <input type="submit" name="ShowAccount"
           class="button" value="ShowAccount" />
</form>



<hr/>

<form method="POST" action="manager.php">
    <input type="hidden" id="insertQueryRequest" name="insertQueryRequest">

    <label for="haven">Having a manager for this customer?:</label><br>
    <input type="text" id="manager" name="ManagerName" placeholder="manager name*"> <br /><br />
    <input type="text" id="customerId" name="customerId" placeholder="customer ID*"> <br /><br />

    <hr/>
    <label for="member">Add a new membership for this customer:</label><br>
    <input type="text" id="manager" name="RealName" placeholder="real name*"> <br /><br />
    <input type="text" id="dependentId" name="dependentId" placeholder="dependent Id*"> <br /><br />
    <p>Choose customer's location?:</p>

    <input type="radio" name="query" value="China">China<br>
    <input type="radio" name="query" value="USA">USA<br>
    <input type="radio" name="query" value="Canada">Canada<br>

    <p>Choose customer's new membership's type?:</p>

    <input type="radio" name="query1" value="Premier">Premier<br>
    <input type="radio" name="query1" value="Visitor">Visitor<br>
    <input type="radio" name="query1" value="Normal">Normal<br>

    <hr/>
<input type="submit" name="Add"
       class="button" value="Add" />
</form>

<hr/>

<form method = "POST" action ="managerPlus.php">
    <input type="submit" name="Change Customer Info"
           class="button" value="Change Customer Info" />
</form>

<hr/>
<form method = "POST" action ="manager.php">
    <input type="submit" name="Log Out"
           class="button" value="Log Out" />
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
    $db_conn = OCILogon("ora_dengzc", "a69240836", "dbhost.students.cs.ubc.ca:1522/stu");

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

function handleAddRequest(){
    global $db_conn;

    $ManagerName = $_POST['ManagerName'];
    $CustomerId = (int)$_POST['customerId'];
    $DependentId = (int)$_POST['dependentId'];
    $RealName = $_POST['RealName'];
    $MemberLocation = $_POST['query'];
    $MemberType = $_POST['query1'];

    if(!isset($_POST['query'])){
        echo "You chose no membership location!";
        header("refresh:10");
    }

    if(!isset($_POST['query1'])){
        echo "You chose no membership type!";
        header("refresh:10");
    }

    $sql_select = executePlainSQL("SELECT Count(*) FROM BLOGSITEMANAGER where MANAGERNAME = '$ManagerName'");
    $results = oci_fetch_row($sql_select);
    $number = (int) $results[0];
    if($number == 0) {
        echo "Sorry, we don't have such a manager, please click Show Manager to view!";
        header("refresh:10");
    }

    $sql_select = executePlainSQL("SELECT Count(*) FROM DEPENDENT_FEE where CUSTOMERID = $CustomerId
                                      AND DEPENDENTID = $DependentId");
    $results = oci_fetch_row($sql_select);
    $number = (int) $results[0];
    if($number != 0) {
        echo "Sorry, this member have already been added!";
        header("refresh:10");
    }

    $sql_select = executePlainSQL("SELECT Count(*) FROM CUSTOMER where CUSTOMERID = $CustomerId");
    $results = oci_fetch_row($sql_select);
    $number = (int) $results[0];
    if($number != 0) {
        $sql_select = executePlainSQL("SELECT Count(*) FROM CUSTOMER where CUSTOMERID = $CustomerId 
                                AND NAME = '$RealName'");
        $resultOne = oci_fetch_row($sql_select);
        $number = (int) $resultOne[0];
        if($number == 0) {
            echo "Sorry, the name must be wrong!";
            header("refresh:10");
        } else {
            $OrderTuple = array(
            "bind1" => $CustomerId,
            "bind2" => $DependentId,
            "bind3" => $MemberType,
            "bind4" => $MemberLocation,
        );
        $AllOrderOne = array(
            $OrderTuple
        );
        executeBoundSQL("insert into DEPENDENT_FEE values (:bind1, :bind2, :bind3, :bind4)",$AllOrderOne);
         echo "The customer now have more than one dependent account!";
        }
    } else {
        $OrderTuple = array(
            "bind1" => $CustomerId,
            "bind2" => $ManagerName,
            "bind3" => $RealName,
        );
        $AllOrder = array(
            $OrderTuple
        );
        executeBoundSQL("insert into CUSTOMER values (:bind1, :bind2, :bind3)", $AllOrder);

        $OrderTuple = array(
            "bind1" => $CustomerId,
            "bind2" => $DependentId,
            "bind3" => $MemberType,
            "bind4" => $MemberLocation,
        );
        $AllOrderOne = array(
            $OrderTuple
        );
        var_dump($AllOrderOne);
        executeBoundSQL("insert into DEPENDENT_FEE values (:bind1, :bind2, :bind3, :bind4)", $AllOrderOne);
        echo "The new customer now have one dependent account!";
    }
    OCICommit($db_conn);
}

function printManager($result,$first) {
    echo "<br>Retrieved data from table:<br>";
    echo "<table>";
    echo "<tr><th>Manager Name</th></tr>";
    echo "<tr><td>" . $first[0] . "</td></tr>";
    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr>";
    }
    echo "</table>";
}

function printAssignedManager($result,$first) {
    echo "<br>Retrieved data from table:<br>";
    echo "<table>";
    echo "<tr><th>Manager Name</th><th>Customer ID</th><th>User Name</th></tr>";
    echo "<tr><td>" . $first[0] . "</td><td>" . $first[1] . "</td><td>" . $first[2] ."</td></tr>";
    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr>";
    }

    echo "</table>";
}

function printCustomerType($result,$first) {
    echo "<br>Retrieved data from table:<br>";
    echo "<table>";
    echo "<tr><th>Number of Customers </th><th>Number of Memberships </th><th> Customer Type </th></tr>";
    echo "<tr><td>" . $first[0] . "</td><td>" . $first[1] . "</td><td>" . $first[2] . "</td></tr>";
    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr>";
    }

    echo "</table>";
}

function printCustomerLocation($result,$first) {
    echo "<br>Retrieved data from table:<br>";
    echo "<table>";
    echo "<tr><th>Number of Customers </th><th>Number of Memberships </th><th> Customer Location </th></tr>";
    echo "<tr><td>" . $first[0] . "</td><td>" . $first[1] . "</td><td>" . $first[2] . "</td></tr>";
    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr>";
    }

    echo "</table>";
}

function printMemberships($result,$first) {
    echo "<br>Retrieved data from table:<br>";
    echo "<table>";
    echo "<tr><th>Customer Type </th><th>Location </th><th> Membership Fees </th></tr>";
    echo "<tr><td>" . $first[0] . "</td><td>" . $first[1] . "</td><td>" . $first[2] . "</td></tr>";
    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr>";
    }

    echo "</table>";
}

function printAccount($result,$first) {
    echo "<br>Retrieved data from table:<br>";
    echo "<table>";
    echo "<tr><th>Customer Id </th><th>Customer Name</th><th>Manager Name </th><th> Dependent Id 
</th><th> Customer Type </th><th> Customer Location </th></tr>";
    echo "<tr><td>" . $first[0] . "</td><td>" . $first[1] . "</td><td>" . $first[2] ."</td><td>" . $first[3] .
        "</td><td>" . $first[4] ."</td><td>" . $first[5] ."</td><td>" . $first[6] ."</td><td>" . $first[7] ."</td></tr>";
    while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
        echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td><td>" . $row[3] .
            "</td><td>" . $row[4] . "</td><td>" . $row[5] ."</td><td>" . $row[6] ."</td><td>" . $row[7] ."</td></tr>";
    }

    echo "</table>";
}

function handleShowManager() {
    global $db_conn;
    echo "<br>show all Managers here<br>";

    $result = executePlainSQL("SELECT MANAGERNAME FROM BLOGSITEMANAGER");
    if (($row = oci_fetch_row($result)) != false) {
        printManager($result,$row);
    }
}

function handleAssignedManager(){
    global $db_conn;
    echo "<br>";
    echo "Show assigned managers here<br>";

    $result = executePlainSQL("
SELECT MANAGERNAME, BLOGACCOUNT.CUSTOMERID, USERNAME
FROM CUSTOMER JOIN BLOGACCOUNT ON CUSTOMER.CUSTOMERID = BLOGACCOUNT.CUSTOMERID");
    if (($row = oci_fetch_row($result)) != false) {
        printAssignedManager($result,$row);
    }
}

function handleAnalyzeCustomerByType(){
    global $db_conn;
    echo "<br>";
    echo "Show grouped information on types here<br>";

    $result = executePlainSQL("
SELECT COUNT(CUSTOMERID),COUNT(DEPENDENTID) ,CUSTOMERTYPE
FROM DEPENDENT_FEE
GROUP BY CUSTOMERTYPE
HAVING (COUNT(CUSTOMERID) >= 1 AND COUNT(DEPENDENTID) >= 1)");
    if (($row = oci_fetch_row($result)) != false) {
        printCustomerType($result,$row);
    }
}


function handleAnalyzeCustomerByLocation(){
    global $db_conn;
    echo "<br>";
    echo "Show grouped information on locations here<br>";

    $result = executePlainSQL("
SELECT COUNT(CUSTOMERID),COUNT(DEPENDENTID) ,LOCATION
FROM DEPENDENT_FEE
GROUP BY LOCATION");
    if (($row = oci_fetch_row($result)) != false) {
        printCustomerLocation($result,$row);
    }
}

function handleMoney(){
    global $db_conn;
    echo "<br>";
    echo "Show assigned membership sets here<br>";

    $result = executePlainSQL("SELECT CUSTOMERTYPE, LOCATION, SERVICEFEE FROM CUSTOMERSERVICE");
    if (($row = oci_fetch_row($result)) != false) {
        printMemberships($result,$row);
    }
}

function handleAccount(){
    global $db_conn;
    echo "<br>";
    echo "Show assigned account values sets here<br>";

    $result = executePlainSQL("SELECT CUSTOMER.CUSTOMERID, NAME, MANAGERNAME, DEPENDENTID,CUSTOMERTYPE,LOCATION
FROM CUSTOMER JOIN DEPENDENT_FEE
                       ON CUSTOMER.CUSTOMERID = DEPENDENT_FEE.CUSTOMERID");
    if (($row = oci_fetch_row($result)) != false) {
        printAccount($result,$row);
    }
}




function handlePOSTRequest() {
    if (connectToDB()) {
        if (array_key_exists('insertQueryRequest', $_POST)) {
            handleAddRequest();
        }
        disconnectFromDB();
    }
}

function handleGETRequest() {
    if (connectToDB()) {
        if (array_key_exists('getManager', $_GET)) {
            echo "line 306";
            handleShowManager();
        }else if (array_key_exists('getCustomerManager', $_GET)) {
            handleAssignedManager();
        } else if (array_key_exists('analyzeCustomerByType', $_GET)) {
            handleAnalyzeCustomerByType();
        } else if (array_key_exists('analyzeCustomerByLocation', $_GET)) {
            handleAnalyzeCustomerByLocation();
        } else if (array_key_exists('getAllPrice', $_GET)) {
            handleMoney();
        } else if (array_key_exists('getAllAccount', $_GET)) {
            handleAccount();
        }
        disconnectFromDB();
    }
}
print_r($_POST);
print_r($_GET);

if (isset($_GET['ShowManager'])|| isset($_GET['ShowAssignedManager']) ||
        isset($_GET['ByType']) ||
            isset($_GET['ByLocation']) ||
    isset($_GET['ShowMemberShip']) || isset($_GET['ShowAccount'])) {
    handleGETRequest();
} else if(isset($_POST['Add'])){
    handlePOSTRequest();
}
?>
</body>
</html>
