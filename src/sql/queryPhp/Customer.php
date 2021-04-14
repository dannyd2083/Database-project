<!DOCTYPE html>
<html>

<?php
session_start(); ?>

<head>
    <meta charset="UTF-8">
    <title> Homepage - Customer | BlogSite Manager</title>
    <link rel="stylesheet" href="stylesheet/homepage-view.css">
</head>

<body>
    <div class="container">
        <div class="top-bar">
            <ul class="nav-bar" role="navigation">
                <li class="logo-name"><a href="customer.html">BlogSite-Manager</a></li>
            </ul>
        </div>
        <div class="guide-bar">
            <ul class="gud-bar" role="navigation">
                <li class="tools">
                    <p>Tools</p>
                </li>
                <li class="results">
                    <p>Results</p>
                </li>
            </ul>
        </div>
        <div class="wrapper">
            <div class="left">
                <div class="left-middle-up">
                    <p class="general-heading">Basic Tools</p>
                    <div class="view-list">

                        <!-- reset the page tables -->
                        <form id="PageRestForm" method="POST" action="Customer.php">
                            <input type="hidden" id="resetTablesRequest" name="resetTablesRequest">
                        </form>

                        <!-- get the basic data -->
                        <form id="BasicDataForm" method="GET" action="Customer.php">
                            <input type="hidden" id="basicDataRequest" name="basicDataRequest">
                        </form>

                        <!-- get the basic data -->
                        <form id="showAllAccountForm" method="GET" action="Customer.php">
                            <input type="hidden" id="displayBlogAccountRequest" name="displayBlogAccountRequest">
                        </form>

                        <button type="submit" form="PageRestForm" name="rest" class="view-btn"> Reset </button>
                        <button type="submit" form="BasicDataForm" name="basic" class="view-btn"> Basic Data </button>
                        <button type="submit" form="showAllAccountForm" name="showAc" class="view-btn"> Show all
                            Accounts</button>
                    </div>
                    <div class="view-list">
                        <input type="text" form="PageRestForm" placeholder="Username" name="username" id="username">
                        <input type="text" form="BasicDataForm" placeholder="Username" name="username" id="username">
                        <input type="text" form="showAllAccountForm" placeholder="Username" name="username" id="username">
                    </div>
                </div>


                <div class="left-middle-bottom">
                    <label class="general-heading">General Search</label>

                    <div class="search-related">
                        <form method="GET" action="Customer.php">
                            <input type="hidden" id="showAllPostsByAccountRequest" name="showAllPostsByAccountRequest">
                            <input type="text" placeholder="Username" name="username" class="search-box">

                            <button type="submit" value="ShowAllPos" name="showPos" class="search-btn">Search</button>
                        </form>
                    </div>

                    <label class="general-heading"> Advanced Search:</label>

                    <label class="general-sub-heading">Search by Post ID:</label>
                    <div class="search-related">
                        <form method="GET" action="Customer.php">
                            <input type="hidden" id="findPostIDRequest" name="findPostIDRequest">
                             <input type="text" placeholder="Post ID" name="postID" class="search-box">
                        <button type="submit" value="FindPostID" name="findPostID"class="search-btn">Search</button>
                        </form>
                    </div>

<!--                    <label class="general-sub-heading">Search by Post Title:</label>-->
<!--                    <div class="search-related">-->
<!--                        <form method="GET" action="Customer.php">-->
<!--                            <input type="hidden" id="showAllPostsIDByTitleRequest" name="showAllPostsIDByTitleRequest">-->
<!--                            <input type="text" placeholder="Post Title" name="postTitle" class="search-box">-->
<!--                            <button type="submit" value="ShowAllPosByT" name="showPosByT" class="search-btn">Search</button>-->
<!--                        </form>-->
<!--                    </div>-->

                    <label class="general-sub-heading">Find all posts posted by all blog accounts:</label>
                    <div class="search-related">
                        <form method="GET" action="Customer.php">
                            <input type="hidden" id="divisionRequest" name="divisionRequest">
                            <button type="submit" name="division" class="search-btn">Search</button>
                        </form>
                    </div>

                    <label class="general-sub-heading">Min Post ID from users who published >1 posts:</label>
                    <div class="search-related">
                        <form method="GET" action="Customer.php">
                            <input type="hidden" id="findMinPostID" name="findMinPostID">
                            <button type="submit" value="findMinP" name="findMinP"class="search-btn">Search</button>
                        </form>
                    </div>

                </div>

                <div class="left-bottom">
                    <label class="general-heading">
                        Edit Account Info:
                    </label>

<!--                    <label class="general-sub-heading"> Enter Account Username </label>-->
<!--                    <div class="edit">-->
<!--                        <input type="text" placeholder="Username" name="acc-name" id="usrid-box">-->
<!--                        <button type="submit" class="search-btn">Confirm</button>-->
<!--                    </div>-->

                    <!-- <label class="general-sub-heading"> Edit account name </label>
                    <div class="edit">
                        <input type="text" placeholder="new name" name="username">
                        <button type="submit" class="search-btn">Submit</button>
                    </div> -->

                    <!-- TODO Needed to be implement. -->
                    <!-- <label class="general-sub-heading"> Edit account's Content </label>
                    <div class="edit">
                        <button type="submit" class="search-btn" style="width: 15em; margin-left: 4px;" href="account.html">
                            Confirm</button>
                    </div> -->

                    <label class="general-heading">Delete This Account</label>
                    <div class="delete">
                            <form method="GET" action="Customer.php">
                            <input type="hidden" id="deleteRequest" name="deleteRequest" >
                            <input type="text"  placeholder="postIDtoDelete" name="postIDtoDelete" id="postIDtoDelete">
                            <button type="submit" value="deletePost" name="deletePost" class="search-btn">Delete</button>
                        </form>
                    </div>
                </div>
            </div>


            <div class="right">
                <div class="right-mb">
                    <div class="results">
                        <p>
                            Results.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php
    //this tells the system that it's no longer just parsing html; it's now parsing PHP
    //global $userName;
//    var_dump($_POST['username']);
    $userName = $_POST['username']; // this information is from the login page
    $_SESSION['username'] = $userName;
//    var_dump($userName);
//    var_dump( $_SESSION['username']);
    $success = True; //keep track of errors so it redirects the page only if there are no errors
    $db_conn = NULL; // edit the login credentials in connectToDB()
    $show_debug_alert_messages = False; // set to True if you want alerts to show you which methods are being triggered (see how it is used in debugAlertMessage())
    function debugAlertMessage($message)
    {
        global $show_debug_alert_messages;
        if ($show_debug_alert_messages) {
            echo "<script type='text/javascript'>alert('" . $message . "');</script>";
        }
    }
    function connectToDB()
    {
        global $db_conn;
        // Your username is ora_(CWL_ID) and the password is a(student number). For example,
        // ora_platypus is the username and a12345678 is the password.
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
    function disconnectFromDB()
    {
        global $db_conn;
        debugAlertMessage("Disconnect from Database");
        OCILogoff($db_conn);
    }

    function executePlainSQL($cmdstr) { //takes a plain (no bound variables) SQL command and executes it
        //echo "<br>running ".$cmdstr."<br>";
        global $db_conn, $success;

        $statement = OCIParse($db_conn, $cmdstr);
        //There are a set of comments at the end of the file that describe some of the OCI specific functions and how they work

        if (!$statement) {
            echo "<br>Cannot parse the following command: " . $cmdstr . "<br>";
            $e = OCI_Error($db_conn); // For OCIParse errors pass the connection handle
            echo htmlentities($e['message']);
            $success = False;
        }

        $r = OCIExecute($statement, OCI_DEFAULT);
        if (!$r) {
            echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
            $e = oci_error($statement); // For OCIExecute errors pass the statementhandle
            echo htmlentities($e['message']);
            $success = False;
        }

        return $statement;
    }

    function executeBoundSQL($cmdstr, $list)
    {
        /* Sometimes the same statement will be executed several times with different values for the variables involved in the query.
		In this case you don't need to create the statement several times. Bound variables cause a statement to only be
		parsed once and you can reuse the statement. This is also very useful in protecting against SQL injection.
		See the sample code below for how this function is used */

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
                //echo $val;
                //echo "<br>".$bind."<br>";
                OCIBindByName($statement, $bind, $val);
                unset($val); //make sure you do not remove this. Otherwise $val will remain in an array object wrapper which will not be recognized by Oracle as a proper datatype
            }

            $r = OCIExecute($statement, OCI_DEFAULT);
            if (!$r) {
                echo "<br>Cannot execute the following command: " . $cmdstr . "<br>";
                $e = OCI_Error($statement); // For OCIExecute errors, pass the statementhandle
                echo htmlentities($e['message']);
                echo "<br>";
                $success = False;
            }
        }
    }

    function handleDisplayPostsByBlogAccount()
    {
        global $db_conn;
        $uName = $_GET['username'];
        var_dump($uName);
        $result = executePlainSQL("select POSTID FROM PUBLISH WHERE USERNAME = '$uName'");
        printAllPostIdByBlogAccount($result);
    }

//    function handleDisplayPostsIdByTitle()
//    {
//        $tName = $_GET['postTitle'];
//        $result = executePlainSQL("select POSTID FROM POSTS WHERE postTitle LIKE '%$tName%'");
//        printAllPostIdByBlogAccount($result);
//    }

    function printAllPostIdByBlogAccount($result)
    { //prints results from a select statement
        echo "<br>Retrieved data from table Publish:<br>";
        echo "<table>";
        echo "<tr><th>Post ID</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row["0"] . "</td></tr>"; //or just use "echo $row[0]"
        }
        echo "</table>";
    }

    function handleDisplayBlogAccountByCustomer()
    {
        global $db_conn;
//        global $userName;
        $userName = $_GET['username'];
        echo "line 288";
        var_dump($userName);
//        $q = "select USERNAME from OWN where CUSTOMERID in (select CUSTOMERID FROM OWN WHERE USERNAME = '$userName')";
        $q = "SELECT userName FROM BlogAccount WHERE customerID = (SELECT customerID FROM BlogAccount WHERE userName = '$userName')";
        $result = executePlainSQL($q);
        echo $q;
        printAllBlogAccountByCustomer($result);
    }

    function printAllBlogAccountByCustomer($result)
    { //prints results from a select statement
        echo "<br>Retrieved data from table Own:<br>";
        echo "<table>";
        echo "<tr><th>User Name</th></tr>";
        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td></tr>"; //or just use "echo $row[0]"
            print_r($row);
        }
        echo "</table>";
    }

    function handleCountBlogAccount()
    {
        global $db_conn;
        $userName = $_GET['username'];
        $q = "select COUNT (USERNAME) from BlogAccount where customerID = (select CUSTOMERID FROM BlogAccount WHERE userName = '$userName')";
        echo $q;
        $result = executePlainSQL("select COUNT (USERNAME) from BlogAccount where customerID = (select CUSTOMERID FROM BlogAccount WHERE userName = '$userName')");
        if (($row = oci_fetch_row($result)) != false) {
            echo "<br> The number of Blog Accounts you have: " . $row[0] . "<br>";
        }
    }

    function handleDisplayCustomerId()
    {
        global $db_conn;
        $userName = $_GET['username'];
        $result = executePlainSQL("select CUSTOMERID FROM BlogAccount WHERE USERNAME = '$userName'");
        if (($row = oci_fetch_row($result)) != false) {
            echo "<br> The customer ID is: " . $row[0] . "<br>";
        }
    }

    //    function handleResetRequest () {
    //
    //
    //    }

    function handleDeletePost() {
        global $db_conn;
        global $userName;
        echo "delete post";
        $postID = $_GET['postIDtoDelete'];
        $q = "DELETE FROM POSTS WHERE POSTID = $postID";
        echo $q;
        executePlainSQL($q);
        OCICommit($db_conn);
    }

    function handleDivision(){
        global $db_conn;
        $q = "SELECT DISTINCT P.POSTID FROM PUBLISH P WHERE NOT EXISTS (SELECT B.USERNAME FROM BLOGACCOUNT B MINUS (SELECT P1.USERNAME FROM PUBLISH P1 WHERE (P1.POSTID = P.POSTID)))";
        echo $q;
        $result = executePlainSQL($q);
//        $row = OCI_Fetch_Array($result);
//        if($row[0] == 0) {
//            $err_message = 'Such of this post postID  does not exist. Please enter another one.';
//            echo "<script type='text/javascript'> swal('Error!', '$err_message', 'error'); </script>";
//            echo "<script>
//                if ( window.history.replaceState ) {
//                    window.history.replaceState( null, null, window.location.href );
//                }
//                </script>";
//        }
        printPostID($result);
    }

    function handleFindMinSharePost(){
        global $db_conn;
        $q = "SELECT Min(P.POSTID) FROM PUBLISH P WHERE P.USERNAME IN (SELECT P1.USERNAME FROM PUBLISH P1 GROUP BY P1.USERNAME HAVING COUNT(*) > 1)";
        $result = executePlainSQL($q);
//        echo $q;
//        $row = OCI_Fetch_Array($result);
//        if($row[0] == 0) {
//            $err_message = 'This tagID does not exist. Please enter another one.';
//            echo "<script type='text/javascript'> swal('Error!', '$err_message', 'error'); </script>";
//            echo "<script>
//                if ( window.history.replaceState ) {
//                    window.history.replaceState( null, null, window.location.href );
//                }
//                </script>";
//        }
        printPostID($result);
    }

    function printUserName($result) {
        echo "<br>Retrieved List of All User Name:<br>";
        echo "<table>";
        echo "<tr><th>User Name</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td></tr>";
        }
        echo "</table>";
    }

    function printPostID($result) {
        echo "<br>Retrieved List of All Post ID:<br>";
        echo "<table>";
        echo "<tr><th>Post ID</th></tr>";

        while ($row = OCI_Fetch_Array($result, OCI_BOTH)) {
            echo "<tr><td>" . $row[0] . "</td></tr>";
        }
        echo "</table>";
    }

    function handleFindPostID(){
        global $db_conn;
        $postID = $_GET['postID'];
        $result = executePlainSQL("SELECT USERNAME FROM PUBLISH WHERE POSTID = $postID");
//        $row = OCI_Fetch_Array($result);
//        if($row[0] == 0) {
//            $err_message = 'This postID does not exist. Please enter another one.';
//            echo "<script type='text/javascript'> swal('Error!', '$err_message', 'error'); </script>";
//            echo "<script>
//                if ( window.history.replaceState ) {
//                    window.history.replaceState( null, null, window.location.href );
//                }
//                </script>";
//        }
        printUserName($result);
    }


    // TODO ALL GET
    // HANDLE ALL GET ROUTES
    // A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
    function handleGETRequest()
    {
        if (connectToDB()) {
            if (array_key_exists('displayBlogAccountRequest', $_GET)) {
                handleDisplayBlogAccountByCustomer();
            }
            if (array_key_exists('basicDataRequest', $_GET)) {
                handleCountBlogAccount();
                handleDisplayCustomerId();
            }
            if (array_key_exists('showAllPostsByAccountRequest', $_GET)) {
                handleDisplayPostsByBlogAccount();
            }
            if (array_key_exists('divisionRequest', $_GET)) {
                handleDivision();
            }
            if (array_key_exists('findPostIDRequest', $_GET)) {
                handleFindPostID();
            }
            if (array_key_exists('findMinPostID', $_GET)) {
                handleFindMinSharePost();
            }
            if (array_key_exists('deleteRequest', $_GET)) {
                handleDeletePost();
            }
            disconnectFromDB();
        }
    }
//


    // TODO ALL POST ROUTES

    //     HANDLE ALL POST ROUTES
    //     A better coding practice is to have one method that reroutes your requests accordingly. It will make it easier to add/remove functionality.
    function handlePOSTRequest()
    {
        if (connectToDB()) {
                        if (array_key_exists('resetTablesRequest', $_POST)) {
//                            handleResetRequest();
                            echo "to do handle request";
                        }
//                        else if (array_key_exists('insertQueryRequest', $_POST)) {
            //                handleInsertRequest();
            //            }
            disconnectFromDB();
        }
    }
//    print_r($_POST);
//    print_r($_GET);
//    var_dump($_GET['basic']);
    if (isset($_POST['reset'])) {
        echo "line 192";
        handlePOSTRequest();
    } else if (isset($_GET['basic']) || isset($_GET['showAc']) || isset($_GET['showPos']) || isset($_GET['division']) || isset($_GET['findPostID']) || isset($_GET['findMinP']) || isset($_GET['deletePost'])) {
        handleGETRequest();
    }
    ?>
</body>

</html>