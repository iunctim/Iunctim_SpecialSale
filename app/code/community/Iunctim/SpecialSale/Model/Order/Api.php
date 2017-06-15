<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_Sales
 * @copyright  Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Order API
 *
 * @category   Mage
 * @package    Mage_Sales
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Iunctim_SpecialSale_Model_Order_Api extends Mage_Sales_Model_Order_Api
{
  /**
   * Retrieve full order information
   *
   * @param string $orderIncrementId
   * @return array
   */
  public function info($orderIncrementId)
  {
    $order = $this->_initOrder($orderIncrementId);

    if ($order->getGiftMessageId() > 0) {
      $order->setGiftMessage(
          Mage::getSingleton('giftmessage/message')->load($order->getGiftMessageId())->getMessage()
          );
    }

    $result = $this->_getAttributes($order, 'order');

    $result['shipping_address'] = $this->_getAttributes($order->getShippingAddress(), 'order_address');
    $result['billing_address']  = $this->_getAttributes($order->getBillingAddress(), 'order_address');
    $result['items'] = array();

    foreach ($order->getAllItems() as $item) {
      if ($item->getGiftMessageId() > 0) {
        $item->setGiftMessage(
            Mage::getSingleton('giftmessage/message')->load($item->getGiftMessageId())->getMessage()
            );
      }

      $attributes = $this->_getAttributes($item, 'order_item');
      $attributes['product_original_price'] = $item->getProduct()->getPrice();
      $result['items'][] = $attributes; 
    }

    $result['payment'] = $this->_getAttributes($order->getPayment(), 'order_payment');

    $result['status_history'] = array();

    foreach ($order->getAllStatusHistory() as $history) {
      $result['status_history'][] = $this->_getAttributes($history, 'order_status_history');
    }

    return $result;
  }
} // Class Mage_Sales_Model_Order_Api End

