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

class Codnitive_Paniz_Model_Service
{
	const SOAP_CLIENT_URL = 'http://87.107.121.54/post/Send.asmx?wsdl';

	protected function _getConfig()
	{
		return Mage::getModel('paniz/config');
	}

	protected function _getUsername()
	{
		return $this->_getConfig()->getUsername();
	}

	protected function _getPassword()
	{
		return $this->_getConfig()->getPassword();
	}

	protected function _connect()
	{
		try {
			ini_set("soap.wsdl_cache_enabled", "0");
			$client = new SoapClient(self::SOAP_CLIENT_URL);

			if (!$client) {
				Mage::log($client, null, 'codnitive_paniz.log', true);
				Mage::throwException($client);
				return false;
			}

			return $client;
		}
		catch (Mage_Core_Exception $e) {
			Mage::log($e->getMessage(), null, 'codnitive_paniz.log', true);
			return false;
		}
		catch (Exception $e){
			Mage::log($e->getMessage(), null, 'codnitive_paniz.log', true);
			Mage::logException($e);
			return false;
		}
	}

	public function getCredit()
	{
		$parameters = array();
		$parameters['username'] = $this->_getUsername();
		$parameters['password'] = $this->_getPassword();

		$client = $this->_connect();
		$method = $client->GetCredit($parameters);

		if ($this->_getConfig()->isDebug()) {
			Mage::log('Get credit result: ' . $method->GetCreditResult, null, 'codnitive_paniz.log', true);
		}

		return $method->GetCreditResult;
	}

	public function sendSms($message, $cellNumber, $sendAdmin = false)
	{
		$parameters = array();
		$parameters['username'] = $this->_getUsername();
		$parameters['password'] = $this->_getPassword();
		$parameters['from']     = $this->_getConfig()->getSenderNumber();
		$parameters['isflash']  = $this->_getConfig()->isFlash();
		$parameters['text']     = $message;
		$parameters['to']       = $cellNumber;

		$client = $this->_connect();
		$method = $client->SendSimpleSMS2($parameters);

		if ($sendAdmin) {
			$parameters['to'] = $this->_getConfig()->getAdminNumber();
			$client->SendSimpleSMS2($parameters);
		}

		if ($this->_getConfig()->isDebug()) {
			Mage::log('Send SMS result: ' . $method->SendSimpleSMS2Result, null, 'codnitive_paniz.log', true);
		}

		return $method->SendSimpleSMS2Result;
	}

}
