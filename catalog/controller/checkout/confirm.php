<?php 
class ControllerCheckoutConfirm extends Controller { 
	public function index() {
		$total_data = array();
		$total = 0;
		$taxes = $this->cart->getTaxes();
			 
		$this->load->model('setting/extension');
		
		$sort_order = array(); 
		
		$results = $this->model_setting_extension->getExtensions('total');
		
		foreach ($results as $key => $value) {
			$sort_order[$key] = $this->config->get($value['code'] . '_sort_order');
		}
			
		array_multisort($sort_order, SORT_ASC, $results);
		
		foreach ($results as $result) {
			if ($this->config->get($result['code'] . '_status')) {
				$this->load->model('total/' . $result['code']);
	
				$this->{'model_total_' . $result['code']}->getTotal($total_data, $total, $taxes);
			}
		}
			
		$sort_order = array(); 
	  
		foreach ($total_data as $key => $value) {
			$sort_order[$key] = $value['sort_order'];
		}
	
		array_multisort($sort_order, SORT_ASC, $total_data);
	
		$product_data = array();
		
		foreach ($this->cart->getProducts() as $product) {
			$option_data = array();
			foreach ($product['option'] as $option) {
				if ($option['type'] != 'file') {
					$value = $option['option_value'];	
				} else {
					$value = $this->encryption->decrypt($option['option_value']);
				}	
					
				$option_data[] = array(
					'product_option_id'       => $option['product_option_id'],
					'product_option_value_id' => $option['product_option_value_id'],
					'option_id'               => $option['option_id'],
					'option_value_id'         => $option['option_value_id'],								   
					'name'                    => $option['name'],
					'value'                   => $value,
						'type'                    => $option['type']
					);					
			}
	 
			$product_data[] = array(
					'product_id' => $product['product_id'],
					'name'       => $product['name'],
					'model'      => $product['model'],
					'href'      =>   $this->url->link('product/product', 'product_id=' . $product['product_id']),
					'option'     => $option_data,
					'quantity'   => $product['quantity'],
					'subtract'   => $product['subtract'],
					'price'      => $this->currency->format($product['price']),
					'total'      => $this->currency->format($product['total']),
					'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
				); 
		}
			
						
	        $this->data['products'] = $product_data;
		$this->data['totals'] = $total_data;
		$this->data['total'] = $total;
		$this->template = $this->config->get('config_template') . '/template/checkout/confirm.tpl';
		$this->response->setOutput($this->render());	
  	}
}
?>
