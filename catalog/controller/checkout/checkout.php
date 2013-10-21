<?php class ControllerCheckoutCheckout extends Controller
{
  public function index ()
  {
    // Validate cart has products and has stock.
    if ((!$this->cart->hasProducts ()
	 && empty ($this->session->data['vouchers']))
	|| (!$this->cart->hasStock ()
	    && !$this->config->get ('config_stock_checkout')))
      {
	$this->redirect ($this->url->link ('checkout/cart'));
      }

    $products = $this->cart->getProducts ();

    $this->language->load ('checkout/checkout');

    $this->document->setTitle ($this->language->get ('heading_title'));
    $this->document->addScript
      ('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
    $this->
      document->addStyle
      ('catalog/view/javascript/jquery/colorbox/colorbox.css');

    $this->data['breadcrumbs'] = array ();

    $this->data['breadcrumbs'][] = array ('text' => $this->language->get ('text_home'),
					  'href' => $this->url->link ('common/home'),
					  'separator' =>false);

    $this->data['breadcrumbs'][] = array ('text' =>$this->language->get ('text_cart'),
					  'href' =>$this->url->link ('checkout/cart'),
					  'separator' =>$this->language->get ('text_separator'));

    $this->data['breadcrumbs'][] = array ('text' =>$this->
					  language->get ('heading_title'),
					  'href' =>$this->
					  url->link ('checkout/checkout', '',
						     'SSL'), 'separator' =>$this->language->get ('text_separator'));

    $this->data['heading_title'] = $this->language->get ('heading_title');

    $this->template = 'default/template/checkout/checkout.tpl';

    $this->children = array ('common/column_left',
			     'common/column_right',
			     'common/content_top',
			     'common/content_bottom',
			     'common/footer', 'common/header');

    $this->data['action'] = $this->url->link ('checkout/checkout', '', 'SSL');

    //Initialize values for view
    $allFields = array('firstName', 'lastName', 'contactNumber', 'reservationNumber', 'specialInstructions', 'captcha');
    foreach( $allFields as $field) {
      $this->data[$field] = "";
      if( isset( $this->request->post[$field]) ){
        $this->data[$field] = $this->request->post[$field];
      }
      $errorId = 'error' . ucfirst($field);
      $this->data[$errorId] = "";
    }
    


    if (isset ($this->request->post['submitOrder'])){

        //Validate Data
        $isValid =  $this->validateForm();
        if($isValid) {
            $this->load->model('checkout/order');
            $order_id = $this->model_checkout_order->addOrder( $this->prepareOrderData() );

            $this->session->data['order_id'] = $order_id; 
            $this->redirect($this->url->link('checkout/success'));
            return;
        }
    }
    
    //Generate captcha 
    include("simple-php-captcha/simple-php-captcha.php");
    $_SESSION['captcha'] = simple_php_captcha();
    $this->data['captchaImg'] = $_SESSION['captcha']['image_src'];
    $this->data['captcha'] = "";
    $this->response->setOutput ($this->render ());
  }

  private function validateForm() {
     $res = true;
     $reqFields = array('firstName', 'lastName', 'contactNumber', 'reservationNumber');
     foreach( $reqFields as $field) {
        $res = $res & $this->validateBlank($field);
     }
     
     $res = $res & $this->validateCaptcha();
     return $res;
   }
   
   private function validateCaptcha() {
      if( !isset($_SESSION) ||
          !isset($_SESSION['captcha']) ||
          !isset($_SESSION['captcha']['code']) ||
          !isset($this->request->post['captcha'])) {
         return false;

      }
      if ( strtoupper($_SESSION['captcha']['code']) == strtoupper($this->request->post['captcha'])) {
         return true;
      }
      $this->data['errorCaptcha'] = "Wrong code. Try again.";
      return false;

   }

   private function validateBlank($field) {
      if(  utf8_strlen($this->request->post[$field]) < 1 ) {
        $errorId = 'error' . ucfirst($field);
        $this->data[$errorId] = "Must not be blank.";
        return false;
      }
      return true;
   }
   
   private function prepareOrderData() {
       $orderdata = array();
       //$orderdata['email'] = $this->data['email'];
       $orderdata['email'] = "michael.e.moorman@gmail.com";
       $orderdata['contactNumber'] = $this->data['contactNumber'];
       $orderdata['ipAddress'] = $_SERVER["REMOTE_ADDR"];
       $orderdata['lastName'] = $this->data['lastName'];
       $orderdata['firstName'] = $this->data['firstName'];
       $orderdata['reservationNumber'] = $this->data['reservationNumber'];
       $orderdata['products'] = $this->cart->getProducts();
       $orderdata['total'] = $this->cart->getTotal();
       return $orderdata;
 

   }

}


?>
