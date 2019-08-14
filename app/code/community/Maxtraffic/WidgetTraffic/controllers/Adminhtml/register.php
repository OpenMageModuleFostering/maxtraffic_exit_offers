<div id="maxtraffic-old-content">
	<div class="maxtraffic-wrap" style="max-width:1000px;margin:20px auto;" hidden>
		<div style="max-width:1000px;margin:0 auto;">
			<h1 style="text-align:center;">MaxTraffic Exit Offers</h1>

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

			<div style="width:100%;text-align:center;">
				<div style="width:40%;box-shadow: 0 0 6px 2px rgba(0, 0, 0, 0.1);border-radius: 5px;background: rgba(255, 255, 255, 0.65);padding: 10px 20px 10px 20px;margin:40px auto;">
					<br>
					<form action="<?php echo Mage::helper('adminhtml')->getUrl("adminhtml/traffic/register"); ?>">
						<p>
							<label for="name"><b>Your name</b></label>
							<input type="text" name="name" style="float:right;font-size:13px;">
						</p>
						<p>
							<label for="surname"><b>Your surname</b></label>
							<input type="text" name="surname" style="float:right;font-size:13px;">
						</p>
						<p>
							<label for="email"><b>E-mail</b></label>
							<input type="text" name="email" style="float:right;font-size:13px;">
						</p>
						<p>
							<label for="domain"><b>Website</b></label>
							<input type="text" name="domain" autocomplete="off" style="float:right;font-size:13px;" value="<?php echo $domain ?>">
						</p>
						<p>
							<br>
							<br>
							<input type="submit" value="Create My Account" class="button button-primary" style="cursor:pointer;padding:7px;color: #fff;background-color: #F77B08;border:none;text-shadow:none;box-shadow:0 1px 0 #C75800;-webkit-box-shadow:0 1px 0 #C75800;background-image:none;border-radius:5px;">
						</p>
					</form>	
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