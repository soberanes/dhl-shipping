<?php
/**
 * @var $installer Mage_Core_Model_Resource_Setup
 */
$installer = $this;
$installer->startSetup();

/**
 * Create table 'dhl_shipment_orders'
 */
$table = $installer->getConnection()
// The following call to getTable('dhl_shipment/orders') will lookup the resource for dhl_shipment (dhl_shipment_mysql4),
// and look for a corresponding entity called orders. The table name in the XML is dhl_shipment_orders,
// so ths is what is created.
    ->newTable($installer->getTable('dhl_shipment/orders'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'identity' => true,
        'unsigned' => true,
        'nullable' => false,
        'primary'  => true,
    ), 'ID')
    ->addColumn('file', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, array(
        'nullable' => false
    ), 'File')
    ->addColumn('date', Varien_Db_Ddl_Table::TYPE_DATE, null, array(
        'nullable' => false
    ), 'Date');
$installer ->getConnection()->cretaeTable($table);

$installer->endSetup();
