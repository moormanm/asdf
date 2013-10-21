<?php
class ModelCheckoutOrder extends Model {
   public function addOrder($data) {
	$this->db->query("INSERT INTO `" . DB_PREFIX . "order` 
           SET contactNumber = '" . $data['contactNumber'] . "'" . 
                ", email = '" . $data['email'] .  "'" . 
                ", ipAddress ='" . $data['ipAddress'] .  "'" . 
                ", datePurchased = NOW()" . 
                ",  lastName = '" . $data['lastName'] . "'" . 
                ",  firstName = '" . $data['firstName'] . "'" . 
                ",  reservationNumber = '" . $data['reservationNumber'] . "'" . 
                ",  total = ' " . (float)$data['total'] . "'" .
                ",  orders_status = '0'");
        $order_id = $this->db->getLastId();

        foreach ($data['products'] as $product) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "order_product " .
                  "SET order_id = '" . (int)$order_id . "'" . 
                  ", product_id = '" . (int)$product['product_id'] . "'" .
                  ", name = '" . $this->db->escape($product['name']) . "'" .
                  ", model = '" . $this->db->escape($product['model']) . "'" .
                  ", quantity = '" . (int)$product['quantity'] . "'" .
                  ", price = '" . (float)$product['price'] . "'" . 
                  ", total = '" . (float)$product['total'] . "'" .
                  ", tax = '" . (float)$product['tax'] . "'");
            $order_product_id = $this->db->getLastId();
            foreach ($product['option'] as $option) {
               $this->db->query("INSERT INTO " . DB_PREFIX . "order_option " .
                    "SET order_id = '" . (int)$order_id . "'" .
                    ", order_product_id = '" . (int)$order_product_id . "'" .
                    ", product_option_id = '" . (int)$option['product_option_id'] . "'" .
                    ", product_option_value_id = '" . (int)$option['product_option_value_id'] . "'" . 
                    ", name = '" . $this->db->escape($option['name']) . "'" . 
                    ", `value` = '" . $this->db->escape($option['value']) . "'" . 
                    ", `type` = '" . $this->db->escape($option['type']) . "'");
            }
       }        
       return $order_id;
   }
              

   public function getOrder($data) {

   }
}
?>
