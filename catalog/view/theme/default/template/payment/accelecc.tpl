<h2><?php echo $text_credit_card; ?></h2>
<div class="content" id="payment">

  <table class="form">
   <tr>
      <td><?php echo $entry_cc_type; ?></td>
      <td><img src="catalog/view/theme/default/image/accelecc/vm.jpg"  alt="master" title="visa&mastercard" /></td>
    </tr>
    <tr>
      <td><?php echo $entry_cc_firstname; ?></td>
      <td><input type="text"  name="accelecc_cc_firstname" size="22" value="<?php echo $entry_cc_owner_firstname ; ?>" /></td>
    </tr>
	<tr>
      <td><?php echo $entry_cc_lastname; ?></td>
      <td><input type="text" name="accelecc_cc_lastname" size="22" value="<?php echo $entry_cc_owner_lastname ; ?>" /></td>
    </tr>
    <tr>
      <td><?php echo $entry_cc_number; ?></td>
      <td><input type="text" name="accelecc_cc_number" id="accelecc_cc_number" autocomplete="off" maxlength="16"  size="22" value="" /></td>
    </tr>
    <tr>
      <td><?php echo $entry_cc_expire_date; ?></td>
      <td><select name="accelecc_cc_expire_date_month" id="accelecc_cc_expire_date_month" >
          <?php foreach ($months as $month) { ?>
          <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
          <?php } ?>
        </select>
        /
        <select name="accelecc_cc_expire_date_year" id="accelecc_cc_expire_date_year" >
          <?php foreach ($year_expire as $year) { ?>
          <option  value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
          <?php } ?>
        </select></td>
    </tr>
    <tr>
      <td><?php echo $entry_cc_cvv2; ?></td>
      <td><input type="password" name="accelecc_cvv" id="accelecc_cvv" autocomplete="off" value="" size="14" maxlength="3" /> <img src="catalog/view/theme/default/image/accelecc/cartpic1.jpg"  alt="cartpic1" /></td>
    </tr>
  </table>
</div>
<div class="buttons">	
  <div class="right"><a onclick="location = '<?php echo str_replace ( '&', '&amp;', $back );?>'" class="button">
		<span><?php	echo $button_back;?></span>
		</a>
		<a id="button-confirm" class="button">
			<span><?php	echo $button_confirm;?></span>
		</a>
</div>


<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
	var cardNo_length = $("#accelecc_cc_number").val().length;
	if(cardNo_length<10 || cardNo_length==""){
		alert("card number can not be null ,the length must be 16.");
		return false;
	}
	var CVV_length = $("#accelecc_cvv").val().length;
	if(CVV_length!=3 || CVV_length==""){
		alert("CVV2/CSC is incorrect!");
		return false;
	}
	var expirationYear_length = $("#accelecc_cc_expire_date_year").val().length;
	if(expirationYear_length==""){
		alert("expirationYear can not be null.");
		return false;
	}
	var expirationMonth_length = $("#accelecc_cc_expire_date_month").val().length;
	if(expirationMonth_length==""){
		alert("expirationMonth can not be null.");
		return false;
	}
	$.ajax({
		url: 'index.php?route=payment/accelecc/send',
		type: 'post',
		data: $('#payment :input'),
		dataType: 'json',		
		beforeSend: function() {
			$('#button-confirm').attr('disabled', true);
			$('#payment').before('<div class="attention"><img src="catalog/view/theme/default/image/accelecc/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('#button-confirm').attr('disabled', false);
			$('.attention').remove();
		},				
		success: function(json) {
			if (json['error']) {
				alert(json['error']);
				location = 'index.php?route=payment/accelecc/failure';
			}
			
			if (json['success']) {
				location = json['success'];
			}
		}
	});
});
//--></script>