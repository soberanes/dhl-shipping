<?php
/**
 * Resource Mysql4 for Dhl Shipment Orders
 */
class Dhl_Shipment_Model_Mysql4_Orders extends Mage_Core_Model_Mysql4_Abstract
{

    function _construct()
    {
        $this->_init('dhl_shipment/orders', 'id');
    }
}
