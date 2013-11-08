<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <div>
     <div>
      <div class="checkout-heading">Confirm Your Order</div>
      <div  id='confirmArea'></div>

      <div class="checkout-heading">Submit Your Order</div>
      <div class="checkout-content">

         <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
            <input type='hidden' value='1' name='submitOrder'/>
            <div class="content">
               <table class="form"> 
                  <tr>
                     <td><span class="required">*</span>First Name:</td>
                     <td><input type="text" name="firstName" value="<?php echo $firstName; ?>"/>
                     <?php if ($errorFirstName) { ?>
                     <span class="error"><?php echo $errorFirstName; ?></span>
                     <?php } ?></td>
                  </tr>

                  <tr>
                     <td><span class="required">*</span>Last Name:</td>
                     <td><input type="text" name="lastName" value="<?php echo $lastName; ?>"/>
                     <?php if ($errorLastName) { ?>
                     <span class="error"><?php echo $errorLastName; ?></span>
                     <?php } ?></td>
                  </tr>

                  <tr>
                     <td><span class="required">*</span>Contact Number:</td>
                     <td><input type="text" name="contactNumber" value="<?php echo $contactNumber; ?>" />
                     <?php if ($errorContactNumber) { ?>
                     <span class="error"><?php echo $errorContactNumber; ?></span>
                     <?php } ?></td>
                  </tr>

                  <tr>
                     <td><span class="required">*</span>Reservation Number:</td>
                     <td><input type="text" name="reservationNumber" value="<?php echo $reservationNumber; ?>" />
                     <?php if ($errorReservationNumber) { ?>
                     <span class="error"><?php echo $errorReservationNumber; ?></span>
                     <?php } ?></td>
                  </tr>


                  <tr> 
                     <td><span class="required">*</span>Desired Fulfillment Date:</td>
                     <td><input type="text" name="fulfillmentDate" value="<?php echo $fulfillmentDate?>" size="12" class="date" />
                     <?php if ($errorFulfillmentDate) { ?>
                     <span class="error"><?php echo $errorFulfillmentDate; ?></span>
                     <?php } ?></td>
 

                  </tr>

                  <tr>
                     <td>Special Instructions:</td>

                     <td><textarea cols="80" rows="5"  name="customerInstructions" ><?php echo $customerInstructions; ?></textarea>
                     </td>
                  </tr>

                  <tr>
                     <td><span class="required">*</span>Prove you're human:
                     <img src="<?php echo $captchaImg?>"/></td>
                     <td><input type="text" name="captcha" value="<?php echo $captcha;?>" />
                     <?php if ($errorCaptcha) { ?>
                     <span class="error"><?php echo $errorCaptcha; ?></span>
                     <?php } ?></td>
                  </tr>

                    
                  <tr>
                     <td></td><td> <input type="submit" value="Submit Order" class="button" /></td>
                  </tr>      
                



               </table>
           </div>
        </form>
     </div>

  </div>
  <script>
    $('.date').datepicker({dateFormat: 'mm-dd-yy', minDate: new Date()});
    $("#confirmArea").load('index.php?route=checkout/confirm', function() {
       $("#confirmArea").width('100%');
    });
 </script>
  <?php echo $content_bottom; ?>

<?php echo $footer; ?>
