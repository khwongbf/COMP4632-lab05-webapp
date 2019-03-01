﻿<?php
	session_start();

	isset($_SESSION["userId"]) or header("Location: login.php") and exit(0);

	include "../mysql.php";

	$conn = FALSE;
	$userId = $_SESSION["userId"];
	$userName = $_SESSION["userName"];
	$topic = trim($_POST["txt_topic"]);
	$optionA = trim($_POST["txt_option_a"]);
	$optionB = trim($_POST["txt_option_b"]);
	$optionC = trim($_POST["txt_option_c"]);
	$optionD = trim($_POST["txt_option_d"]);

	$errmsg = "";
	if (isset($_POST["submit_publish"])) {
		$conn = dbOpen();
		if (!$conn) {
			$errmsg .= mysql_error()."<br />";
		}
		else {
			$rows = retrieveVoteByTopic($conn, $poll);
			if (is_null($rows)) {
				$errmsg .= mysql_error()."<br />";
			}
			else if (count($rows)>0) {
				$errmsg .= "Topic already exists!<br />";
			}
			else {
				$res = createVote($conn, $topic, $optionA, $optionB, $optionC, $optionD);
				if (!$res) {
					$errmsg .= mysql_error()."<br />";
				}
				else {
					$topic = "";
					$optionA = "";
					$optionB = "";
					$optionC = "";
					$optionD = "";
				}
			}
		}
		dbClose($conn);
		$conn = FALSE;
	}
?>

<html>
<head>
    <meta charset='UTF-8'>
    <title>Vote Publish - Vulnerable Voting System</title>
    <script type="text/javascript">
        function validatePublish() {
            var txtTopic = document.getElementById("txt_topic").value;
            var txtOptionA = document.getElementById("txt_option_a").value;
            var txtOptionB = document.getElementById("txt_option_b").value;
            var txtOptionC = document.getElementById("txt_option_c").value;
            var txtOptionD = document.getElementById("txt_option_d").value;
            var errmsg = "";

            if (txtTopic == "") {
                errmsg += "Topic is missing!<br />";
            }
            else if (txtTopic.length > 1000) {
                errmsg += "Topic too long!<br />";
            }

            if (txtOptionA == "") {
                errmsg += "Option A is missing!<br />";
            }
            else if (txtOptionA.length > 1000) {
                errmsg += "Option A too long!<br />";
            }

            if (txtOptionB == "") {
                errmsg += "Option B is missing!<br />";
            }
            else if (txtOptionB.length > 1000) {
                errmsg += "Option B too long!<br />";
            }

            if (txtOptionC == "") {
                errmsg += "Option C is missing!<br />";
            }
            else if (txtOptionC.length > 1000) {
                errmsg += "Option C too long!<br />";
            }

            if (txtOptionD == "") {
                errmsg += "Option D is missing!<br />";
            }
            else if (txtOptionD.length > 1000) {
                errmsg += "Option D too long!<br />";
            }

            document.getElementById("err_publish").innerHTML = errmsg;
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
                <td width='25%'><a href='client.php'>Client Profile</a></td>
                <td width='25%'>Vote Publish</td>
                <td align='RIGHT'><input type='SUBMIT' id='submit_logout' name='submit_logout' value='Logout' /></td>
            </tr>
        </table>
    </form>

    <h3>Publish New Vote:</h3>
    <font color='#FF0000'>
        <span id='err_publish'></span>
    </font>
    <form id='form_publish_vote' name='form_publish_vote' method='POST' action='publish.php'>
        Topic: <input type='TEXT' id='txt_topic' name='txt_topic' value='' size='50' /><br />
        Option A: <input type='TEXT' id='txt_option_a' name='txt_option_a' value='' size='25' /><br />
        Option B: <input type='TEXT' id='txt_option_b' name='txt_option_b' value='' size='25' /><br />
        Option C: <input type='TEXT' id='txt_option_c' name='txt_option_c' value='' size='25' /><br />
        Option D: <input type='TEXT' id='txt_option_d' name='txt_option_d' value='' size='25' /><br />
        <input type='SUBMIT' id='submit_publish' name='submit_publish' value='Publish Vote' onclick="javascript: return validatePublish();"/>
    </form>
</body>
</html>