<html>
<head>
    <meta charset='UTF-8'>
    <title>User Result - Vulnerable Voting System</title>
</head>

<body>
    <form id='form_logout' name='form_logout' method='POST' action='../logout.php'>
        <table border='0' width='100%'>
            <tr>
                <td colspan='3'>
                    <h2>Vulnerable Voting System</h2>
                    <h3>User Result</h3>
                </td>
                <!-- userinfo -->
                <td align='RIGHT' valign='BOTTOM'>
                    Chan Tai Man<br />
                </td>
            </tr>
            <!-- navigation -->
            <tr bgcolor='#8AC007' align='CENTER'>
                <td width='25%'><a href='profile.php?login=<?=$email;?>'>Profile</a></td>
                <td width='25%'><a href='voting.html?login=<?=$email;?>'>Voting</a></td>
                <td width='25%'>Result</td>
                <td align='RIGHT'><input type='SUBMIT' id='submit_logout' name='submit_logout' value='Logout' /></td>
            </tr>
        </table>
    </form>

    <h3>Voting Record:</h3>
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
</body>
</html>