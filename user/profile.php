﻿<?php
	session_start();
	isset($_SESSION["userId"]) or header("Location: ../index.php") and exit(0);
	require_once("../phpThumb_1.7.9/phpThumb.config.php");
	include "../mysql.php";

	$conn = FALSE;
	$userId = $_SESSION["userId"];
	$userName = $_SESSION["userName"];
	$email = trim($_GET["login"]);
	$phone = trim($_POST["txt_phone"]);
	$address = trim($_POST["txt_address"]);
	$password = $_POST["pwd_new"];
	$errmsgUpload = "";
	$errmsgUpdate = "";
	$errmsgChange = "";
	$conn = dbOpen();
	if (!$conn) {
		$errmsgUpload .= mysql_error()."<br />";
	}
	else {
		$rows = retrieveUserByEmail($conn, $email);
		if (is_null($rows)) {
			$errmsgUpload .= mysql_error()."<br />";
		}
		else if (count($rows)!=1) {
			$errmsgUpload .= "Login is invalid!<br />";
		}
		else {
			$row = $rows[0];
			$uploadPath = '../images/';
			$uploadFile = $uploadPath.$row["_id"];
			if (isset($_POST["submit_upload"])) {
				$isUploaded = move_uploaded_file($_FILES["file_upload"]["tmp_name"], $uploadFile);
				if (!$isUploaded) {
					switch ($_FILES["file_upload"]["error"]) {
						case UPLOAD_ERR_INI_SIZE:
							$errmsgUpload .= "The uploaded file exceeds the maximum file size limit!<br />";
						break;
						case UPLOAD_ERR_FORM_SIZE:
							$errmsgUpload .= "The uploaded file exceeds the maximum file size limit!<br />";
						break;
						case UPLOAD_ERR_PARTIAL:
							$errmsgUpload .= "The uploaded file was only partially uploaded!<br />";
						break;
						default:
						$errmsgUpload .= "Unknown error!<br />";
					}
				}
				else if (strcmp(mime_content_type($uploadFile), "image/png")!=0 && strcmp(mime_content_type($uploadFile), "image/jpeg")!=0 &&
						strcmp(mime_content_type($uploadFile), "image/gif")!=0) {
					$errmsgUpload .= "Image type invalid!<br />";
					copy($uploadPath."profile.png", $uploadFile);
				}
			}
			else if (isset($_POST["submit_update"])) {
				$res = updateUserPhoneAndAddressByEmail($conn, $phone, $address, $email);
				if (!$res) {
					$errmsg .= mysql_error()."<br />";
				}
			}
			else if (isset($_POST["submit_change"])) {
				$res = updatePasswordByEmail( $conn, $password, $email);
				if (!$res) {
					$errmsg .= mysql_error()."<br />";
				}
			}
		}
	}
	dbClose($conn);
	$conn = FALSE;
?>
<html>
<head>
    <meta charset='UTF-8'>
    <title>User profile - Vulnerable Voting System</title>

    <style>
    	.float{
    		width: 33%;
			float: right;
    	}
    </style>

    <script type='text/javascript'>
		 function toggleUpload(isDisplay) {
			 var btnUpload = document.getElementById("btn_upload");
			 var fileUpload = document.getElementById("file_upload");
			 var submitUpload = document.getElementById("submit_upload");

			 if (isDisplay) {
				btnUpload.style.display="none";
				fileUpload.style.display="block";
				submitUpload.style.display="block";
			 }
			 else {
				 btnUpload.style.display="block";
				 fileUpload.style.display="none";
				 submitUpload.style.display="none";
			 }
        }

        function validateInfo() {
            var txtPhone = document.getElementById("txt_phone").value;
            var txtAddress = document.getElementById("txt_address").value;

            var errmsg = "";

            if (txtPhone == "") {
                errmsg += "Phone is missing!<br />";
            }
            else if (txtPhone.length != 8) {
                errmsg += "Phone number invalid!<br />";
            }
            else if (txtPhone != ("" + parseInt(txtPhone, 10))) {
                errmsg += "Phone number invalid!<br />";
            }
            if (txtAddress == "") {
                errmsg += "Address is missing!<br />";
            }
            else if (txtAddress.length > 1000) {
                errmsg += "Address too long!<br />";
            }

            document.getElementById("err_update").innerHTML = errmsg;
            return (errmsg == "");
        }

        function validatePassword() {
            var newPW = document.getElementById("pwd_new").value;
            var confirmPW = document.getElementById("pwd_confirm").value;
            var errmsg = "";

            if (newPW == "" || confirmPW == "") {
                errmsg += "Password is missing!<br />";
            }
            else if (newPW.length < 6) {
                errmsg += "Password too short!<br />";
            }
            else if (newPW.length > 30) {
                errmsg += "Password too long!<br />";
            }
            else if (newPW != confirmPW) {
                errmsg += "Password not match!<br />";
            }

            document.getElementById("err_change").innerHTML = errmsg;
            return (errmsg == "");
        }
	 </script>

</head>

<body onload='javascript: toggleUpload(false);'>
    <form id='form_logout' name='form_logout' method='POST' action='../logout.php'>
        <table border='0' width='100%'>
            <tr>
                <td colspan='3'>
                    <h2>Vulnerable Voting System</h2>
                    <h3>User profile</h3>
                </td>
                <!-- userinfo -->
                <td align='RIGHT' valign='BOTTOM'>
                    Chan Tai Man<br />
                </td>
            </tr>
            <!-- navigation -->
            <tr bgcolor='#8AC007' align='CENTER'>
                <td width='25%'>Profile</td>
                <td width='25%'><a href='voting.php?login=<?php echo $email;?>'>Voting</a></td>
                <td width='25%'><a href='result.php?login=<?php echo $email;?>'>Result</a></td>
                <td align='RIGHT'><input type='SUBMIT' id='submit_logout' name='submit_logout' value='Logout' /></td>
            </tr>
        </table>
    </form>

    <div class='float'>

        <?php
			if (file_exists($uploadFile)) {
		?>
		<img src='<?php echo htmlspecialchars(phpThumbURL("src=".$uploadFile));?>' alt='profile pic' height='150' /><br />
		<?php
			}
			else {
		?>
		<img src='<?php echo htmlspecialchars(phpThumbURL("src=../images/profile.png"));?>'	alt='profile pic' height='150' /><br />
		<?php
			}
		?>

        <form id='form_upload' name='form_upload' enctype='multipart/form-data' method='POST' action='profile.php?login=<?php echo $email;?>'>

            <input type='BUTTON' id='btn_upload' name='btn_upload' value='Upload profile pic' onclick='javascript: toggleUpload(true);'/>

            <input type='HIDDEN' id='MAX_FILE_SIZE' name='MAX_FILE_SIZE' value='1000000' />
            <input type='FILE' id='file_upload' name='file_upload'/>

            <input type='SUBMIT' id='submit_upload' name='submit_upload' value='Upload' onclick='javascript: toggleUpload(false);'/>

        </form>

    </div>



    <h3>Account Information:</h3>

    <font color='#FF0000'>
        <span id='err_upload'></span>
    </font>
    Name: Chan Tai Man<br />

    Email: chantaiman@hotmail.com<br />

    Phone: 98765432<br />

    Address: 8/F Po Kwong Building, 31-35 Shek Ku Lung Road, Mong Kok, Kowloon<br />



    <h3>Update Information:</h3>

    <font color='#FF0000'>

        <span id='err_update'></span>

    </font>

    <form id='form_update' name='form_update' enctype='multipart/form-data' method='POST' action='profile.php?login=<?php echo $email;?>'>

        Phone: <input type='TEXT' id='txt_phone' name='txt_phone' value='' size='11' /><br />

        Address: <input type='TEXT' id='txt_address' name='txt_address' value='' size='80' /><br />

        <input type='SUBMIT' id='submit_update' name='submit_update' value='Update' onclick='javascript: return validateInfo();' /><br />

    </form>



    <h3>Change Password:</h3>

    <font color='#FF0000'>

        <span id='err_change'></span>

    </font>

    <form id='form_change' name='form_change' method='POST' action='profile.php?login=<?php echo $email;?>'>

        New Password: <input type='PASSWORD' id='pwd_new' name='pwd_new' value='' size='16' /><br />

        Confirm Passowrd: <input type='PASSWORD' id='pwd_confirm' name='pwd_confirm' value='' size='16' /><br />

        <input type='SUBMIT' id='submit_change' name='submit_change' value='Change' onclick='javascript: return validatePassword();' /><br />

    </form>

</body>
</html>