<?php
/**
 * View/Edit SO for Dhl Shipment Orders
 */
class Dhl_Shipment_Block_Adminhtml_Orders_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    function __construct()
    {
        $this->_blockGroup = 'dhl_shipment';
        $this->_controller = 'adminhtml_orders';

        parent::__construct();

        $this->_updateButton('save', 'label', $this->__('Save Order'));
        $this->_updateButton('delete', 'label', $this->__('Delete Order'));
    }

    /**
     * Get Header text
     *
     * @return string
     */
    public function getHeaderText(){
        if(Mage::registry('dhl_shipment')->getId()){
            return $this->__('Edit Order');
        }else{
            return $this->__('New Order');
        }
    }
}
