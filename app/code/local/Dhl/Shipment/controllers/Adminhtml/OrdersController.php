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


        // save file in database
        $model = Mage::getSingleton('dhl_shipment/orders');
        $data = array(
            'file' => 'dhl-so-22-06-16-14-41-56.csv',
            'date' => Mage::getModel('core/date')->date('d-m-Y H:i:s')
        );
        $model->setData($data);

        try{
            $model->save();

            Mage::getSingleton('adminhtml/session')->addSuccess($this->__('El archivo ha sido generado con éxito.'));
            $this->_redirect('*/*/');

            return;
        }catch(Mage_Core_Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }catch(Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($this->__('Ha ocurrido un error al guardar el archivo con SO.'));
        }

        Mage::getSingleton('adminhtml/session')->setOrdersData($data);
        $this->_redirectReferer();
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
