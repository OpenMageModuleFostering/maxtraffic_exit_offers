<?php
 
class Maxtraffic_WidgetTraffic_Adminhtml_TrafficController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {

        $storeId = Mage::app()->getStore()->getStoreId();

        $domain = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);
        $websiteUrl = substr_compare($domain, '/index.php/', 0, 11) ? substr($domain, 0, -11) : $domain;

        $connection = Mage::getSingleton('core/resource');
        $readConnection = $connection->getConnection('core_read');
        $maxtrafficSites = $readConnection->fetchAll("SELECT * FROM maxtraffic");

        if(empty($maxtrafficSites))
        {
            $user = Mage::getSingleton('admin/session');
            $userFirstName = $user->getUser()->getFirstname();
            $email = $user->getUser()->getEmail();
        
            $url =  'http://e.maxtraffic.com/magento/response?&store='.$websiteUrl.'&email='.$email.'&name='.$userFirstName;
            $response = json_decode(file_get_contents($url));

            if($response->ok)
            {
                $writeConnection = $connection->getConnection('core_write');
                $writeConnection->insert('maxtraffic', array('maxtraffic_website_id' => $response->data->website_id, 'maxtraffic_token' => $response->data->user_token));

                $template = "\n<!-- MaxTraffic script -->\n<script type='text/javascript'>\n(function() {\nvar k = document.createElement('script');\nk.type = 'text/javascript';\nk.async = true;\nk.src = '//e.maxtraffic.com/serve/public/index.php?id=".$response->data->website_id."&d=".$response->data->website_name."';\n var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(k, s);\n})();\n</script>\n<!-- End of MaxTraffic script -->";

                $headerScripts = $readConnection->fetchAll("SELECT * FROM core_config_data WHERE path = 'design/head/includes'");

                if(empty($headerScripts))
                {
                    $writeConnection->insert('core_config_data', array('scope' => 'default', 'value' => $template, 'path' => 'design/head/includes', 'scope_id' => '0'));
                }
                else
                {
                    foreach ($headerScripts as $header)
                    {
                        $script = $header['value'] .' '. $template;

                        $updateQue = 'UPDATE core_config_data SET value = "'.$script.'" WHERE config_id = '.$header['config_id'];
                        $writeConnection->query($updateQue);
                    }
                }

                $redirect = 'login';
            }
            else
            {
                $redirect = 'invalid';
            }
        }
        else
        {
            $redirect = 'login';
        }

        Mage::app()->getResponse()->setRedirect(Mage::helper('adminhtml')->getUrl("adminhtml/traffic/".$redirect))->sendResponse();
    }

    public function loginAction()
    {
        $connection = Mage::getSingleton('core/resource');
        $readConnection = $connection->getConnection('core_read');
        $maxtrafficSites = $readConnection->fetchAll("SELECT * FROM maxtraffic");

        $maxtrafficToken = empty($maxtrafficSites) ? null : $maxtrafficSites[0]['maxtraffic_token'];
        $maxtrafficWebsiteId = empty($maxtrafficSites) ? null : $maxtrafficSites[0]['maxtraffic_website_id'];

        $url =  'http://e.maxtraffic.com/magento/auth?token='.$maxtrafficToken.'&website='.$maxtrafficWebsiteId;

        header("Location: ".$url);
    }

    public function invalidAction()
    {
        $this->loadLayout();
         
        $block = $this->getLayout()->createBlock('core/text', 'invalid-message')->setText('<h1>Unable to connect with MaxTraffic</h1><p>Invalid e-mail</p>');
        $this->_addContent($block);
         
        $this->renderLayout();  
    }
}