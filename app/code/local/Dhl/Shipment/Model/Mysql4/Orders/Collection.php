<?php
/**
 * Collection for Dhl Shipment Orders
 */
class Dhl_Shipment_Model_Mysql4_Orders_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{

    function _construct()
    {
        $this->_init('dhl_shipment/orders');
    }
}
