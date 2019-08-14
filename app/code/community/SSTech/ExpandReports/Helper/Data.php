<?php

class SSTech_ExpandReports_Helper_Data extends Mage_Core_Helper_Abstract {

    public function getCustomerTotals($customer) {
        if (!($customer instanceof Mage_Customer_Model_Customer)) {
            $customer = Mage::getModel('customer/customer')->load($customer);
        }
        if(!$customer->getId()){
            return false;
        }
        return Mage::getResourceModel('sales/sale_collection')
                        ->setCustomerFilter($customer)
                        ->load()
                        ->getTotals();
    }
    public function isReturningCustomer($customer) {
        $customerTotals = $this->getCustomerTotals($customer);
        return (bool)($customerTotals && $customerTotals->getNumOrders() > 1);
    }

    public function renderData($renderedValue, $row, $column, $isExport){
        return (int) $renderedValue;
    }

}