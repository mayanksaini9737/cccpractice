<?php
class Customer_Controller_Account extends Core_Controller_Front_Action
{
    protected $_allowedAction = ['register', 'login'];

    public function init()
    {
        // $this->getRequest()->getActionName();
        if (
            !in_array($this->getRequest()->getActionName(), $this->_allowedAction) &&
            !Mage::getSingleton('core/session')->get('logged_in_customer_id')
        ) {
            $this->setRedirect('customer/account/dashboard');
            $this->setRedirect('customer/account/login');
        }
    }
    public function registerAction()
    {
        $layout = $this->getLayout();
        $layout->removeChild('header')->removeChild('footer');
        $layout->getChild('head')->addCss('customer/account/register.css');
        $layout->getChild('head')->addJs('customer/account/register.js');
        $child = $layout->getChild('content');
        $loginForm = $layout->createBlock('customer/account_register');
        $child->addChild('registerForm', $loginForm);
        $layout->toHtml();
    }
    public function saveAction()
    {
        echo "<pre>";
        $registerCredentials = $this->getRequest()->getParams('customer');
        $checkCustomerExist = Mage::getModel('customer/customer')
            ->getCollection()
            ->addFieldToFilter('customer_email', $registerCredentials['customer_email'])
            ->getData();
        $address = Mage::getBaseUrl("customer/account");
        if (count($checkCustomerExist)) {
            echo "<script>
                alert('email already exist');
                location. href= '{$address}/register';
            </script>";
        } else {
            $customerAccount = Mage::getModel('customer/customer')
                ->setData($registerCredentials)->save();
            echo "<script>
                alert('Register successfully');
                location. href= '{$address}/login';
            </script>";
            print_r($customerAccount);
        }
    }
    public function loginAction()
    {
        $layout = $this->getLayout();
        $layout->removeChild('header')->removeChild('footer');
        $layout->getChild('head')->addCss('customer/account/login.css');
        $child = $layout->getChild('content');
        $loginForm = $layout->createBlock('customer/account_login');
        $child->addChild('loginForm', $loginForm);
        $layout->toHtml();
        if ($this->getRequest()->isPost()) {
            $loginCredentials = $this->getRequest()->getParams('login');
            $email = $loginCredentials['email'];
            $password = $loginCredentials['password'];
            $customerCollection = Mage::getModel('customer/customer')->getCollection();
            $customerCollection->addFieldToFilter('customer_email', $email);
            $customerCollection->addFieldToFilter('password', $password);
            $count = 0;
            $customerId = 0;
            foreach ($customerCollection->getData() as $row) {
                $count++;
                $customerId = $row->getId();
            }
            if ($count) {
                Mage::getSingleton('core/session')
                    ->set('logged_in_customer_id', $customerId);
                $this->setRedirect('customer/account/dashboard');
            } else {
                $this->setRedirect('customer/account/login');
            }
        }
    }

    public function dashboardAction()
    {
        $customerId = Mage::getSingleton('core/session')
            ->get('logged_in_customer_id');
        if ($customerId) {
            $layout = $this->getLayout();
            // $child = $layout->getChild('content');
            // $dashboard = $layout->createBlock('customer/');
            $layout->toHtml();
            echo "<pre>";
            $customer = Mage::getModel('customer/customer')->load($customerId);
            print_r($customer);
        } else {
            $address = Mage::getBaseUrl('customer/account/login');
            header('Location:' . $address);
        }
    }
    public function forgotpasswordAction()
    {
        $layout = $this->getLayout();
        $layout->removeChild('header')->removeChild('footer');
        $layout->getChild('head')->addCss('customer/account/forgotpassword.css');
        $child = $layout->getChild('content');
        $loginForm = $layout->createBlock('customer/account_forgotpassword');
        $child->addChild('forgotPasswordform', $loginForm);
        $layout->toHtml();
    }
}
?>