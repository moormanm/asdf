<?php
final class Tax {
	private $shipping_address;
	private $payment_address;
	private $store_address;
	
	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->customer = $registry->get('customer');
		$this->db = $registry->get('db');	
		$this->session = $registry->get('session');
		
		if (isset($this->session->data['shipping_country_id']) || isset($this->session->data['shipping_zone_id'])) {
			$this->setShippingAddress($this->session->data['shipping_country_id'], $this->session->data['shipping_zone_id']);
		} elseif ($this->config->get('config_tax_default') == 'shipping') {
			$this->setShippingAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
		}
		
		if (isset($this->session->data['payment_country_id']) || isset($this->session->data['payment_zone_id'])) {
			$this->setPaymentAddress($this->session->data['payment_country_id'], $this->session->data['payment_zone_id']);
		} elseif ($this->config->get('config_tax_default') == 'payment') {
			$this->setPaymentAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));
		}
		
		$this->setStoreAddress($this->config->get('config_country_id'), $this->config->get('config_zone_id'));	
  	}
	
	public function setShippingAddress($country_id, $zone_id) {
		$this->shipping_address = array(
			'country_id' => $country_id,
			'zone_id'    => $zone_id
		);				
	}

	public function setPaymentAddress($country_id, $zone_id) {
		$this->payment_address = array(
			'country_id' => $country_id,
			'zone_id'    => $zone_id
		);
	}

	public function setStoreAddress($country_id, $zone_id) {
		$this->store_address = array(
			'country_id' => $country_id,
			'zone_id'    => $zone_id
		);
	}
							
  	public function calculate($value, $tax_class_id, $calculate = true) {
		if ($tax_class_id && $calculate) {
			$amount = $this->getTax($value, $tax_class_id);
				
			return $value + $amount;
		} else {
      		return $value;
    	}
  	}
	
  	public function getTax($value, $tax_class_id) {
		$amount = 0;
			
		$tax_rates = $this->getRates($value, $tax_class_id);
		
		foreach ($tax_rates as $tax_rate) {
			$amount += $tax_rate['amount'];
		}
				
		return $amount;
  	}
		
	public function getRateName($tax_rate_id) {
		$tax_query = $this->db->query("SELECT name FROM " . DB_PREFIX . "tax_rate WHERE tax_rate_id = '" . (int)$tax_rate_id . "'");
	
		if ($tax_query->num_rows) {
			return $tax_query->row['name'];
		} else {
			return false;
		}
	}
	
    public function getRates($value, $tax_class_id) {
		$tax_rates = array();
		
				
		
		if ($this->store_address) {
			$tax_query = $this->db->query("SELECT tr2.tax_rate_id, tr2.name, " . 
                        "tr2.rate, tr2.type, tr1.priority FROM " . DB_PREFIX . "tax_rule tr1 " .
                        " LEFT JOIN " . DB_PREFIX . "tax_rate tr2 ON (tr1.tax_rate_id = tr2.tax_rate_id) " .
                        " ORDER BY tr1.priority ASC");
			
			foreach ($tax_query->rows as $result) {
				$tax_rates[$result['tax_rate_id']] = array(
					'tax_rate_id' => $result['tax_rate_id'],
					'name'        => $result['name'],
					'rate'        => $result['rate'],
					'type'        => $result['type'],
					'priority'    => $result['priority']
				);
			}
		}			
		
		$tax_rate_data = array();
		
		foreach ($tax_rates as $tax_rate) {
			if (isset($tax_rate_data[$tax_rate['tax_rate_id']])) {
				$amount = $tax_rate_data[$tax_rate['tax_rate_id']]['amount'];
			} else {
				$amount = 0;
			}
			
			if ($tax_rate['type'] == 'F') {
				$amount += $tax_rate['rate'];
			} elseif ($tax_rate['type'] == 'P') {
				$amount += ($value / 100 * $tax_rate['rate']);
			}
		
			$tax_rate_data[$tax_rate['tax_rate_id']] = array(
				'tax_rate_id' => $tax_rate['tax_rate_id'],
				'name'        => $tax_rate['name'],
				'rate'        => $tax_rate['rate'],
				'type'        => $tax_rate['type'],
				'amount'      => $amount
			);
		}
		
		return $tax_rate_data;
	}

  	public function has($tax_class_id) {
		return isset($this->taxes[$tax_class_id]);
  	}
}
?>
