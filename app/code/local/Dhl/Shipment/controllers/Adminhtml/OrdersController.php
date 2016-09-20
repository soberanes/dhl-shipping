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
        $model  = Mage::getModel('dhl_shipment/orders');
        $helper = Mage::helper('dhl_shipment/data');

        $entries = array();
        $lightcone_entries = array();
        $headers = $helper->getFileHeaders();
        array_push($entries, $headers);

        $orders = $helper->getOrders();

        $white_list = $helper->getWhiteList();
        echo "<pre>";
        foreach ($orders as $order) {

            $dhl    = 0;
            $lgc    = 0;
            $line   = 0;
            $so_qty = 0;

            $order_row = $helper->getFileRow($order);
            $lightcone_order_row = $order_row;

            foreach ($order->getItemsCollection() as $key => $item) {

                $product = $item->getData();

                if(!in_array($product['sku'], $white_list)){
                    array_push($lightcone_order_row, $key+1); //LineNo from 1
                    array_push($lightcone_order_row, $product['sku']); //ItemD
                    array_push($lightcone_order_row, intval($product['qty_ordered'])); //Quantity
                    array_push($lightcone_order_row, ''); //Gift Message
                    array_push($lightcone_order_row, 0); //Unit Price
                    $dhl = 2;
                }else{
                    $line++;
                    array_push($order_row, $line); //LineNo from 1
                    array_push($order_row, $product['sku']); //ItemD
                    array_push($order_row, intval($product['qty_ordered'])); //Quantity
                    array_push($order_row, ''); //Gift Message
                    array_push($order_row, 0); //Unit Price
                    $dhl = 1;
                }
            }

            if($dhl == 1){
                array_push($entries, $order_row);
                //cambiar el status del pedido
                // $helper->changeOrderStatus($order);
            }

        }
        die;

        // crear archivo csv aquí - $entries
        // sacar el nombre por el ID
        $filename = 'dhl-SO-' . Mage::getModel('core/date')->date('m-d-Y-H-i-s') . '.csv';
        $model = Mage::getSingleton('dhl_shipment/orders');

        $so_data = array(
            'file' => $filename,
            'qty'  => count($entries) - 1,
            'date' => Mage::getModel('core/date')->date('Y-m-d H:i:s'),
        );
        $model->setData($so_data);

        try{
            $model->save();
        }catch(Mage_Core_Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }catch(Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($this->__('Ha ocurrido un error al guardar el archivo con SO.'));
        }

        $content = Mage::helper('dhl_shipment/sales')->generateSalesOrderList($entries, $filename);

        // $this->wonprepareDownloadResponse($filename, $content);
        // $this->_prepareDownloadResponse($filename, $content);

        Mage::getSingleton('adminhtml/session')->addSuccess($this->__('El archivo CSV se ha generado con éxito.'));
        $this->_redirect('*/*/');
        return;
    }

    public function downloadAction(){
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('dhl_shipment/orders');

        if($id){
            $model->load($id);
            if(!$model->getId()){
                Mage::getSingleton('adminhtml/session')->addError($this->__('Esta SO no existe.'));
                $this->_redirect('*/*/');

                return;
            }

            $filename = $model->getFile();
            $file = Mage::getBaseDir('var') . DS . 'export' . DS . $filename;

            $fileContent = file_get_contents($file);
            if($fileContent){
                $this->getResponse()->setHttpResponseCode(200)
                    ->setHeader('Content-Type', 'application/csv')
                    ->setHeader('Content-Disposition', 'attachment; filename="'.$filename.'"', true)
                    ->setBody($fileContent);
                return;
            }

        }

    }

    public function editAction(){
        $this->_initAction();

        // Get id if available
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('dhl_shipment/orders');

        if($id){
            // Load record
            $model->load($id);

            // Check if record is loaded
            if(!$model->getId()){
                Mage::getSingleton('adminhtml/session')->addError($this->__('Esta SO no existe.'));
                $this->_redirect('*/*/');

                return;
            }
        }

        $this->_title($model->getId() ? $model->getFile() : $this->__('Nueva SO'));

        $data = Mage::getSingleton('adminhtml/session')->getOrdersData(true);
        if(!empty($data)){
            $model->setData($data);
        }

        Mage::register('dhl_shipment', $model);

        $this->_initAction()
            ->_addBreadcrumb($id ? $this->__('Editar SO') : $this->__('Nueva SO'), $id ? $this->__('Editar SO') : $this->__('Nueva SO'))
            ->_addContent($this->getLayout()->createBlock('dhl_shipment/adminhtml_orders_edit')->setData('action', $this->getUrl('*/*/save')))
            ->renderLayout();
    }

    public function saveAction(){

        if($postData = $this->getRequest()->getPost()){
            // Save data in database
            $model = Mage::getSingleton('dhl_shipment/orders');

            if(empty($postData['file'])){
                $postData['file'] = 'dhl-SO-' . Mage::getModel('core/date')->date('m-d-Y-H-i-s');
            }else{
                $postData['file'] = preg_replace('/\s+/', '', $postData['file']);
            }

            $model->setData($postData);

            try{
                $model->save();

                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('El archivo ha sido generado con éxito.'));
                $this->_redirect('*/*/config');

                return;
            }catch(Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }catch(Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($this->__('Ha ocurrido un error al guardar el archivo con SO.'));
            }

            Mage::getSingleton('adminhtml/session')->setOrdersData($postData);
            $this->_redirect('*/*/');
            return;
        }
    }

    public function deleteAction(){
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('dhl_shipment/orders');

        if($id){
            try{
                $model->setId($id)->delete();
                Mage::getSingleton('adminhtml/session')->addError($this->__('La SO ha sido eliminada.'));
                $this->_redirect('*/*/');

                return;
            }catch(Exception $e){

            }

        }
    }

    public function messageAction(){
        $data = Mage::getModel('dhl_shipment/orders')->load($this->getRequest()->getParam('id'));
        echo $data->getContent();
    }

    private function wonprepareDownloadResponse(
        $fileName,
        $content,
        $contentType = 'application/octet-stream',
        $contentLength = null)
    {
        $session = Mage::getSingleton('admin/session');
        if ($session->isFirstPageAfterLogin()) {
            $this->_redirect($session->getUser()->getStartupPageUrl());
            return $this;
        }
        $isFile = false;
        $file   = null;
        if (is_array($content)) {
            if (!isset($content['type']) || !isset($content['value'])) {
                return $this;
            }
            if ($content['type'] == 'filename') {
                $isFile         = true;
                $file           = $content['value'];
                $contentLength  = filesize($file);
            }
        }
        $this->getResponse()
            ->setHttpResponseCode(200)
            ->setHeader('Pragma', 'public', true)
            ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
            ->setHeader('Content-type', $contentType, true)
            ->setHeader('Content-Length', is_null($contentLength) ? strlen($content) : $contentLength, true)
            ->setHeader('Content-Disposition', 'attachment; filename="'.$fileName.'"', true)
            ->setHeader('Last-Modified', date('r'), true);
        if (!is_null($content)) {
            if ($isFile) {
                $this->getResponse()->clearBody();
                $this->getResponse()->sendHeaders();
                $ioAdapter = new Varien_Io_File();
                $ioAdapter->open(array('path' => $ioAdapter->dirname($file)));
                $ioAdapter->streamOpen($file, 'r');
                while ($buffer = $ioAdapter->streamRead()) {
                    print $buffer;
                }
                $ioAdapter->streamClose();
                if (!empty($content['rm'])) {
                    $ioAdapter->rm($file);
                }
                exit(0);
            } else {
                $this->getResponse()->setBody($content);
            }
        }
        return $this;
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
