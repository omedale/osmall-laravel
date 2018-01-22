<?php
namespace App\Classes;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Exception;

/**
* Direct Payment
*/
class MasterCard
{
	
	public $merchant_id="";
	public $api_password="";
	public $connection_status_url="";
	protected $api_mode="REST";

	public function __construct()
	{
		$this->set_variables();
		$test=$this->check_connection();
		if ($test==false) {
			throw new Exception("No connection", 404);
			
		}

	}
	public function set_variables()
	{
		$this->merchant_id="10000023801";
		$this->api_password="";
		if ($this->api_mode=="REST") {
			$this->connection_status_url="https://eu-gateway.mastercard.com/api/rest/version/1/information";
		}
	}

	public function check_connection()
	{
		$ret=false;
		$client=new Client;
		$response=$client->request('GET',$this->connection_status_url);
		$body=json_decode($response->getBody()->getContents());
		if ($response->getStatusCode()=="200" and $body->status=="OPERATING") {
			$ret=true;
		}
		return $ret;
	}

	public function sanitize_input($)
	{
		# code...
	}

	public function do_authentication($order_id,$transaction_id)
	{

		$url="https://eu-gateway.mastercard.com/api/rest/version/44/merchant/".$this->merchant_id."/order/".$order_id."/transaction/".$transaction_id;
		$client=new Client;

	}

	/*TEST METHODS*/

	public function test()
	{
		return "MasterCard is connected";
	}

	public function fake_data_auth()
	{
		$fake_data=array();
		$sourceOfFunds=array();
		
		/*Card Details*/ 
		$card=array();
		$expiry=array();
		$expiry['month']="12";
		$expiry['year']="2023";

		$card['securityCode']="123";
		$card['number']="0987659876";
		$card['expiry']=$expiry;
		/**/ 
		// $sourceOfFunds['type']['card']=$card;
		$sourceOfFunds['type']='card';
		/*Order details*/
		$order=array();
		$order['reference'] ="PUNKBUNKRUNK";
		$order['amount']=12.13;
		$order['currency']="MYR";
		/*Transaction*/
		$transaction=array();
		$transaction['reference']="YOLOWOLOHOLO";
		// $transaction['amount']="12.13";
		// $transaction['currency']="MYR";
		$transaction['targetTransactionId']="CHINGCHANGCHOONG";

		/*Customer*/
		$customer=array();
		$customer['ipAddress']="10.20.43.33";
		/*Optionals for Authorization*/ 
		$customer['email']="bruce@wayneindustries.com";
		$customer['firstName']="Bruce";
		$customer['lastName']="Wayne";
		$customer['mobilePhone']="123456";
		$customer['phone']="12345666";

		/*OPTIONAL - BILLING*/
		$billing=array();
		$billing['address']['street']="Wayne Tower, First Road Lake Side.";
		$billing['address']['city']="Gotham City"; 
		$billing['address']['company']="Wayne Industries";
		$billing['address']['country']="Marvel's Universe";
		$billing['address']['postcodeZip']="12345";
		$billing['address']['stateProvince']="NewYork";

		$fake_data['order']=$order;
	}
}

?>