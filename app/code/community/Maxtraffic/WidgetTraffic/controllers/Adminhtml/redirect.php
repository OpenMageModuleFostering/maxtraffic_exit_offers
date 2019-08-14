<div id="maxtraffic-old-content">
	<div class="maxtraffic-wrap" style="max-width:1000px;margin:20px auto;" hidden>
		<h1 style="text-align:center;">MaxTraffic Exit Offers</h1>

		<?php
			$check = $this->maxtTrafficCheckData();
			if($check->ok)
			{
				$query = $this->maxtTrafficData();
		?>
		<div style="max-width:1000px;text-align:center;margin:0 auto;">
			<div style="width:50%;box-shadow: 0 0 6px 2px rgba(0, 0, 0, 0.1);border-radius: 5px;background: rgba(255, 255, 255, 0.65);padding: 10px 20px 10px 20px;margin:40px auto;">
				<br>
				<p>
					You are logged in as <b><?php echo $check->email; ?></b> (<a href="<?php echo Mage::helper('adminhtml')->getUrl("adminhtml/traffic/logout"); ?>" style="color:#F77B08;">Logout</a>) and we have placed MaxTraffic tracking code for <b><?php echo $check->domain; ?></b> in the source of your site.
				</p>
				<p>
					Go to MaxTraffic to set up and manage your campaigns.
				</p>
				<a href="//e.maxtraffic.local/magento/login?user=<?php echo $query['maxtraffic_user'] .'&website='. $query['maxtraffic_website'] .'&token='. $query['maxtraffic_token'] ?>" target="_blank" class="button button-primary" style="padding:7px;border-radius:5px;color: #fff;background-color: #F77B08;border-color: #de6f07;text-shadow:none;box-shadow:0 1px 0 #C75800;-webkit-box-shadow:0 1px 0 #C75800;background-image:none;text-decoration:none;">Go to MaxTraffic</a>
			</div>
		</div>
		<?php
			}
			else
			{
				$connection = Mage::getSingleton('core/resource');
				$writeConnection = $connection->getConnection('core_write');
				$updateQue = 'TRUNCATE TABLE maxtraffic';
				$writeConnection->query($updateQue);

				Mage::app()->getResponse()->setRedirect(Mage::helper('adminhtml')->getUrl("adminhtml/traffic/"))->sendResponse();
			}
		?>
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
