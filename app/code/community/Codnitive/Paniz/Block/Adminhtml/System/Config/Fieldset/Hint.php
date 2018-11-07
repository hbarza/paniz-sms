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


/**
 * Renderer for Paniz account credit in System Configuration
 *
 * @category   Codnitive
 * @package    Codnitive_Paniz
 * @author     Hassan Barza <support@codnitive.com>
 */
class Codnitive_Paniz_Block_Adminhtml_System_Config_Fieldset_Hint
	extends Mage_Adminhtml_Block_Abstract
	implements Varien_Data_Form_Element_Renderer_Interface
{

	public function render(Varien_Data_Form_Element_Abstract $element)
	{
		$config   = Mage::getModel('paniz/config');
		$helper   = Mage::helper('paniz');
		$username = $config->getUsername();
		$password = $config->getPassword();

		if (empty($username) || empty($password)) {
			$msg = Mage::helper('paniz')->__('Please enter your account username and password to view yout credit.');
		}
		else {
			$credit = Mage::getModel('paniz/service')->getCredit();
			$credit = $helper->formatTotal($credit, true, false) . ' SMS';
			$msg = $helper->__('Credit: you have %s in your account.', $credit, false);
		}

		$html = <<<HTML
<div class="paypal-payment-notice">
$msg
</div>
HTML;
		return $html;
	}
}
