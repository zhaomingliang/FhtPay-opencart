<?php
class ControllerPaymentaccelecc extends Controller {

	protected function index() {
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');
		$this->load->model('checkout/order');
		
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		$this->data['entry_cc_owner_firstname'] = $order_info['payment_firstname'];
		$this->data['entry_cc_owner_lastname'] = $order_info['payment_lastname'];
		
		$this->load->library('encryption');
		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/checkout';
		} else {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/checkout';
		}
		$this->language->load('payment/accelecc');
		
		$this->data['text_credit_card'] = $this->language->get('text_credit_card');
		$this->data['text_wait'] = $this->language->get('text_wait');
		
		$this->data['entry_cc_type'] = $this->language->get('entry_cc_type');
		$this->data['entry_cc_firstname'] = $this->language->get('entry_cc_firstname');
		$this->data['entry_cc_lastname'] = $this->language->get('entry_cc_lastname');
		
		$this->data['entry_cc_number'] = $this->language->get('entry_cc_number');
		$this->data['entry_cc_expire_date'] = $this->language->get('entry_cc_expire_date');
		$this->data['entry_cc_cvv2'] = $this->language->get('entry_cc_cvv2');
		
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		
		$this->data['months'] = array();
		
		for ($i = 1; $i <= 12; $i++) {
			$this->data['months'][] = array(
				'text'  => strftime('%B', mktime(0, 0, 0, $i, 1, 2000)), 
				'value' => sprintf('%02d', $i)
			);
		}
		
		$today = getdate();

		$this->data['year_expire'] = array();

		for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
			$this->data['year_expire'][] = array(
				'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
				'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)) 
			);
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/accelecc.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/accelecc.tpl';
		} else {
			$this->template = 'default/template/payment/accelecc.tpl';
		}	
		
		$this->render();	
	}
	public function send() {
		$this->language->load('payment/accelecc');
		$this->load->library('encryption');
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		$paymenturl=$this->config->get('accelecc_transaction_url');
		//[必填] 商户号
	 	$merNo = trim($this->config->get('accelecc_merchant'));
		//[必填] 密钥，在后台可以查询
		$merKey = trim($this->config->get('accelecc_md5key'));
		 //[必填] 订单号，由网店系统产生，不能重复  		
		$orderNo = $this->session->data['order_id'];
		//[必填] 金额，最多为小数点两位
		$amount =sprintf ( '%.2f', $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false)); 
        //[必填] 币种
		$currency =$order_info['currency_code'];
		//[必填] 持卡人邮箱,用户支付成功/失败发送邮件给持卡人
		$email =$order_info['email'];
		//[必填] 支付结果返回的商户URL
		$returnURL=HTTPS_SERVER . 'index.php?route=payment/accelecc/callback';
		// 参数组合：只能以下面的顺序组合;进行md5加密
		$md5src = $merNo.$merKey.$orderNo.$amount.$currency.$email.$returnURL;	
		$md5Info = strtoupper(md5($md5src));
		
        $data = array();

		$data['BillNo'] = $order_info['order_id'];
		$data['payNumber'] = "0";
		$data['merNo'] =$merNo;
		$data['shopName'] ="";	
		$data['orderNo'] =$orderNo;
		$data['currency'] = $currency;
		$data['amount'] = $amount; 
		
		$productsinfo= $this->cart->getProducts();
		
		$goodsstr='';
		foreach($productsinfo as $goodsinfo){
			$goodsstr.='goodsName='.$goodsinfo['name'].'&';
			$goodsstr.='goodsPrice='.$goodsinfo['price'].'&';
			$goodsstr.='goodsNumber='.$goodsinfo['quantity'].'&';
		}
		
		$data['billFirstName'] = $order_info['payment_firstname'];
		$data['billLastName'] = $order_info['payment_lastname'];
		$data['billAddress'] = $order_info['payment_address_1'];
		$data['billCity'] =$order_info['payment_city'];
		$data['billState'] =$order_info['payment_zone'];
		$data['billCountry'] = $order_info['payment_iso_code_2'];
		$data['billZip'] = $order_info['payment_postcode'];
		$data['email'] = $email;
		$data['phone'] = $order_info['telephone'];
		
		$data['shipFirstName'] = $order_info['shipping_firstname'];
		$data['shipLastName'] = $order_info['shipping_lastname'];
		
		if (!$order_info['shipping_address_2']) {
			$data['shipAddress']= $order_info['shipping_address_1'] ;
		} else {
			$data['shipAddress'] = $order_info['shipping_address_1'] . ', ' . $order_info['shipping_address_2'];
		}
		
		$data['shipCity'] = $order_info['shipping_city'];
		$data['shipState'] = $order_info['shipping_zone'];
		$data['shipCountry'] = $order_info['shipping_iso_code_2'];
		$data['shipZip'] = $order_info['shipping_postcode'];
		$data['returnURL'] = $returnURL;
		$data['language'] = "EN";
		$data['remark'] = "";
		$data['md5Info'] = $md5Info;
		//$data['payIp'] ='183.49.116.67';
		$data['payIp'] =$this->getip(); 
		$data['acceptLanguage'] =substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,5);
		$data['userAgent'] =$_SERVER['HTTP_USER_AGENT'];
		$data['payMethod'] ='';
		
		$data['cardNo'] = str_replace(' ', '', $this->request->post['accelecc_cc_number']);
		$data['expirationMonth'] =$this->request->post['accelecc_cc_expire_date_month'] ;
		$data['expirationYear'] =$this->request->post['accelecc_cc_expire_date_year'];
		$data['cvv'] = $this->request->post['accelecc_cvv'];
		$response=$this->CurlPost($paymenturl, http_build_query($data).'&'.$goodsstr);

		$json = array();
		if ($response) {
			$xmlArr=$this->xmltoArray($response);
			$ReturntradeNo=$xmlArr['tradeNo'];
			$ReturnorderNo=$xmlArr['orderNo'];
			$ReturnSucceed=$xmlArr['succeed'];
			$bankInfo=$xmlArr['bankInfo'];
			$errorCode=$xmlArr['errorCode'];
			$errorMsg=$xmlArr['errorMsg'];
			$Returncurrency=$xmlArr['currency'];
			$returnamount=$xmlArr['amount'];
			$returnmd5info=$xmlArr['md5Info'];//返回的md5info			
			$tokenId=$xmlArr['tokenId'];
			$validateString=$xmlArr['validateString'];
			$validateurl=$xmlArr['validateUrl'];
			$returnmd5sign= strtoupper(md5($ReturntradeNo.$ReturnorderNo.$merKey.$ReturnSucceed.$Returncurrency.$returnamount));
			$resultMsg=$bankInfo?$bankInfo:$errorMsg;
		if($ReturnSucceed=='1'){
			if($returnmd5info==$returnmd5sign){
					$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('config_order_status_id'));
					$message = '';
				if (!empty($ReturntradeNo)) {
					$message .= 'TradeNo: '.$ReturntradeNo."\n";
				}
					if (!empty($ReturnorderNo)) {
					$message .= 'OrderNo: '.$ReturnorderNo."\n";
				}
				
				if (!empty($returnamount) &&!empty($Returncurrency )) {
					$message .= 'Amount: ' . $returnamount . $Returncurrency."\n";
				}
				
				
				if (!empty($ReturnSucceed)) {
					$message .= 'Succeed: '.$ReturnSucceed. "\n";
				}
				
				if (!empty($bankInfo)) {
					$message .= 'BankInfo: '.$errorCode.$resultMsg."\n";
				}
					$this->model_checkout_order->update($ReturnorderNo, $this->config->get('accelecc_success_order_status_id'), $message, true );
			}
			$json['success'] = $this->url->link('checkout/success', '', 'SSL');
		}elseif($ReturnSucceed=='0'){		//支付失败
				//$this->log->write('response='.$response);
				//$this->log->write('str='.$str.$goodsstr);//写入日志文件
				$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('config_order_status_id'));

				$message = '';
				if (!empty($ReturntradeNo)) {
					$message .= 'TradeNo: '.$ReturntradeNo."\n";
				}
				if (!empty($ReturnorderNo)) {
					$message .= 'OrderNo: '.$ReturnorderNo."\n";
				}
				
				if (!empty($returnamount) &&!empty($Returncurrency )) {
					$message .= 'Amount: ' . $returnamount . $Returncurrency."\n";
				}
				
				
				if (!empty($ReturnSucceed)) {
					$message .= 'Succeed: '.$ReturnSucceed. "\n";
				}
				
				if (!empty($resultMsg)) {
					$message .= 'BankInfo: '.$errorCode.$resultMsg."\n";
				}
				
				$this->model_checkout_order->update($ReturnorderNo, $this->config->get('accelecc_failed_order_status_id'), $message, true );
			
				
				$json['error'] ='Return Message:'.$errorCode.$resultMsg;
				
				
		}else{
				//$this->log->write('response='.$response);
				//$this->log->write('str='.$str);
				$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('config_order_status_id'));

				$message = '';
					if (!empty($ReturnorderNo)) {
					$message .= 'OrderNo: '.$ReturnorderNo."\n";
				}
				
				if (!empty($returnamount) &&!empty($Returncurrency )) {
					$message .= 'Amount: ' . $returnamount . $Returncurrency."\n";
				}
				
				
				if (!empty($ReturnSucceed)) {
					$message .= 'Succeed: '.$ReturnSucceed. "\n";
				}
				
				if (!empty($resultMsg)) {
					$message .= 'BankInfo: '.$errorCode.$resultMsg."\n";
				}
				$this->model_checkout_order->update($ReturnorderNo, $this->config->get('accelecc_order_status_id'), $message, true );

				$json['error'] ='Return Message:'.$errorCode.$resultMsg;
				
		}
	}else{	
			$json['error'] = 'Empty Gateway Response';

			$this->log->write('CURL ERROR: Empty Gateway Response');
		
	}		

		$this->response->setOutput(json_encode($json));		
	}
	function CurlPost($url, $data) { 
		$curl_cookie="";
		$curl_cookie.= str_replace('&',';' ,http_build_query($_COOKIE));

		$curl = curl_init(); 
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
		curl_setopt($curl, CURLOPT_REFERER, $_SERVER['HTTP_HOST']);
		if(stripos( $url, 'https') !== false){
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($curl, CURLOPT_PORT, 443);
		}
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($curl, CURLOPT_TIMEOUT, 300);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_COOKIE,$curl_cookie);
		$tmpInfo = curl_exec($curl);
		
		curl_close($curl);
		return $tmpInfo;
	}
	//支付失败
	public function failure() {
		
			$this->language->load('payment/accelecc');
		
			$this->data['title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

			if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on')) {
				$this->data['base'] = HTTP_SERVER;
			} else {
				$this->data['base'] = HTTPS_SERVER;
			}
		
			$this->data['charset'] = $this->language->get('charset');
			$this->data['language'] = $this->language->get('code');
			$this->data['direction'] = $this->language->get('direction');
		
			$this->data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));
			
			$this->data['text_response'] = $this->language->get('text_response');
			$this->data['text_success'] = $this->language->get('text_success');
			$this->data['text_success_wait'] = sprintf($this->language->get('text_success_wait'), HTTPS_SERVER . 'index.php?route=checkout/success');
		
			$this->data['text_failure'] = $this->language->get('text_failure');
			
			$this->data['text_billno']='<font color="green">'.$this->session->data['order_id'].'</font>';
			
			if ($this->request->get['route'] != 'checkout/guest_step_3') {
				$this->data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), HTTPS_SERVER . 'index.php?route=checkout/payment');
			} else {
				$this->data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), HTTPS_SERVER . 'index.php?route=checkout/guest_step_2');
			}
			$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'			
		);
		$this->data ['continue'] = HTTPS_SERVER . 'index.php?route=checkout/cart';
					if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/payment/accelecc_failure.tpl' )) {
					$this->template = $this->config->get ( 'config_template' ) . '/template/payment/accelecc_failure.tpl';
				 } else {
					$this->template = 'default/template/payment/accelecc_failure.tpl';
				 }
				
				 $this->response->setOutput ( $this->render ( TRUE ), $this->config->get ( 'config_compression' ) );

	
	}
	  /*
  
    返回结果处理
  */
	public function callback(){
	   if(empty($this->request->request['tradeNo']))
		{
			header('Location:'.$this->url->link('checkout/checkout', '', 'SSL'));
		}
		$this->load->model('checkout/order');	 
		$resp_Data	= array(
			'tradeNo'	=> $this->request->request["tradeNo"],
			'orderNo'	=> $this->request->request["orderNo"],
			'amount'	=> $this->request->request["amount"],
			'currency'	=> $this->request->request["currency"],
			'succeed'	=> $this->request->request["succeed"],
			'bankInfo'	=> $this->request->request["bankInfo"]
		);
		$md5Info		=  $this->request->request["md5Info"];
	    $merKey 		= trim($this->config->get('accelecc_md5key'));
	  	$resp_sign		= strtoupper(md5($resp_Data["tradeNo"].$resp_Data["orderNo"].$merKey.$resp_Data["succeed"].$resp_Data["currency"].$resp_Data["amount"]));
		$message 		= $this->getMessage($resp_Data);
		
		if( $md5Info == $resp_sign){
			if($resp_Data["succeed"] == '1'){
				$this->model_checkout_order->update($resp_Data["orderNo"], $this->config->get('acceledc_success_order_status_id'), $message, true );
				header('Location:'.$this->url->link('checkout/success', '', 'SSL'));
			}elseif($resp_Data["succeed"] == '0'){
				$this->model_checkout_order->update($resp_Data["orderNo"], $this->config->get('acceledc_failed_order_status_id'), $message, true );
				$this->failure();
			}else{
				$this->model_checkout_order->update($resp_Data["orderNo"], $this->config->get('acceledc_success_order_status_id'), $message, true );
				header('Location:'.$this->url->link('checkout/success', '', 'SSL'));
			}
		}else{
			$this->model_checkout_order->update($resp_Data["orderNo"], $this->config->get('acceledc_failed_order_status_id'), $message, true );
			$this->failure();
		}
  }
  
   function getMessage($returnData){
		$message = '';
		if (!empty($returnData['tradeNo'])) {
			$message .= 'tradeNo: '.$returnData['tradeNo']."\n";
		}
		if (!empty($returnData['orderNo'])) {
			$message .= 'OrderNo: '.$returnData['orderNo']."\n";
		}
		
		if (!empty($returnData['amount']) &&!empty($returnData['currency'] )) {
			$message .= 'Amount: ' . $returnData['amount'] . $returnData['currency']."\n";
		}
		if (!empty($returnData['succeed'])) {
			$message .= 'succeed: '.$returnData['succeed']."\n";
		}
		if (!empty($returnData['bankInfo'])) {
			$message .= 'bankInfo: '.$returnData['bankInfo']."\n";
		}
		return $message;
	}
  
 
  function xmltoArray($sim) {
       //var_dump($sim);
	   $xml=new SimpleXMLElement($sim);
	   $array = json_decode(json_encode($xml),true);
	   return $array;
}

//持卡人ip
  function getip(){ 
   if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){ 
			$online_ip = $_SERVER['HTTP_X_FORWARDED_FOR']; 
		}
		elseif(isset($_SERVER['HTTP_CLIENT_IP'])){ 
			$online_ip = $_SERVER['HTTP_CLIENT_IP']; 
		}
		elseif(isset($_SERVER['HTTP_X_REAL_IP'])){ 
			$online_ip = $_SERVER['HTTP_X_REAL_IP']; 
		}else{ 
			$online_ip = $_SERVER['REMOTE_ADDR']; 
		}
		$ips = explode(",",$online_ip);
		return $ips[0];  
}
}
?>