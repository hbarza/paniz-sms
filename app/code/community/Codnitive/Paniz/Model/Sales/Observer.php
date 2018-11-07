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

class Codnitive_Paniz_Model_Sales_Observer extends Mage_Core_Model_Abstract
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

	public function orderSendSms(Varien_Event_Observer $observer)
	{
		if (!$this->getConfig()->sendOnRegister()) {
			return $this;
		}

		$incrementId = Mage::getSingleton('checkout/session')->getLastRealOrderId();
		$order       = Mage::getModel('sales/order')->loadByIncrementId($incrementId);
		$telephone   = $order->getBillingAddress()->getTelephone();

		if (empty($telephone)) {
			return $this;
		}

		try {
			$objects   = new Varien_Object();
			$customer  = Mage::getModel('customer/customer')->load($order->getCustomerId());
			$objects->setCustomer($customer);
			$objects->setOrder($order);
			$msg       = $this->_getHelper()->renderMsg($objects, $this->getConfig()->getOrderMessage());
			$this->_getService()->sendSms($msg, $telephone, $this->getConfig()->sendAdminOnOrderPlace());
		}
		catch (Exception $e) {
			if ($this->getConfig()->isDebug()) {
				Mage::log($e->getMessage(), null, 'codnitive_paniz.log', true);
			}
		}

		return $this;
	}
}