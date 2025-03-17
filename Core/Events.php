<?php

namespace Novalnet\Core;

use OxidEsales\Eshop\Application\Model\Payment;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Registry;

/**
 * Class Events.
 */
class Events
{

    /**
     * Method to be called when the plugin is activated
     */
    public static function onActivate(): void
    {
        self::createNovalnetTables();
        self::addNovalnetPayments();
    }

    /**
     * Method to be called when the plugin is deactivated
     */
    public function deactivate()
    {
        $this->dropCustomTables();
    }
  public static function createNovalnetTables(){
    $db = DatabaseProvider::getDb();
    $sql = '
        CREATE TABLE IF NOT EXISTS `novalnet_transaction_details` (
                `ID` INT(11) NOT NULL AUTO_INCREMENT,
                `TID` bigint(20) COMMENT "Novalnet transaction reference ID",
                `PAYMENT_TYPE` varchar(225) COMMENT "Executed payment type of this order",
                `ORDER_NO` int(11) unsigned COMMENT "Order ID from shop",
                `AMOUNT` int(11) DEFAULT "0" COMMENT "Transaction amount",
                `CURRENCY` varchar(50) DEFAULT NULL COMMENT "Transaction currency",
                `PAID_AMOUNT` int(11) DEFAULT "0" COMMENT "Paid amount",
                `REFUND_AMOUNT` int(11) DEFAULT "0" COMMENT "Refund amount",
                `GATEWAY_STATUS` varchar(30) NULL COMMENT "Novalnet transaction status",
                `ADDITIONAL_DATA` TEXT DEFAULT NULL COMMENT "Stored Novalnet bank account details",
                `TOKEN_INFO` VARCHAR(255) DEFAULT NULL COMMENT "Transaction Token",
                PRIMARY KEY (`id`),
                KEY TID (TID),
                KEY ORDER_NO (ORDER_NO)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ';
    $db->execute($sql);
  }
  public static function addNovalnetPayments() {
       $aPayments = [
            'novalnetinvoice'  => [
                'OXID'          => 'novalnetinvoice',
                'OXDESC_DE'     => 'Rechnung',
                'OXDESC_EN'     => 'Invoice',
                'OXLONGDESC_DE' => ' Sie erhalten eine E-Mail mit den Bankdaten von Novalnet, um die Zahlung abzuschließen ',
                'OXLONGDESC_EN' => 'You will receive an e-mail with the Novalnet account details to complete the payment'
                'OXSORT'        => '1'
            ],

            'novalnetpaypal'    => [
              'OXID'     => 'novalnetpaypal',
              'OXDESC_DE'     => 'PayPal',
              'OXDESC_EN'     => 'PayPal',
              'OXLONGDESC_DE' => ' Sie werden zu PayPal weitergeleitet. Um eine erfolgreiche Zahlung zu gewährleisten, darf die Seite nicht geschlossen oder neu geladen werden, bis die Bezahlung abgeschlossen ist ',
              'OXLONGDESC_EN' => 'You will be redirected to PayPal. Please don\'t close or refresh the browser until the payment is completed'
            ]
        ];
  }
    
}
