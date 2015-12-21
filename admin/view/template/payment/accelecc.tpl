<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/payment.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content" style="height:600px;">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><span class="required">*</span> <?php echo $entry_merchantid; ?></td>
          <td><input type="text" name="accelecc_merchant" value="<?php echo $accelecc_merchant; ?>" />
            <?php if ($error_merchant) { ?>
            <span class="error"><?php echo $error_merchant; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> <?php echo $entry_md5key; ?></td>
          <td><input type="text" name="accelecc_md5key" value="<?php echo $accelecc_md5key; ?>" />
            <?php if ($error_md5key) { ?>
            <span class="error"><?php echo $error_md5key; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_callback; ?></td>
          <td><input type="text" value="<?php echo $callback; ?>" style="width:700px;"></td>
        </tr>
        <tr>
          <td><?php echo $entry_currency; ?></td>
          <td><select name="accelecc_currency">
              <?php if ($accelecc_currency == '1') { ?>
              <option value="1" selected="selected"><?php echo $text_usd; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_usd; ?></option>
              <?php } ?>
              <?php if ($accelecc_currency == '2') { ?>
              <option value="2" selected="selected"><?php echo $text_eur; ?></option>
              <?php } else { ?>
              <option value="2"><?php echo $text_eur; ?></option>
              <?php } ?>
              <?php if ($accelecc_currency == '3') { ?>
              <option value="3" selected="selected"><?php echo $text_rmb; ?></option>
              <?php } else { ?>
              <option value="3"><?php echo $text_rmb; ?></option>
              <?php } ?>
			  
			  <?php if ($accelecc_currency == '4') { ?>
              <option value="4" selected="selected"><?php echo $text_gbp; ?></option>
              <?php } else { ?>
              <option value="4"><?php echo $text_gbp; ?></option>
              <?php } ?>
            </select></td>
        </tr>
		
		<tr>
          <td><?php echo $entry_language; ?></td>
          <td><input type="text" name="accelecc_language" value="2" style="width:30px;"/></td>
        </tr>
		<tr>
			<td><?php echo $entry_transaction_url; ?></td>
			<td><input type="text" name="accelecc_transaction_url" value="<?php echo $accelecc_transaction_url; ?>" style="width:400px;"/></td>
		</tr>
        <tr>
          <td><?php echo $entry_order_status; ?></td>
          <td><select name="accelecc_order_status_id">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $accelecc_order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
		<tr>
          <td><?php echo $entry_success_order_status; ?></td>
          <td><select name="accelecc_success_order_status_id">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $accelecc_success_order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
		<tr>
          <td><?php echo $entry_failed_order_status; ?></td>
          <td><select name="accelecc_failed_order_status_id">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $accelecc_failed_order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>

        <tr>
          <td><?php echo $entry_geo_zone; ?></td>
          <td><select name="accelecc_geo_zone_id">
              <option value="0"><?php echo $text_all_zones; ?></option>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php if ($geo_zone['geo_zone_id'] == $accelecc_geo_zone_id) { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="accelecc_status">
              <?php if ($accelecc_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_sort_order; ?></td>
          <td><input type="text" name="accelecc_sort_order" value="<?php echo $accelecc_sort_order; ?>" size="1" /></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php echo $footer; ?>