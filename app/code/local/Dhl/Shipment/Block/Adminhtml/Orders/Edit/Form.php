<?php
/**
 * Form to edit order for Dhl Shipment Orders
 */
class Dhl_Shipment_Block_Adminhtml_Orders_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

    function __construct()
    {
        parent::__construct();
        $this->setId('dhl_shipment_orders_form');
        $this->setTitle($this->__('Order Information'));
    }

    /**
     * Setup form fields for inserts/updates
     *
     * return Mage_Adminhtml_Block_Widget_Form
     */
    public function _prepareForm(){
        $model = Mage::registry('dhl_shipment');

        $form = new Varien_Data_Form(array(
            'id'     => 'edit_form',
            'action' => $this->getUrl('*/*/save'), array('id' => $this->getRequest()->getParam('id')),
            'method' => 'post'
        ));

        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend' => Mage::helper('checkout')->__('Order Information'),
            'class'  => 'fieldset-wide'
        ));

        if($model->getId()){
            $fieldset->addField('id', 'hidden', array(
                'name' => 'id',
            ));
        }

        $fieldset->addField('file', 'text', array(
            'name'      => 'file',
            'label'     => Mage::helper('checkout')->__('File'),
            'title'     => Mage::helper('checkout')->__('File'),
            'required'  => true,
        ));

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
