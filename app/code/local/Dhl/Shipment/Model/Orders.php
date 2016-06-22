<?php
/**
 * Model for Dhl Shipment Orders
 */
class Dhl_Shipment_Model_Orders extends Mage_Core_Model_Abstract
{

    function _construct()
    {
        $this->_init('dhl_shipment/orders');
    }
}
