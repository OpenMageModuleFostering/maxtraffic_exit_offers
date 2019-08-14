<div id="maxtraffic-old-content">
	<div class="maxtraffic-wrap" style="max-width:1000px;margin:20px auto;" hidden>
		<div style="max-width:1000px;margin:0 auto;">
			<h1 style="text-align:center;">MaxTraffic Exit Offers</h1>

			<h2 style="text-align:center;">Thank you for installing our plugin!</h2>
			<div style="width:100%;text-align:center;">
				<p>If you are new to MaxTraffic then use the form to your left and create a new account. After registration go to our platform and create your first campaign. New users can use our platform 14 days for free and then decide if they want to continue with paid subscription.</p>
				<p>If you already have an account with us, log in with form on your right. We will automatically place our tracking code on your site and you will be able to show ads to your leaving visitors.</p>
			</div>

			<?php
				if(isset($_SESSION['message']))
				{
			?>
				<div style="padding: 15px;margin-bottom: 20px;border: 1px solid transparent;border-radius: 4px;background-color: #f2dede;border-color: #ebccd1;color: #a94442;">
					<b>
						<?php
							echo $_SESSION['message'];

							unset($_SESSION['message']);
		    			?>
		    		</b>
				</div>
			<?php
				}
			?>

			<div style="width:100%;text-align:center;display:-webkit-flex;display:flex;height:inherit;">
				<div style="width:50%;-webkit-flex: 1;-ms-flex: 1;flex: 1;box-shadow: 0 0 6px 2px rgba(0, 0, 0, 0.1);border-radius: 5px;background: rgba(255, 255, 255, 0.65);padding: 10px 20px 10px 20px;margin:40px 20px;">
					<h2>I am a new user</h2>
					<p>
						<a href="<?php echo Mage::helper('adminhtml')->getUrl("adminhtml/traffic/register"); ?>" class="button button-primary" style="padding:7px;border-radius:5px;color: #fff;background-color: #F77B08;border-color: #de6f07;text-shadow:none; margin-top:50px;box-shadow:0 1px 0 #C75800;-webkit-box-shadow:0 1px 0 #C75800;background-image:none;text-decoration:none;">Create new account</a>
					</p>
				</div>
				<div style="width:50%;-webkit-flex: 1;-ms-flex: 1;flex: 1;box-shadow: 0 0 6px 2px rgba(0, 0, 0, 0.1);border-radius: 5px;background: rgba(255, 255, 255, 0.65);padding: 10px 20px 10px 20px;margin:40px 20px;">
					<p>
						<h2>I already have an account with MaxTraffic</h2>
						<br>
						<form action="<?php echo Mage::helper('adminhtml')->getUrl("adminhtml/traffic"); ?>">
							<p>
								<label for="email"><b>E-mail</b></label>
								<input type="text" name="email" style="float:right;" value="">
							</p>
							<p>
								<label for="password"><b>Password</b></label>
								<input type="password" name="password" autocomplete="off" style="float:right;" value="">
							</p>
							<p>
								<br>
								<input type="submit" value="Login" class="button button-primary" style="cursor:pointer;padding:7px;color: #fff; border:none;background-color:#F77B08;text-shadow:none;box-shadow:0 1px 0 #C75800;-webkit-box-shadow:0 1px 0 #C75800;background-image:none;border-radius:5px;">
							</p>
						</form>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
<script type="text/javascript">
var $m = jQuery.noConflict();
$m(document).ready(function()
{
	var content = $m('.maxtraffic-wrap').html();
	$m('#maxtraffic-old-content').remove();
	$m('#maxtraffic-content').html(content);
});
</script>