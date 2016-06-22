<?php
/**
 * Block for Dhl Shipment Orders
 */
class Dhl_Shipment_Block_Adminhtml_Orders extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    function __construct()
    {
        // The blockGroup must match the first half of how we call the block, and controller matches the second half
        // ie. dhl_shipment/adminhtml_orders
        $this->_blockGroup = 'dhl_shipment';
        $this->_controller = 'adminhtml_orders';
        $this->_headerText = $this->__('Orders');

        parent::__construct();
    }
}
