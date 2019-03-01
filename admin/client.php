<?php
 session_start();
 // isset($_SESSION["userId"]) or header("Location: login.php") and exit(0);
 include "../mysql.php";
 $conn = FALSE;
 $userId = $_SESSION["userId"];
 $userName = $_SESSION["userName"];
 $email = trim($_POST["txt_email"]);
 $errmsg = "";
 $choice = "";
 if (isset($_POST["submit_client_detail"]) || isset($_GET["hidden_email"])) {
 $conn = dbOpen();
 if (!$conn) {
 $errmsg .= mysql_error()."<br />";
 }
 else {
 // when email is submitted by checkbox
 if (isset($_GET["hidden_email"])) {
 $email = trim($_GET["hidden_email"]);
if (isset($_GET["chk_account_status"])) {
 $status = "E";
 }
 else {
 $status = "D";
 }
 $res = updateStatusByEmail($conn, $status, $email);
 if (!$res) {
 $errmsg .= mysql_error()."<br />";
 }
 }
 // when email is submitted by textbox
 $rows = retrieveUserByEmail($conn, $email);
 if (is_null($rows)) {
 $errmsg .= mysql_error()."<br />";
 }
 else if (count($rows)!=1) {
 $errmsg .= "User not found!<br />";
 }
 else {
 $row = $rows[0];
 }
 }
 dbClose($conn);
 $conn = FALSE;
 }
?>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Client Profile - Vulnerable Voting System</title>
    <script type="text/javascript">
        function validateEmail(email) {
            var reg = new RegExp(/\S+@\S+.+\S{2,}$/);
            return reg.test(email);
        }

        function validateSearch() {
            var txtEmail = document.getElementById("txt_email").value;
            var errmsg = "";

            if (txtEmail == "") {
                errmsg += "Email is missing!<br />";
            }
            else if (txtEmail.length > 100) {
                errmsg += "Email too long!<br />";
            }
            else if (!validateEmail(txtEmail)) {
                errmsg += "Email invalid!<br />";
            }

            document.getElementById("err_search").innerHTML = errmsg;
            return (errmsg == "");
        }
    </script>
</head>

<body>
    <form id='form_logout' name='form_logout' method='POST' action='logout.php'>
        <table border='0' width='100%'>
            <tr>
                <td colspan='2'>
                    <h2>Vulnerable Voting System</h2>
                    <h3>Management Portal</h3>
                </td>
                <!-- Account Info -->
                <td align='RIGHT' valign='BOTTOM'>
                    Admin: TERRYLEUNG<br />
                </td>
            </tr>
            <!-- navigation -->
            <tr bgcolor='#ECEDEF' align='CENTER'>
                <td width='25%'>Client Profile</td>
                <td width='25%'><a href='publish.php'>Vote Publish</a></td>
                <td align='RIGHT'><input type='SUBMIT' id='submit_logout' name='submit_logout' value='Logout' /></td>
            </tr>
        </table>
    </form>

    <h3>Client Profile:</h3>
    <font color='#FF0000'>
        <span id='err_search'></span>
    </font>
    <form id='form_client_detail' name='form_client_detail' method='POST' action='client.php'>
        Email: <input type='TEXT' id='txt_email' name='txt_email' value='' />
        <input type='SUBMIT' id='submit_client_detail' name='submit_client_detail' value='Submit' onclick="javascript: return validateSearch();"/>
    </form>

	<?php
 if (isset($_POST["submit_client_detail"]) || isset($_GET["hidden_email"])) {
?>
 <form id='form_account_status' name='form_account_status' method='GET' action='client.php'>
 Name: <?=$row["name"];?><br />
 HKID: <?=substr($row["hkid"], 0, -1);?>(<?=substr($row["hkid"], -1);?>)<br />
 E-mail: <?=$row["email"];?><input type='HIDDEN' id='hidden_email'
name='hidden_email' value='<?=$row["email"];?>'><br /> Phone:
<?=$row["phone"];?><br />
 Address: <?=$row["address"];?><br />
 Account Enable: <input type='CHECKBOX' id='chk_account_status'
name='chk_account_status'<?=($row["status"]=='E' ? " checked='CHECKED'" : "");?>
onchange='javascript: document.getElementById("form_account_status").submit();' />
 </form>

    <h3>Client Voting Record:</h3>
    <table border='0' cellpadding='5'>
        <tr bgcolor='#8AC007'>
            <th>Hot Topic</th>
            <th>My Choice</th>
        </tr>
        <tr bgcolor='#C1E0EB'>
            <td>Which course is your favourite one?</td>
            <td>COMP4632</td>
        </tr>
        <tr bgcolor='#C1E0EB'>
            <td>What overall rating would you give COMP4632?</td>
            <td>A+</td>
        </tr>
        <tr bgcolor='#C1E0EB'>
            <td>How easy is COMP4632 exam?</td>
            <td>Not my cup of tea</td>
        </tr>
    </table>
<?php
	}
?>
</body>
</html>