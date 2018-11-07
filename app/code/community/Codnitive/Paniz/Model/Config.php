<?php
/**
 * CODNITIVE
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE_EULA.html.
 * It is also available through the world-wide-web at this URL:
 * http://www.codnitive.com/en/terms-of-service-softwares/
 * http://www.codnitive.com/fa/terms-of-service-softwares/
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade to newer
 * versions in the future.
 *
 * @category   Codnitive
 * @package    Codnitive_Paniz
 * @author     Hassan Barza <support@codnitive.com>
 * @copyright  Copyright (c) 2012 CODNITIVE Co. (http://www.codnitive.com)
 * @license    http://www.codnitive.com/en/terms-of-service-softwares/ End User License Agreement (EULA 1.0)
 */

class Codnitive_Paniz_Model_Config
{

	const PATH_NAMESPACE      = 'codnitivepaniz';
	const EXTENSION_NAMESPACE = 'paniz';
	const CUSTOMER_NAMESPACE  = 'customer';
	const SALES_NAMESPACE     = 'sales';

	const EXTENSION_NAME    = 'Paniz SMS';
	const EXTENSION_VERSION = '1.0.00';
	const EXTENSION_EDITION = 'Basic';

	public static function getNamespace()
	{
		return self::PATH_NAMESPACE . '/' . self::EXTENSION_NAMESPACE . '/';
	}

	public static function getCustomerNamespace()
	{
		return self::PATH_NAMESPACE . '/' . self::CUSTOMER_NAMESPACE . '/';
	}

	public static function getSalesNamespace()
	{
		return self::PATH_NAMESPACE . '/' . self::SALES_NAMESPACE . '/';
	}

	public function getExtensionName()
	{
		return self::EXTENSION_NAME;
	}

	public function getExtensionVersion()
	{
		return self::EXTENSION_VERSION;
	}

	public function getExtensionEdition()
	{
		return self::EXTENSION_EDITION;
	}

	public function isActive()
	{
		return Mage::getStoreConfigFlag(self::getNamespace() . 'active');
	}

	public function getUsername()
	{
		return Mage::getStoreConfig(self::getNamespace() . 'username');
	}

	public function getPassword()
	{
		return Mage::getStoreConfig(self::getNamespace() . 'password');
	}

	public function getSenderNumber()
	{
		return Mage::getStoreConfig(self::getNamespace() . 'sender_number');
	}

	public function getCanSendSms()
	{
		$username = $this->getUsername();
		$password = $this->getPassword();

		return $this->isActive() && !empty($username) && !empty($password);
	}

	public function getAdminNumber()
	{
		return Mage::getStoreConfig(self::getNamespace() . 'admin_number');
	}

	public function isFlash()
	{
		return Mage::getStoreConfigFlag(self::getNamespace() . 'flash');
	}

	public function isDebug()
	{
		return Mage::getStoreConfigFlag(self::getNamespace() . 'debug');
	}

	public function sendOnRegister()
	{
		return Mage::getStoreConfigFlag(self::getCustomerNamespace() . 'register')
			&& $this->getCanSendSms();
	}

	public function getRegisterMessage()
	{
		return Mage::getStoreConfig(self::getCustomerNamespace() . 'register_msg');
	}

	public function sendOnLogin()
	{
		return Mage::getStoreConfigFlag(self::getCustomerNamespace() . 'login')
			&& $this->getCanSendSms();
	}

	public function getLoginMessage()
	{
		return Mage::getStoreConfig(self::getCustomerNamespace() . 'login_msg');
	}

	public function sendOnOrderPlace()
	{
		return Mage::getStoreConfigFlag(self::getSalesNamespace() . 'order')
			&& $this->getCanSendSms();
	}

	public function sendAdminOnOrderPlace()
	{
		return Mage::getStoreConfigFlag(self::getSalesNamespace() . 'order_admin')
			&& $this->getCanSendSms();
	}

	public function getOrderMessage()
	{
		return Mage::getStoreConfig(self::getSalesNamespace() . 'order_msg');
	}

}
