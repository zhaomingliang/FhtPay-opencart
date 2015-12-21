<?php 
class ControllerPaymentaccelecc extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/accelecc');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('setting/setting');
			
			$this->model_setting_setting->editSetting('accelecc', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect(HTTPS_SERVER . 'index.php?route=extension/payment&token='. $this->session->data['token']);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_eur'] = $this->language->get('text_eur');
		$this->data['text_rmb'] = $this->language->get('text_rmb');
		$this->data['text_usd'] = $this->language->get('text_usd');
		$this->data['text_gbp'] = $this->language->get('text_gbp');
	
		$this->data['entry_merchantid'] = $this->language->get('entry_merchantid');
		$this->data['entry_md5key'] = $this->language->get('entry_md5key');
		$this->data['entry_callback'] = $this->language->get('entry_callback');
		$this->data['entry_currency'] = $this->language->get('entry_currency');
		
		$this->data['entry_language'] = $this->language->get('entry_language');
		$this->data['entry_transaction_url'] = $this->language->get('entry_transaction_url');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');	

		$this->data['entry_success_order_status']=$this->language->get('entry_success_order_status');
		$this->data['entry_failed_order_status']=$this->language->get('entry_failed_order_status');
		$this->data['entry_proccess_order_status']=$this->language->get('entry_proccess_order_status');
		
		
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['merchant'])) {
			$this->data['error_merchant'] = $this->error['merchant'];
		} else {
			$this->data['error_merchant'] = '';
		}

 		if (isset($this->error['md5key'])) {
			$this->data['error_md5key'] = $this->error['md5key'];
		} else {
			$this->data['error_md5key'] = '';
		}

  		$this->document->breadcrumbs = array();

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=common/home&token='. $this->session->data['token'],
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=extension/payment&token='. $this->session->data['token'],
       		'text'      => $this->language->get('text_payment'),
      		'separator' => ' :: '
   		);

   		$this->document->breadcrumbs[] = array(
       		'href'      => HTTPS_SERVER . 'index.php?route=payment/accelecc&token='. $this->session->data['token'],
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = HTTPS_SERVER . 'index.php?route=payment/accelecc&token='. $this->session->data['token'];
		
		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/payment&token='. $this->session->data['token'];
		
		if (isset($this->request->post['accelecc_merchant'])) {
			$this->data['accelecc_merchant'] = $this->request->post['accelecc_merchant'];
		} else {
			$this->data['accelecc_merchant'] = $this->config->get('accelecc_merchant');
		}
		
		if (isset($this->request->post['accelecc_md5key'])) {
			$this->data['accelecc_md5key'] = $this->request->post['accelecc_md5key'];
		} else {
			$this->data['accelecc_md5key'] = $this->config->get('accelecc_md5key');
		}
		
		$this->data['callback'] = HTTP_CATALOG . 'index.php?route=payment/accelecc/callback';
		//echo $this->data['callback'];
		if (isset($this->request->post['accelecc_currency'])) {
			$this->data['accelecc_currency'] = $this->request->post['accelecc_currency'];
		} else {
			$this->data['accelecc_currency'] = $this->config->get('accelecc_currency');
		}
		
		if (isset($this->request->post['accelecc_language'])) {
			$this->data['accelecc_language'] = $this->request->post['accelecc_language'];
		} else {
			$this->data['accelecc_language'] = $this->config->get('accelecc_language');
		}
		if (isset($this->request->post['accelecc_order_status_id'])) {
			$this->data['accelecc_order_status_id'] = $this->request->post['accelecc_order_status_id'];
		} else {
			$this->data['accelecc_order_status_id'] = $this->config->get('accelecc_order_status_id'); 
		} 
		/* add transaction URL */
		if(isset($this->request->post['accelecc_transaction_url'])){
			$this->data['accelecc_transaction_url'] = $this->request->post['accelecc_transaction_url'];
		}else{
			$this->data['accelecc_transaction_url'] = $this->config->get('accelecc_transaction_url');
		}
		/* add status */
		if (isset($this->request->post['accelecc_success_order_status_id'])) {
			$this->data['accelecc_success_order_status_id'] = $this->request->post['accelecc_success_order_status_id'];
		} else {
			$this->data['accelecc_success_order_status_id'] = $this->config->get('accelecc_success_order_status_id'); 
		} 
		if (isset($this->request->post['accelecc_failed_order_status_id'])) {
			$this->data['accelecc_failed_order_status_id'] = $this->request->post['accelecc_failed_order_status_id'];
		} else {
			$this->data['accelecc_failed_order_status_id'] = $this->config->get('accelecc_failed_order_status_id'); 
		} 
		if (isset($this->request->post['accelecc_proccess_order_status_id'])) {
			$this->data['accelecc_proccess_order_status_id'] = $this->request->post['accelecc_proccess_order_status_id'];
		} else {
			$this->data['accelecc_proccess_order_status_id'] = $this->config->get('accelecc_proccess_order_status_id'); 
		} 
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['accelecc_geo_zone_id'])) {
			$this->data['accelecc_geo_zone_id'] = $this->request->post['accelecc_geo_zone_id'];
		} else {
			$this->data['accelecc_geo_zone_id'] = $this->config->get('accelecc_geo_zone_id'); 
		} 

		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['accelecc_status'])) {
			$this->data['accelecc_status'] = $this->request->post['accelecc_status'];
		} else {
			$this->data['accelecc_status'] = $this->config->get('accelecc_status');
		}
		
		if (isset($this->request->post['accelecc_sort_order'])) {
			$this->data['accelecc_sort_order'] = $this->request->post['accelecc_sort_order'];
		} else {
			$this->data['accelecc_sort_order'] = $this->config->get('accelecc_sort_order');
		}
		
		$this->template = 'payment/accelecc.tpl';
		$this->children = array(
			'common/header',	
			'common/footer'	
		);
		
		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/accelecc')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['accelecc_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}
		
		if (!$this->request->post['accelecc_md5key']) {
			$this->error['md5key'] = $this->language->get('error_md5key');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
}
?>