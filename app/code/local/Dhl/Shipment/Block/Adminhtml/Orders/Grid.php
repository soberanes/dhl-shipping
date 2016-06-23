<?php
/**
 * Grid for admin panel Dhl Shipment Orders
 */
class Dhl_Shipment_Block_Adminhtml_Orders_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    function __construct()
    {
        parent::__construct();

        // Set some defaults for our grid
        $this->setDefaultSort('id');
        $this->setId('dhl_shipment_orders_grid');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);
    }

    protected function _getCollectionClass(){
        // Return the model we are using for the grid
        return 'dhl_shipment/orders_collection';
    }

    protected function _prepareCollection(){
        // Get and set our collection for the grid
        $collection = Mage::getResourceModel($this->_getCollectionClass());
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns(){
        // Add the columns that should appear in the grid

        $this->addColumn('id', array(
            'header' => $this->__('ID'),
            'align'  => 'right',
            'width'  => '50px',
            'index'  => 'id'
        ));

        $this->addColumn('file', array(
            'header' => $this->__('SO File'),
            'index'  => 'file'
        ));

        $this->addColumn('qty', array(
            'header' => $this->__('Qty'),
            'index'  => 'qty'
        ));

        $this->addColumn('date', array(
            'header' => $this->__('Date'),
            'index'  => 'date'
        ));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        // This is where our row data will link to
        return $this->getUrl('*/*/download', array('id' => $row->getId()));
    }
}
