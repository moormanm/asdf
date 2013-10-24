<?php
class ModelCheckoutOrder extends Model {
   public function addOrder($data) {
      return ShopciergeOrder::addOrder($data, $this->db);
   }
              

   public function getOrder($id) {
      return ShopciergeOrder::getOrder($id, $this->db);
   }
}
?>
