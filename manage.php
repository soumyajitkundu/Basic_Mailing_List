<?php
require_once('include.php');
 //determine if they need to see the form or not
$display_block = True;
if (isset($_POST['submit'])) 
{
$display_block = False;
 }
 if ($display_block) { 
?>
<form action="<?php echo $_SERVER['PHP_SELF'];?>" method = "post" >
<p><label for="email">Your E-Mail Address:</label><br/>
<input type="email" id="email" name="email" size="40" maxlength="150" /></p>
<fieldset>
<legend>Action:</legend><br/>
<input type="radio" id="action_sub" name="action" value="sub" checked />
<label for="action_sub">subscribe</label><br/>
<input type="radio" id="action_unsub" name="action" value="unsub" />
<label for="action_unsub">unsubscribe</label>
</fieldset>
<button type="submit" name="submit" value="submit">Submit</button>
</form>
<?php
}
elseif($_POST['action'] == "sub")
{
//trying to subscribe; validate email address
if ($_POST['email'] =="") 
{
 header("Location: manage.php");
 exit;
}
else 
{
//connect to database
doDB();
//check that email is in list
emailChecker($_POST['email']);
//get number of results and do action
if (mysqli_num_rows($check_res) < 1)
{
//free result
mysqli_free_result($check_res);
//add record
$add_sql ="INSERT INTO subscribers (email) VALUES('".$safe_email."')";
$add_res = mysqli_query($mysqli, $add_sql) or die(mysqli_error($mysqli));
$display_block = "<p>Thanks for signing up!</p>";
//close connection to MySQL
mysqli_close($mysqli);
} 
else
{
//print failure message
$display_block = "<p>You're already subscribed!</p>";
    }
  }
} 
elseif (($_POST) && ($_POST['action'] == "unsub"))
{
 //trying to unsubscribe; validate email address
if ($_POST['email'] == "")
{
  header("Location: manage.php");
  exit;
}
else 
{
//connect to database
doDB();
//check that email is in list
emailChecker($_POST['email']);
//get number of results and do action
if (mysqli_num_rows($check_res) < 1) 
{
//free result
mysqli_free_result($check_res);
//print failure message
$display_block = "<p>Couldn’t find your address!</p>
<p>No action was taken.</p>";
}
else 
{
//get value of ID from result
while ($row = mysqli_fetch_array($check_res))
{
$id = $row['id'];
}
//unsubscribe the address
$del_sql = "DELETE FROM subscribers WHERE id = ".$id;
$del_res = mysqli_query($mysqli, $del_sql) or die(mysqli_error($mysqli));
$display_block= "<p>You’re unsubscribed!</p>";
}
mysqli_close($mysqli);
}
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Subscribe/Unsubscribe to a Mailing List</title>
</head>
<body>
<h1>Subscribe/Unsubscribe to a Mailing List</h1>
<?php echo $display_block ; ?>
</body>
</html>