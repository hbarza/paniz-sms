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

class Codnitive_Paniz_Model_Customer_Observer extends Mage_Core_Model_Abstract
{
	public function getConfig()
	{
		return Mage::getModel('paniz/config');
	}

	protected function _getHelper()
	{
		return Mage::helper('paniz');
	}

	protected function _getService()
	{
		return Mage::getModel('paniz/service');
	}

	public function registerSendSms(Varien_Event_Observer $observer)
	{
		$phoneNumber = $this->_getCustomerPhoneNumber($observer);
		if (empty($phoneNumber)) {
			$phoneNumber = Mage::app()->getRequest()->getParam('telephone');
		}

		if (!$this->getConfig()->sendOnRegister() || empty($phoneNumber)) {
			return $this;
		}

		try {
			$objects   = new Varien_Object(array('customer' => $observer->getCustomer()));
			$msg       = $this->_getHelper()->renderMsg($objects, $this->getConfig()->getRegisterMessage());
			$this->_getService()->sendSms($msg, $phoneNumber);
		}
		catch (Exception $e) {
			if ($this->getConfig()->isDebug()) {
				Mage::log($e->getMessage(), null, 'codnitive_paniz.log', true);
			}
		}

		return $this;
	}

	public function loginSendSms(Varien_Event_Observer $observer)
	{
		$phoneNumber = $this->_getCustomerPhoneNumber($observer);
		if (!$this->getConfig()->sendOnLogin() || empty($phoneNumber)) {
			return $this;
		}

		try {
			$objects   = new Varien_Object();
			$objects->setCustomer($observer->getCustomer());
			$msg       = $this->_getHelper()->renderMsg($objects, $this->getConfig()->getLoginMessage());
			$this->_getService()->sendSms($msg, $phoneNumber);
		}
		catch (Exception $e) {
			if ($this->getConfig()->isDebug()) {
				Mage::log($e->getMessage(), null, 'codnitive_paniz.log', true);
			}
		}

		return $this;
	}

	protected function _getCustomerPhoneNumber($observer)
	{
		$billingId = $observer->getCustomer()->getDefaultBilling();
		$address   = Mage::getModel('customer/address')->load($billingId);
		return $address->getTelephone();
	}
}