<?php
/**
 * Webhook subscription Options Status
 *
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
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Magento
 * @package     Magento_Webhook
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Magento\Webhook\Model\Subscription\Options;

class Status implements \Magento\Core\Model\Option\ArrayInterface
{

    /**
     * @var \Magento\Core\Model\Translate
     */
    protected $_translator;

    /**
     * @param \Magento\Core\Model\Translate $translator
     */
    public function __construct(\Magento\Core\Model\Translate $translator)
    {
        $this->_translator = $translator;
    }

    /**
     * Return statuses array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            \Magento\Webhook\Model\Subscription::STATUS_ACTIVE => __('Active'),
            \Magento\Webhook\Model\Subscription::STATUS_REVOKED => __('Revoked'),
            \Magento\Webhook\Model\Subscription::STATUS_INACTIVE => __('Inactive'),
        );
    }
}
