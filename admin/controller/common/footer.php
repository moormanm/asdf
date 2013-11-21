<?php
class ControllerCommonFooter extends Controller {   
	protected function index() {
		$this->language->load('common/footer');
		$this->data['text_footer'] =  "Powered by Shopcierge&#169;<br/> 2013 All Rights Reserved." ;
		$this->template = 'common/footer.tpl';
    	        $this->render();
  	}
}
?>
