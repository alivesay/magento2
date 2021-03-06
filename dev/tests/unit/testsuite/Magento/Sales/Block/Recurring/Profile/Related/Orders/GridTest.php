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
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Magento
 * @package     Magento_Sales
 * @subpackage  unit_tests
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Magento\Sales\Block\Recurring\Profile\Related\Orders;

/**
 * Test class for \Magento\Sales\Block\Recurring\Profile\Related\Orders\Grid
 */
class GridTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\TestFramework\Helper\ObjectManager
     */
    protected $_objectManagerHelper;

    protected function setUp()
    {
        $this->_objectManagerHelper = new \Magento\TestFramework\Helper\ObjectManager($this);
    }

    public function testPrepareLayout()
    {
        $customer = $this->getMock('Magento\Customer\Model\Customer', array(), array(), '', false);
        $customer->expects($this->once())->method('getId')->will($this->returnValue(1));
        $store = $this->getMock('Magento\Core\Model\Store', array(), array(), '', false);
        $args = array(
            'getIncrementId', 'getCreatedAt', 'getCustomerName', 'getBaseGrandTotal', 'getStatusLabel', 'getId'
        );
        $collectionElement = $this->getMock('Magento\Sales\Model\Recurring\Profile', $args, array(), '', false);
        $collectionElement->expects($this->once())->method('getIncrementId')
            ->will($this->returnValue(1));
        $collection = $this->getMock('Magento\Sales\Model\Resource\Order\Collection', array(), array(), '', false);
        $collection->expects($this->any())->method('addFieldToFilter')
            ->will($this->returnValue($collection));
        $collection->expects($this->once())->method('addFieldToSelect')
            ->will($this->returnValue($collection));
        $collection->expects($this->once())->method('addRecurringProfilesFilter')
            ->will($this->returnValue($collection));
        $collection->expects($this->once())->method('setOrder')
            ->will($this->returnValue($collection));
        $collection->expects($this->once())->method('getIterator')
            ->will($this->returnValue(new \ArrayIterator(array($collectionElement))));
        $profile = $this->getMock('Magento\Sales\Model\Recurring\Profile', array(), array(), '', false);
        $registry = $this->getMock('Magento\Core\Model\Registry', array(), array(), '', false);
        $registry->expects($this->at(0))
            ->method('registry')
            ->with('current_recurring_profile')
            ->will($this->returnValue($profile));
        $registry->expects($this->at(1))
            ->method('registry')
            ->with('current_customer')
            ->will($this->returnValue($customer));
        $profile->expects($this->once())->method('setStore')->with($store)->will($this->returnValue($profile));
        $profile->expects($this->once())->method('setLocale')->will($this->returnValue($profile));
        $storeManager = $this->getMock('Magento\Core\Model\StoreManagerInterface');
        $storeManager->expects($this->once())->method('getStore')
            ->will($this->returnValue($store));
        $locale = $this->getMock('\Magento\Core\Model\LocaleInterface');
        $locale->expects($this->once())->method('formatDate')
            ->will($this->returnValue('11-11-1999'));
        $helperFactory = $this->getMock('Magento\App\Helper\HelperFactory', array(), array(), '', false);
        $helper = $this->getMock('Magento\Core\Helper\Data', array(), array(), '', false);
        $helperFactory->expects($this->any())->method('get')->will($this->returnValue($helper));
        $helper->expects($this->once())->method('formatCurrency')
            ->will($this->returnValue('10 USD'));
        $block = $this->_objectManagerHelper->getObject(
            'Magento\Sales\Block\Recurring\Profile\Related\Orders\Grid',
            array(
                'registry' => $registry,
                'storeManager' => $storeManager,
                'collection' => $collection,
                'locale' => $locale,
                'helperFactory' => $helperFactory
            )
        );
        $pagerBlock = $this->getMockBuilder('Magento\Page\Block\Html\Pager')
            ->disableOriginalConstructor()
            ->setMethods(array('setCollection'))
            ->getMock();
        $pagerBlock->expects($this->once())->method('setCollection')
            ->with($collection)
            ->will($this->returnValue($pagerBlock));
        $layout = $this->getMock('Magento\View\LayoutInterface');
        $layout->expects($this->once())->method('createBlock')
            ->will($this->returnValue($pagerBlock));
        $block->setLayout($layout);

        /**
         * @var \Magento\Sales\Block\Recurring\Profile\Related\Orders\Grid
         */
        $this->assertNotEmpty($block->getGridColumns());
        $expectedResult = array(new \Magento\Object(array(
            'increment_id' => 1,
            'increment_id_link_url' => null,
            'created_at'  => '11-11-1999',
            'customer_name' => null,
            'status' => null,
            'base_grand_total' => '10 USD'
        )));
        $this->assertEquals($expectedResult, $block->getGridElements());
    }
}
