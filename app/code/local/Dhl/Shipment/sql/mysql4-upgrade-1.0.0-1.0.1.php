<?php
$installer = $this;
$installer->startSetup();
$installer->getConnection()
    ->addColumn($installer->getTable('dhl_shipment/orders'), 'qty', array(
            'type' => Varien_Db_Ddl_Table::TYPE_INTEGER
            'nullable' => false
    ), 'Qty');
$installer->endSetup();
