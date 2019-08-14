<?php

class SSTech_ExpandReports_Model_Resource_Customer_Orders_Collection extends Mage_Reports_Model_Resource_Customer_Orders_Collection {

    protected function _joinFields($from = '', $to = '') {
        $orderTable = Mage::getResourceModel('sales/order_collection')
                ->addAttributeToFilter('created_at', array('to' => $from, 'datetime' => true));
        $orderTable->getSelect()->where('order_table.customer_id = main_table.customer_id');
        $tbl_from = $orderTable->getSelect()->getPart(Zend_Db_Select::FROM);
        $tbl_from['order_table'] = $tbl_from['main_table'];
        unset($tbl_from['main_table']);
        $orderTable->getSelect()->setPart(Zend_Db_Select::FROM, $tbl_from);
       $countSelect = $orderTable->getSelectCountSql()->reset(Zend_Db_Select::COLUMNS)->columns('(COUNT(*) >= 1)');
        $countSelect = $orderTable->getSelectCountSql();
        $this->joinCustomerName()
                ->groupByCustomer()
                ->addOrdersCount()
                ->addAttributeToFilter('created_at', array('from' => $from, 'to' => $to, 'datetime' => true));

        $this->getSelect()->columns(array('returning_customer' => new Zend_Db_Expr ('(' . $countSelect->reset(Zend_Db_Select::COLUMNS)->columns('(COUNT(*) >= 1)') . ')')));
        $this->getSelect()->columns(array('new_customer' => new Zend_Db_Expr ('(' . $countSelect->reset(Zend_Db_Select::COLUMNS)->columns('(COUNT(*) = 0)') . ')')));
        return $this;
    }

}
