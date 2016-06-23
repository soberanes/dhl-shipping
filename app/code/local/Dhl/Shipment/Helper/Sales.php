<?php
/**
 * Helper to create and download SO CSV for Dhl Shipping Orders
 */
class Dhl_Shipment_Helper_Sales extends Mage_Core_Helper_Abstract
{

    /**
     * Generates CSV file with product's list according to the collection in the Sales Order
     * @return array
     */
    public function generateSalesOrderList($list){

        if(!is_null($list)){
            if(count($list) > 0){
                $io = new Varien_Io_File();
                $path = Mage::getBaseDir('var') . DS . 'export' . DS;
                $name = md5(microtime());
                $file = $path . DS . $name . '.csv';

                $io->setAllowCreateFolders(true);
                $io->streamOpen($file, 'w+');
                $io->streamLock(true);
                $io->streamWriteCsv($list);
                $io->streamClose();

                return array(
                    'type'  => 'filename',
                    'value' => $file,
                    'rm'    => true //can delete file after use
                );
            }
        }

    }

}
