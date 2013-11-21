<?php
class ShopciergeOrder {
   public static function updateOrderStatus($id, $statusId, $db) {
       $db->query("UPDATE `" . DB_PREFIX . "order` SET order_status_id = '" .  $statusId . "'" .
                  " WHERE order_id = '" . $id . "'");

   }

   public static function addOrder($data, $db) {
        $db->query("INSERT INTO `" . DB_PREFIX . "order` 
           SET contactNumber = '" . $data['contactNumber'] . "'" .
                ", email = '" . $data['email'] .  "'" .
                ", ipAddress ='" . $data['ipAddress'] .  "'" .
                ", datePurchased = NOW()" .
                ",  lastName = '" . $data['lastName'] . "'" .
                ",  firstName = '" . $data['firstName'] . "'" .
                ",  reservationNumber = '" . $data['reservationNumber'] . "'" .
                ",  fulfillmentDate = '" . date('Y-m-d', $data['fulfillmentDate']) . "'" .
                ",  customerInstructions = '"  . $data['customerInstructions'] . "'" .
                ",  total = ' " . (float)$data['total'] . "'");
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
        $data = $db->query("SELECT * FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$id . "'");
         if ($data->num_rows) {
            return array(
                'contactNumber'      =>  $data->row['contactNumber'],
                'email'              =>  $data->row['email'],
                'ipAddress'          =>  $data->row['ipAddress'],
                'datePurchased'      =>  $data->row['datePurchased'],
                'fulfillmentDate'      =>  $data->row['fulfillmentDate'],
                'lastName'           =>  $data->row['lastName'],
                'customerInstructions'  =>  $data->row['customerInstructions'],
                'firstName'          =>  $data->row['firstName'],
                'reservationNumber'  =>  $data->row['reservationNumber'],
                'total'              =>  (float)$data->row['total'],
                'order_status'       =>  $data->row['order_status_id'],
                'ip'       =>            $data->row['ipAddress'] 
             );
         }
         
         return NULL;


   }
}

?>
