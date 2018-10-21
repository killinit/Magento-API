<?php

/**
 * @author kem parson
 * @copyright 2016
 */

//ini_set('display_errors','On');
//error_reporting(E_ALL); 
$customer=$_REQUEST['custid'];
require_once ($_SERVER['DOCUMENT_ROOT'] .'/app/Mage.php');
require_once ('config.php');
Mage::app();
Mage::app()->getTranslator()->init('frontend');
Mage::getSingleton('core/session', array('name' => 'frontend'));
$proxy = new SoapClient(Mage::getBaseUrl().'/api/v2_soap/?wsdl'); 
$sessionId = $proxy->login(APIUSER, APIKEY);

$filter = array('filter' => array(array('key' => 'customer_id', 'value' =>$customer )));
try{
$result = $proxy->salesOrderList($sessionId, $filter);
$i=0;
foreach ($result as $order):
$ret['$i']['orderid']=$order->increment_id;
$ret['$i']['orderdate']=$order->created_at;
$ret['$i']['ordertotal']=$order->grand_total;
//$ret[$i]['orderid']=$order->order_id;

    //optional
//$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
//$lastyear = date('Y-m-d', strtotime("-1 year"));
//$orderCollection = $objectManager->create('\Magento\Sales\Model\ResourceModel\Order\Collection');
//$orderCollection->addAttributeToFilter('customer_id',123456)
//	        ->addAttributeToFilter('status','complete')
//	        ->addAttributeToFilter('created_at', array('gteq'  => $lastyear))->load();
//
//echo "<pre>";print_r($orderCollection->getData()); exit;	
// end optional


endforeach;
}
catch (Exception $e) {
$message = $e->getMessage();
    return  $ret['error']=$message ;   
   
}

$ret=array_values($ret);       
$result=json_encode($ret);
echo $result;



?>
