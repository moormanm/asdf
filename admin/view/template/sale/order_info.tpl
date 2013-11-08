<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/order.png" alt="" /> <?php echo $heading_title; ?></h1>

      <div class="buttons">
            <a href="<?php echo $delete ?>" target="_blank" class="button">Delete Order</a>
            <a href="<?php echo $invoice; ?>" target="_blank" class="button"><?php echo $button_invoice; ?></a>
            <a href="<?php echo $cancel; ?>" class="button">Go Back</a>

           
      </div>
    </div>
    <div class="content">


        <table class="form">
          <tr>
            <td>Status:</td>
            <td id="order-status"> 
              <form action="<?php echo $changeStatusAction; ?>" method="post" enctype="multipart/form-data" id="form">
                 <select name="newStatus">
                    <?php foreach ($order_statuses as $status) { ?>
          `            <option <?php if($status['order_status_id'] == $order_status_id){ echo "selected";}
                                echo " value=" .  $status['order_status_id'] ; ?> 
                       ><?php echo $status['name']; ?></option>
                    <?php } ?>
                 </select>
                 <input type="submit" value="Change"/>
                 <input type="hidden" name="order_id" value="<?php echo $order_id;?>"/>
              </form> 
              <br/>
              <span>Current status: <?php echo $order_status; ?></span>
            </td>

          </tr>

 
          <tr>
            <td><?php echo $text_order_id; ?></td>
            <td>#<?php echo $order_id; ?></td>
          </tr>
          <tr>
            <td>Customer Name:</td>
            <td><?php echo $firstName; ?> <?php echo $lastName; ?></td>
          </tr>
          <tr>
            <td><?php echo $text_email; ?></td>
            <td><?php echo $email; ?></td>
          </tr>
          <tr>
            <td>Contact Number:</td>
            <td><?php echo $contactNumber; ?></td>
          </tr>
          <tr>
            <td><?php echo $text_total; ?></td>
            <td><?php echo $total; ?>
          </tr>
          
          <tr>
            <td><?php echo $text_ip; ?></td>
            <td><?php echo $ip; ?></td>
          </tr>
          <tr>
            <td>Date Added:</td>
            <td><?php echo $datePurchased;?></td>
          </tr>
        </table>
        <p>Products Ordered:</p>
        <table class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $column_product; ?></td>
              <td class="left"><?php echo $column_model; ?></td>
              <td class="right"><?php echo $column_quantity; ?></td>
              <td class="right"><?php echo $column_price; ?></td>
              <td class="right"><?php echo $column_total; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products as $product) { ?>
            <tr>
              <td class="left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                <?php foreach ($product['option'] as $option) { ?>
                <br />
                <?php if ($option['type'] != 'file') { ?>
                &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                <?php } else { ?>
                &nbsp;<small> - <?php echo $option['name']; ?>: <a href="<?php echo $option['href']; ?>"><?php echo $option['value']; ?></a></small>
                <?php } ?>
                <?php } ?></td>
              <td class="left"><?php echo $product['model']; ?></td>
              <td class="right"><?php echo $product['quantity']; ?></td>
              <td class="right"><?php echo $product['price']; ?></td>
              <td class="right"><?php echo $product['total']; ?></td>
            </tr>
            <?php } ?>
          </tbody>
          <?php foreach ($totals as $totals) { ?>
          <tbody id="totals">
            <tr>
              <td colspan="4" class="right"><?php echo $totals['title']; ?>:</td>
              <td class="right"><?php echo $totals['text']; ?></td>
            </tr>
          </tbody>
          <?php } ?>
        </table>
      </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#invoice-generate').live('click', function() {
	$.ajax({
		url: 'index.php?route=sale/order/createinvoiceno&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>',
		dataType: 'json',
		beforeSend: function() {
			$('#invoice').after('<img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />');	
		},
		complete: function() {
			$('.loading').remove();
		},
		success: function(json) {
			$('.success, .warning').remove();
						
			if (json['error']) {
				$('#tab-order').prepend('<div class="warning" style="display: none;">' + json['error'] + '</div>');
				
				$('.warning').fadeIn('slow');
			}
			
			if (json.invoice_no) {
				$('#invoice').fadeOut('slow', function() {
					$('#invoice').html(json['invoice_no']);
					
					$('#invoice').fadeIn('slow');
				});
			}
		}
	});
});

//--></script> 
<script type="text/javascript"><!--
$('.vtabs a').tabs();
//--></script>
<script type="text/javascript"><!--
    function orderStatusChange(){
        var status_id = $('select[name="order_status_id"]').val();

        $('#openbayInfo').remove();

        $.ajax({
            url: 'index.php?route=extension/openbay/ajaxOrderInfo&token=<?php echo $this->request->get['token']; ?>&order_id=<?php echo $this->request->get['order_id']; ?>&status_id='+status_id,
            type: 'post',
            dataType: 'html',
            beforeSend: function(){},
            success: function(html) {
                $('#history').after(html);
            },
            failure: function(){},
            error: function(){}
        });
    }

    function addOrderInfo(){
        var status_id = $('select[name="order_status_id"]').val();
        var old_status_id = $('#old_order_status_id').val();

        $('#old_order_status_id').val(status_id);

        $.ajax({
            url: 'index.php?route=extension/openbay/ajaxAddOrderInfo&token=<?php echo $token; ?>&order_id=<?php echo $order_id; ?>&status_id='+status_id+'&old_status_id='+old_status_id,
            type: 'post',
            dataType: 'html',
            data: $(".openbayData").serialize(),
            beforeSend: function(){},
            success: function() {},
            failure: function(){},
            error: function(){}
        });
    }

    $(document).ready(function() {
        orderStatusChange();
    });

    $('select[name="order_status_id"]').change(function(){orderStatusChange();});
//--></script>
<?php echo $footer; ?>
