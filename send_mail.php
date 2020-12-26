<?php
require_once('include.php');
if (!$_POST)
{
  $display_block = True;
}
 //haven’t seen the form, so display it
if ($display_block) 
{ 
?>
 <form action="<?php echo $_SERVER['PHP_SELF'];?>" method = "post">
 <p><label for="subject">Subject:</label><br/>
 <input type="text" id="subject" name="subject" size="40" /></p>

 <p><label for="message">Mail Body:</label><br/>
 <textarea id="message" name="message" cols="50" rows="10"></textarea></p>
 <button type="submit" name="submit" value="submit">Submit</button>
 </form>
 <?php
}
  elseif ($_POST) {
  //want to send form, so check for required fields
 if (($_POST['subject'] == "") || ($_POST['message'] == "")) {
  header("Location: sendmail.php");
 exit;
 }

 //connect to database
 doDB();
 if (mysqli_connect_errno()) 
 {
 //if connection fails, stop script execution
 printf("Connect failed: %s\n", mysqli_connect_error());
 exit();
 } else {
 //otherwise, get emails from subscribers list
 $sql = "SELECT email FROM subscribers";
 $result = mysqli_query($mysqli, $sql)
 or die(mysqli_error($mysqli));
 //create a From: mailheader
 $mailheaders = "email@email.com";
 //loop through results and send mail
 while ($row = mysqli_fetch_array($result)) {
 set_time_limit(0);
 $email = $row['email'];
 @mail("$email", stripslashes($_POST['subject']), stripslashes($_POST['message']), $mailheaders);
 $display_block.= "newsletter sent to: ".$email."<br/>";
 }
 mysqli_free_result($result);
 mysqli_close($mysqli);
 }
 }
 ?>
 <!DOCTYPE html>
 <html>
 <head>
 <title>Sending a Newsletter</title>
 </head>
 <body>
 <h1>Send a Newsletter</h1>
<?php echo $display_block ; ?>
 </body>
 </html>