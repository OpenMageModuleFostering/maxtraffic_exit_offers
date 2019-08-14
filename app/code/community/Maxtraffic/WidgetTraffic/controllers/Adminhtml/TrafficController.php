<?php
 
class Maxtraffic_WidgetTraffic_Adminhtml_TrafficController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        // Check if maxtraffic table exists
        if(!$this->maxtTrafficRecordsExists() || $this->maxtTrafficEmptyUserLogin() || !$this->maxtTrafficCheckData()->ok)
        {
            // If table or record doesn't exist, show welcome page with menu
            return $this->maxtTrafficWelcome();
        }

        // If table record exist
        return $this->maxtTrafficGetRedirect();
    }

    public function maxtTrafficWelcome()
    {
        $post = Mage::app()->getRequest()->getParams();

        if(!empty($post['email']) && !empty($post['password']))
        {
            $email = filter_var($post['email'], FILTER_SANITIZE_SPECIAL_CHARS);
            $password = filter_var($post['password'], FILTER_SANITIZE_SPECIAL_CHARS);

            $storeUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);
            $domain = substr_compare($storeUrl, '/index.php/', 0, 11) ? substr($storeUrl, 0, -11) : $storeUrl;

            return $this->maxtTrafficPostLogin($email, $password, $domain);
        }

        // Show layout
        $this->loadLayout();
        $block = $this->getLayout()
            ->createBlock('core/text', 'maxtraffic-section')
            ->setText('<div id="maxtraffic-content"><h1>Loading</p></div>');
        $this->_addContent($block);
        $this->renderLayout();

        //
        include Mage::getModuleDir('controllers', 'Maxtraffic_WidgetTraffic').'/Adminhtml/welcome.php';
    }

    public function maxtTrafficGetRedirect()
    {
        $this->loadLayout();
        $block = $this->getLayout()
            ->createBlock('core/text', 'maxtraffic-section')
            ->setText('<div id="maxtraffic-content"><h1>Loading</p></div>');
        $this->_addContent($block);
        $this->renderLayout();

        include Mage::getModuleDir('controllers', 'Maxtraffic_WidgetTraffic').'/Adminhtml/redirect.php';
    }

    public function maxtTrafficPostLogin($email, $password, $domain)
    {
        $data = array(
            'email' => $email,
            'password' => $password,
            'domain' => $domain
        );

        $data = implode(';', $data);
        $salt = "MaxTraffic Pop-Up Plugin";
        $crypted_data = trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $salt, $data, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));

        $url = 'http://e.maxtraffic.local/magento/login-response?data='. $crypted_data;
        $response = json_decode(file_get_contents($url));

        if(!$response->ok)
        {
            $_SESSION['message'] = 'Invalid login data!';

            $this->loadLayout();
            $block = $this->getLayout()
                ->createBlock('core/text', 'maxtraffic-section')
                ->setText('<div id="maxtraffic-content"><h1>Loading</p></div>');
            $this->_addContent($block);
            $this->renderLayout();
            include Mage::getModuleDir('controllers', 'Maxtraffic_WidgetTraffic').'/Adminhtml/welcome.php';

            return;
        }

        $user = $response->user_id;
        $website = $response->website;
        $token = $response->magento_token;
        $tracking_code = $response->tracking_code;

        if($this->maxtTrafficRecordsExists() || $this->maxtTrafficEmptyUserLogin())
        {
            // Insert data in table
            $this->maxtTrafficInsertData($user, $website, $token, $tracking_code);
        }

        Mage::app()->getResponse()->setRedirect(Mage::helper('adminhtml')->getUrl("adminhtml/traffic/"))->sendResponse();

    }

    public function registerAction()
    {
        $post = Mage::app()->getRequest()->getParams();

        if(empty($post['email']) && empty($post['domain']))
        {
            $storeUrl = Mage::app()->getStore($storeId)->getBaseUrl(Mage_Core_Model_Store::URL_TYPE_LINK);
            $domain = substr_compare($storeUrl, '/index.php/', 0, 11) ? substr($storeUrl, 0, -11) : $storeUrl;

            $this->loadLayout();
            $block = $this->getLayout()
                ->createBlock('core/text', 'maxtraffic-section')
                ->setText('<div id="maxtraffic-content"><h1>Loading</p></div>');
            $this->_addContent($block);
            $this->renderLayout();

            include Mage::getModuleDir('controllers', 'Maxtraffic_WidgetTraffic').'/Adminhtml/register.php';

            return;
        }

        // Get email and domain, make url
        
        $name = filter_var($post['name'], FILTER_SANITIZE_SPECIAL_CHARS);
        $surname = filter_var($post['surname'], FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_var($post['email'], FILTER_SANITIZE_SPECIAL_CHARS);
        $domain = filter_var($post['domain'], FILTER_SANITIZE_SPECIAL_CHARS);

        // Validate input
        $validator = $this->maxtTrafficInputValidator($email, $domain);

        if(!$validator)
        {
            $_SESSION['message'] = 'Invalid E-mail or Website!';

            $this->loadLayout();
            $block = $this->getLayout()
                ->createBlock('core/text', 'maxtraffic-section')
                ->setText('<div id="maxtraffic-content"><h1>Loading</p></div>');
            $this->_addContent($block);
            $this->renderLayout();

            include Mage::getModuleDir('controllers', 'Maxtraffic_WidgetTraffic').'/Adminhtml/register.php';

            return;
        }
        
        $url = 'http://e.maxtraffic.local/magento/register?email='. $email .'&domain='. $domain .'&name='. $name .'&surname='. $surname;

        $response = json_decode(file_get_contents($url));

        // Check if valid response
        if(!$response->ok)
        {
            $_SESSION['message'] = 'Invalid E-mail or already registred!';

            $this->loadLayout();
            $block = $this->getLayout()
                ->createBlock('core/text', 'maxtraffic-section')
                ->setText('<div id="maxtraffic-content"><h1>Loading</p></div>');
            $this->_addContent($block);
            $this->renderLayout();

            include Mage::getModuleDir('controllers', 'Maxtraffic_WidgetTraffic').'/Adminhtml/register.php';

            return;
        }

        $user = $response->user_id;
        $website = $response->website;
        $token = $response->magento_token;
        $tracking_code = $response->tracking_code;
        
        // Insert data in table
        $this->maxtTrafficInsertData($user, $website, $token, $tracking_code);

        Mage::app()->getResponse()->setRedirect(Mage::helper('adminhtml')->getUrl("adminhtml/traffic/"))->sendResponse();
    }

    public function maxtTrafficRecordsExists()
    {
        $connection = Mage::getSingleton('core/resource');
        $readConnection = $connection->getConnection('core_read');
        $records = count($readConnection->fetchAll("SELECT * FROM maxtraffic")) > 0 ? true : false;

        return $records;
    }

    public function maxtTrafficEmptyUserLogin()
    {
        $row = $this->maxtTrafficData();

        return empty($row['maxtraffic_user']) || empty($row['maxtraffic_website']) ? true : false;
    }

    public function maxtTrafficdata()
    {
        $connection = Mage::getSingleton('core/resource');
        $readConnection = $connection->getConnection('core_read');
        $query = $readConnection->fetchAll("SELECT * FROM maxtraffic");

        return $query[0];
    }

    public function maxtTrafficInsertData($user, $website, $token, $trackingCode)
    {
        $connection = Mage::getSingleton('core/resource');
        $writeConnection = $connection->getConnection('core_write');
        $writeConnection->insert('maxtraffic', array('maxtraffic_user' => $user,'maxtraffic_website' => $website, 'maxtraffic_token' => $token));

        $readConnection = $connection->getConnection('core_read');
        $footerScripts = $readConnection->fetchAll("SELECT * FROM core_config_data WHERE path = 'design/footer/absolute_footer'");

        if(empty($footerScripts))
        {
            $writeConnection->insert('core_config_data', array('scope' => 'default', 'value' => '<!-- MaxTraffic -->'.$trackingCode.'<!-- MaxTraffic -->', 'path' => 'design/footer/absolute_footer', 'scope_id' => '0'));
        }
        else
        {
            foreach ($footerScripts as $footer)
            {
                $script = $footer['value'] .' <!-- MaxTraffic -->'. str_replace('"', "'", $trackingCode).'<!-- MaxTraffic -->';

                $updateQue = 'UPDATE core_config_data SET value = "'.$script.'" WHERE config_id = '.$footer['config_id'];
                $writeConnection->query($updateQue);
            }
        }
    }

    public function maxtTrafficCheckData()
    {
        $response = new StdClass;
        $response->ok = false;

        if(!$this->maxtTrafficRecordsExists())
        {
            return $response;
        }

        $query = $this->maxtTrafficData();

        $url = 'http://e.maxtraffic.local/magento/login-check?user='. $query['maxtraffic_user'] .'&website='. $query['maxtraffic_website'] .'&token='. $query['maxtraffic_token'];

        $data = json_decode(file_get_contents($url));

        $response = $data->ok ? $data : $response;

        return $response;
    }

    public function maxtTrafficInputValidator($email, $domain)
    {
        $emailNecesary = array('@', '.');
        $websiteBanned = array('@', '~', '°', '!', '|', '"');
        $websiteNecesary = array('.');

        $result = true;

        foreach($emailNecesary as $value)
        {
            if($result && !strpos($email, $value))
            {
                $result = false;
            }
        }

        foreach($websiteBanned as $value)
        {
            if($result && strpos($domain, $value))
            {
                $result = false;
            }
        }

        foreach($websiteNecesary as $value)
        {
            if($result && !strpos($domain, $value))
            {
                $result = false;
            }
        }

        return $result;
    }

    public function logoutAction()
    {
        if ($this->maxtTrafficRecordsExists())
        {
            $connection = Mage::getSingleton('core/resource');
            $writeConnection = $connection->getConnection('core_write');
            $updateQue = 'TRUNCATE TABLE maxtraffic';
            $writeConnection->query($updateQue);

            $readConnection = $connection->getConnection('core_read');
            $footerScripts = $readConnection->fetchAll("SELECT * FROM core_config_data WHERE path = 'design/footer/absolute_footer'");

            if(!empty($footerScripts))
            {
                foreach ($footerScripts as $footer)
                {
                    $explodedScript = explode('<!-- MaxTraffic -->', $footer['value']);
                    $removedScript = str_replace(array($explodedScript[1], '<!-- MaxTraffic -->'), '', $footer['value']);

                    $updateQue = 'UPDATE core_config_data SET value = "'.$removedScript.'" WHERE config_id = '.$footer['config_id'];
                    $writeConnection->query($updateQue);
                }
            }
        }

        Mage::app()->getResponse()->setRedirect(Mage::helper('adminhtml')->getUrl("adminhtml/traffic/"))->sendResponse();
    }

}