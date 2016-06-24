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
    public function generateSalesOrderList($list, $filename){
        if(!is_null($list)){
            if(count($list) > 0){
                $io = new Varien_Io_File();
                $path = Mage::getBaseDir('var') . DS . 'export';
                // $name = md5(microtime());
                $file = $path . DS . $filename;

                $io->setAllowCreateFolders(true);
                $io->open(array('path' => $path));
                $io->streamOpen($file, 'w+');
                $io->streamLock(true);

                foreach ($list as $entry) {
                    $io->streamWriteCsv($entry);
                }

                $io->streamUnlock();
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
