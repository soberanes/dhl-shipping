<?php
/**
 * Controller for Dhl Shipment Orders
 */
class Dhl_Shipment_Adminhtml_OrdersController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction(){
        // Let's call our initAction method which will set some basic params for each action
        $this->_initAction()
            ->renderLayout();
    }

    public function newAction(){
        // Generate file here
    }

    public function saveAction()
    {
        // Save data in row
    }

    public function messageAction(){
        $data = Mage::getModel('dhl_shipment/orders')->load($this->getRequest()->getParam('id'));
        echo $data->getContent();
    }

    /**
     * Initialize action
     *
     * Here, we set the breadcrumbs and the active menu
     *
     * @return Mage_Adminhtml_Controller_Action
     */
    protected function _initAction(){
        $this->loadLayout()
            // Make the active menu match the menu config nodes (whitout 'children' inbetwwen)
            ->_setActiveMenu('sales/dhl_shipment_orders')
            ->_title($this->__('Sales'))->_title($this->__('Orders'))
            ->_addBreadcrumb($this->__('Sales'), $this->__('Sales'))
            ->_addBreadcrumb($this->__('Orders'), $this->__('Orders'));

        return $this;
    }

    /**
     * Check currently called action by permissions for current user
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('sales/dhl_shipment_orders');
    }

}
