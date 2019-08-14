<?php
class SSTech_ExpandReports_Model_Observer {
    public function coreBlockAbstractPrepareLayoutAfter(Varien_Event_Observer $observer) {
        if (Mage::app()->getRequest()->getControllerName() !== 'report_customer') {
            return;
        }

        $block = $observer->getBlock();
        if ($block->getType() == 'adminhtml/page_head') {
            $block->addCss('expandreports/css/expandreports.css');
        }
        if ($block->getType() == 'adminhtml/report_customer_orders_grid') {
            $this->_addReturningCustomerColumn($block);
        }
    }

    protected function _addReturningCustomerColumn(Mage_Adminhtml_Block_Report_Customer_Orders_Grid $block) {
      
        $helper = Mage::helper('expandreports');

        $block->setSubReportSize(null);
        $block->addColumnAfter('new_customer', array(
            'header' => Mage::helper('customer')->__('New Customer'),
            'width' => '100',
            'filter' => false,
            'sortable' => false,
            'total' => 'sum',
            'type' => 'number',
            'index' => 'new_customer',
            'inline_css' => 'new_customer',
            'frame_callback' => array($helper, 'renderData'),
                ), 'name');

        $block->addColumnAfter('returning_customer', array(
            'header' => Mage::helper('customer')->__('Returning Customer'),
            'width' => '100',
            'filter' => false,
            'sortable' => false,
            'total' => 'sum',
            'type' => 'number',
            'index' => 'returning_customer',
            'inline_css' => 'returning_customer',
            'frame_callback' => array($helper, 'renderData'),
                ), 'new_customer');

        $block->sortColumnsByOrder();
    }

}