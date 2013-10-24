<?php
class ShopciergeOrder {

   public static function addOrder($data, $db) {
        $db->query("INSERT INTO `" . DB_PREFIX . "order` 
           SET contactNumber = '" . $data['contactNumber'] . "'" .
                ", email = '" . $data['email'] .  "'" .
                ", ipAddress ='" . $data['ipAddress'] .  "'" .
                ", datePurchased = NOW()" .
                ",  lastName = '" . $data['lastName'] . "'" .
                ",  firstName = '" . $data['firstName'] . "'" .
                ",  reservationNumber = '" . $data['reservationNumber'] . "'" .
                ",  total = ' " . (float)$data['total'] . "'" .
                ",  orders_status = '0'");
        $order_id = $db->getLastId();

        foreach ($data['products'] as $product) {
            $db->query("INSERT INTO " . DB_PREFIX . "order_product " .
                  "SET order_id = '" . (int)$order_id . "'" .
                  ", product_id = '" . (int)$product['product_id'] . "'" .
                  ", name = '" . $db->escape($product['name']) . "'" .
                  ", model = '" . $db->escape($product['model']) . "'" .
                  ", quantity = '" . (int)$product['quantity'] . "'" .
                  ", price = '" . (float)$product['price'] . "'" .
                  ", total = '" . (float)$product['total'] . "'" .
                  ", tax = '" . (float)$product['tax'] . "'");
            $order_product_id = $db->getLastId();

            foreach ($product['option'] as $option) {
               $db->query("INSERT INTO " . DB_PREFIX . "order_option " .
                    "SET order_id = '" . (int)$order_id . "'" .
                    ", order_product_id = '" . (int)$order_product_id . "'" .
                    ", product_option_id = '" . (int)$option['product_option_id'] . "'" .
                    ", product_option_value_id = '" . (int)$option['product_option_value_id'] . "'" .
                    ", name = '" . $db->escape($option['name']) . "'" .      
                    ", `value` = '" . $db->escape($option['value']) . "'" .      
                    ", `type` = '" . $db->escape($option['type']) . "'");
            }
       }
       return $order_id;
   }   


   public static function getOrder($id, $db) { 
        $data = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$id . "'");
         if ($data->num_rows) {
            return array(
                'contactNumber'      =>  $data['contactNumber'],
                'email'              =>  $data['email'],
                'ipAddress'          =>  $data['ipAddress'],
                'datePurchased'      =>  $data['datePurchased'],
                'lastName'           =>  $data['lastName'],
                'firstName'          =>  $data['firstName'],
                'reservationNumber'  =>  $data['reservationNumber'],
                'total'              =>  (float)$data['total'],
                'order_status'       =>  $data['order_status'] 
             );
         }
         
         return NULL;


   }
}

?>
