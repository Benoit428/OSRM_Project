<?php


require_once 'includes/config.php';
require_once 'includes/functions.php';
// Database connection
$db = getDatabase();


// start session
session_start();

// already logged in!
if (isset($_SESSION['email'])) {
    header('location: index.php');
    exit();
}

$msg = "";
//if ($_SERVER['REQUEST_METHOD'] == 'POST') {

// formulier verzonden
if (isset($_POST['moduleAction']) && ($_POST['moduleAction'] == 'login')) {
    $name = isset($_POST["email"]) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // lijst met alle users ophalen
    $stmt = $db->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute(array($email));
    $user = $stmt->fetch(PDO::FETCH_ASSOC);



	if (($email != '') && (password_verify($password, $user['password']))) {
	    //sla user op
	    $_SESSION['user'] = $user;

	    //redirect naar zoekpagina
	    header('location: zoekpagina.php');
        exit();


	} else {
        $msg = "Niet alle gegevens zijn correct";
    }
    /*
     else {
        $sql = "SELECT * FROM members WHERE name = '$email' AND password = '$password'";
        $query = mysql_query($sql);

        if ($query === false) {
            echo "Could not successfully run query ($sql) from DB: " . mysql_error();
            exit;
        }

        if (mysql_num_rows($query) > 0) {
         
            header('Location: YOUR_LOCATION');
            exit;
        }

        $msg = "Username and password do not match";
    }
    */
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>discussdesk.com - Login form in PHP mysql</title>
<meta name="description" content=""/>
<meta name="keywords" content=""/>
<link href="style.css" rel="stylesheet" type="text/css">

</head>
<body>

	<form name="frmregister"action="<?= $_SERVER['PHP_SELF'] ?>" method="post" >
		<table class="form" border="0">

			<tr>
			<td></td>
				<td style="color:red;">
				<?php echo $msg; ?></td>
			</tr> 
			
			<tr>
				<th><label for="name"><strong>Name:</strong></label></th>
				<td><input class="inp-text" name="username" id="username" type="text" size="30" /></td>
			</tr>
			<tr>
				<th><label for="name"><strong>Password:</strong></label></th>
				<td><input class="inp-text" name="password" id="password" type="password" size="30" /></td>
			</tr>
			<tr>
			<td></td>
				<td class="submit-button-right">
				<input class="send_btn" type="submit" value="Submit" alt="Submit" title="Submit" />
				
				<input class="send_btn" type="reset" value="Reset" alt="Reset" title="Reset" /></td>
				
			</tr>
		</table>
	</form>



</body>
</html>
