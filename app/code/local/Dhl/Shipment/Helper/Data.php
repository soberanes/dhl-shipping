<?php
/**
 *  Helper class to DHL Shipment Orders
 */
class Dhl_Shipment_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Changing the order status
     *
     * @return void
     */
    public function changeOrderStatus($order){
        $state   = 'new';
        $status  = 'dhl_processed';
        $comment = 'Se cambió el status porque se envió a DHL.';
        $isCustomerNotified = false;
        $order->setState($state, $status, $comment, $isCustomerNotified);
        $order->save();
    }

    /**
     * Getting orders from Magento with status shipping
     *
     * @return Object
     */
    public function getOrders(){
        $order_collection = Mage::getModel('sales/order')
                                ->getCollection()
                                ->addAttributeToSelect('*')
                                ->addAttributeToFilter('status', 'shipping');
        return $order_collection;
    }

    /**
     * Getting row from SO
     *
     * @return Array
     */
    public function getFileRow($order){

        $deliveryCode = 48;
        $deliveryCarrier = 'DHLX';

        $billingAddressData  = $order->getBillingAddress()->getData();
        $shippingAddressData = $order->getShippingAddress()->getData();

        $billingAddress  = explode("\n", $billingAddressData['street']);
        $shippingAddress = explode("\n", $shippingAddressData['street']);

        $row = array(
            '5116707',
            'SO',
            $order->getRealOrderId(),
            date('m/d/Y', Mage::getModel('core/date')->timestamp($order->getCreatedAt())),
            'B',
            $billingAddress[0],
            $billingAddress[1],
            $billingAddressData['city'],
            substr($billingAddressData['region'], 0, 2),
            str_pad($billingAddressData['postcode'], 5, "0", STR_PAD_LEFT),
            $billingAddressData['country_id'],
            $billingAddressData['telephone'],
            $billingAddressData['email'],
            'S',
            $shippingAddress[0],
            $shippingAddress[1],
            $shippingAddressData['city'],
            substr($shippingAddressData['region'], 0, 2),
            str_pad($shippingAddressData['postcode'], 5, "0", STR_PAD_LEFT),
            $shippingAddressData['country_id'],
            $shippingAddressData['telephone'],
            'PP',
            '',
            'R',
            $deliveryCode,
            $deliveryCarrier,
            $billingAddressData['firstname'],
            $billingAddressData['lastname'],
            $shippingAddressData['firstname'],
            $shippingAddressData['lastname'],
            'N',
            'N',
            '',
            'LINE'
        );

        return $row;
    }

    /**
     * Getting SKU white list
     *
     * @return Array
     */
    public function getWhiteList(){
        $white_list = array(
            'DJI-IP1-0003','DJI-IP1-0005','DJI-IP1-0006','DJI-IP1-0007','DJI-IP1-0010',
            'DJI-IP1-0012','DJI-IP1-0013','DJI-IP1-0014','DJI-IP1-0018','DJI-IP1-0025',
            'DJI-IP1-0026','DJI-IP1-0029','DJI-IP1-0030','DJI-IP1-0031','DJI-IP1-0032',
            'DJI-IP1-0033','DJI-MTC-0001','DJI-MTC-0010','DJI-MTC-0011','DJI-MTC-0012',
            'DJI-MTC-0013','DJI-MTC-0013','DJI-OSM-0010','DJI-OSM-0011','DJI-OSM-0014',
            'DJI-OSM-0016','DJI-OSM-0017','DJI-OSM-0018','DJI-OSM-0019','DJI-OSM-0020',
            'DJI-OSM-0021','DJI-OSM-0022','DJI-OSM-0023','DJI-P2V-0010','DJI-P2V-0015',
            'DJI-P34-0001','DJI-P3A-0001','DJI-P3A-0003','DJI-P3P-0001','DJI-P3P-0002',
            'DJI-P3P-0003','DJI-P3S-0001','DJI-P3S-0002','DJI-P3X-0011','DJI-P3X-0013',
            'DJI-P3X-0014','DJI-P3X-0016','DJI-P3X-0017','DJI-P3X-0018','DJI-P3X-0018',
            'DJI-P3X-0019','DJI-P3X-0020','DJI-P3X-0021','DJI-P3X-0022','DJI-P3X-0024',
            'DJI-P3X-0025','DJI-P3X-0026','DJI-P3X-0027','DJI-P3X-0028','DJI-P3X-0029',
            'DJI-P3X-0032','DJI-P3X-0036','DJI-P3X-0037','DJI-P3X-0038','DJI-P3X-0041',
            'DJI-P3X-0041','DJI-P3X-0043','DJI-P3X-0044','DJI-P3X-0046','DJI-P3X-0050',
            'DJI-P3X-0051','DJI-P3X-0055','DJI-P3X-0056','DJI-P3X-0057','DJI-P3X-0058',
            'DJI-P3X-0059','DJI-P3X-0060','DJI-P3X-0061','DJI-P3X-0062','DJI-P3X-0063',
            'DJI-P3X-0064','DJI-P3X-0065','DJI-P3X-0066','DJI-P3X-0067','DJI-P3X-0067',
            'DJI-P3X-0068','DJI-P4-0001','DJI-P4-0010','DJI-P4-0011','DJI-P4-0012',
            'DJI-P4-0013','DJI-P4-0014','DJI-P4-0015','DJI-P4-0016','DJI-P4-0017',
            'DJI-P4-0018','DJI-P4-0019','DJI-PXX-0010','DJI-PXX-0011','DJI-RON-0001',
            'DJI-RON-0010','DJI-RON-0011','DJI-ZEN-0011','HUB-107C-0001','HUB-107C-0002',
            'HUB-107C-0003','HUB-107C-0004','HUB-107D-0001','HUB-107L-0001','HUB-H111-0001'
        );

        return $white_list;
    }
    /**
     * Getting file headers to csv
     *
     * @return Array
     */
    public function getFileHeaders(){
        $headers = array(
            'OrgID','SO','OrderNo','OrderDate','Billing Address','Address Line 1',
            'Address Line 2','City','State','ZipCode','Country','Phone','Email',
            'Shipping Address','Address Line 1','Address Line 2','City','State',
            'ZipCode','Country','Phone','FreightTerms','FreightPaymentType',
            'CustomerType','CarrierServiceCode','SCAC','BillToFirstName',
            'BillToLastName','ShipToFirstName','ShipToLastName','Reship Indicator',
            'CustomerEmailNotification','SellerOrganizationCode','LINE',
            'LineNo1','ItemID1','Quantity1','Gift Message1','Unit Price1',
            'LineNo2','ItemID2','Quantity2','Gift Message2','Unit Price2',
            'LineNo3','ItemID3','Quantity3','Gift Message3','Unit Price3',
            'LineNo4','ItemID4','Quantity4','Gift Message4','Unit Price4',
            'LineNo5','ItemID5','Quantity5','Gift Message5','Unit Price5',
            'LineNo6','ItemID6','Quantity6','Gift Message6','Unit Price6',
            'LineNo7','ItemID7','Quantity7','Gift Message7','Unit Price7',
            'LineNo8','ItemID8','Quantity8','Gift Message8','Unit Price8',
            'LineNo9','ItemID9','Quantity9','Gift Message9','Unit Price9',
            'LineNo10','ItemID10','Quantity10','Gift Message10','Unit Price10'
        );

        return $headers;
    }



}
