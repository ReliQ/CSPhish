<?php   
/**
 *  CSPhish
 *  A Computer Security assignment.
 *
 *  @author Patrick Reid
 *  @link http://www.reliqartz.com
 */

error_reporting(E_STRICT | E_ALL);

date_default_timezone_set('Etc/UTC');

require '../../mailer/PHPMailerAutoload.php';
require_once("../../incl/header.php");
require_once("../../config.php");

$mail = new PHPMailer();

$body = file_get_contents('../email.html');

$resultView = "<div class=\"actions\">";

$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
if($debug){
    $mail->SMTPDebug = 2;
    //Ask for HTML-friendly debug output
    $mail->Debugoutput = 'html';
}
$mail->Host = $mail_host;
$mail->Port = 587;
$mail->SMTPSecure = 'tls';
$mail->SMTPAuth = true;
$mail->SMTPKeepAlive = true;
$mail->Username = $mail_user;
$mail->Password = $mail_password;
$mail->setFrom($mail_from, $mail_from);
$mail->addReplyTo($mail_replyTo, $mail_replyTo);

$mail->Subject = "Reset your PayPal password";

//connect to the database and select the recipients from your mailing list that have not yet been sent to
//You'll need to alter this to match your database
$mysqli = new mysqli($mysql_host, $mysql_user, $mysql_password, $mysql_db);
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$query = "SELECT full_name, email FROM mailinglist ";//WHERE sent = false";
$result = $mysqli->query($query) or die($mysqli->error.__LINE__);

if($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
        $mail->msgHTML($body);
        $mail->addAddress($row['email'], $row['full_name']);
        //$mail->addStringAttachment($row['photo'], 'YourPhoto.jpg'); //Assumes the image data is stored in the DB
        $resultView .= "<ul>";
        if (!$mail->send()) {
            $resultView .= "<li class=\"error\">Mailer Error (" . str_replace("@", "&#64;", $row["email"]) . ') ' . $mail->ErrorInfo . '</li>';
            break; //Abandon sending
        } else {
            $resultView .= "<li class=\"success\">Message sent to : " . $row['full_name'] . ' (' . str_replace("@", "&#64;", $row['email']) . ')</li>';
            //Mark it as sent in the DB
            $mysqli->query(
                "UPDATE mailinglist SET sent = true WHERE email = '" . mysqli_real_escape_string($mysqli, $row['email']) . "'"
            );
        }
        $resultView .= "</ul>";

        // Clear all addresses and attachments for next loop
        $mail->clearAddresses();
        $mail->clearAttachments();
    }
    $resultView .= "<p>Attempt complete. Results are shown above.</p>";

} else {
    $resultView .= '<p>NO RESULTS: No one to send to.</p>';  
}

$resultView .= "<a class=\"act\" href= \"../../\">Return to main</a>";
$resultView .= "</div>";

// close connection
mysqli_close($mysqli);

// print results
echo $resultView;
require_once("../../incl/footer.php");