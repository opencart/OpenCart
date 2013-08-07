<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel">
    <div class="panel-heading">
      <h1 class="panel-title"><i class="icon-edit icon-large"></i> <?php echo $heading_title; ?></h1>
      <div class="buttons">
        <button type="submit" form="form-paymate" class="btn btn-primary"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
    </div>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-paymate" class="form-horizontal">
      <div class="control-group required">
        <label class="col-lg-2 control-label" for="input-username"><?php echo $entry_username; ?></label>
        <div class="col-lg-10">
          <input type="text" name="paymate_username" value="<?php echo $paymate_username; ?>" placeholder="<?php echo $entry_username; ?>" id="input-username" />
          <?php if ($error_username) { ?>
          <span class="error"><?php echo $error_username; ?></span>
          <?php } ?>
        </div>
      </div>
      <div class="control-group required">
        <label class="col-lg-2 control-label" for="input-password"><?php echo $entry_password; ?> <span class="help-block"><?php echo $help_password; ?></span></label>
        <div class="col-lg-10">
          <input type="text" name="paymate_password" value="<?php echo $paymate_password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" />
          <?php if ($error_password) { ?>
          <span class="error"><?php echo $error_password; ?></span>
          <?php } ?>
        </div>
      </div>
      <div class="form-group">
        <div class="col-lg-2 control-label"><?php echo $entry_test; ?></div>
        <div class="col-lg-10">
          <label class="radio inline">
            <?php if ($paymate_test) { ?>
            <input type="radio" name="paymate_test" value="1" checked="checked" />
            <?php echo $text_yes; ?>
            <?php } else { ?>
            <input type="radio" name="paymate_test" value="1" />
            <?php echo $text_yes; ?>
            <?php } ?>
          </label>
          <label class="radio inline">
            <?php if (!$paymate_test) { ?>
            <input type="radio" name="paymate_test" value="0" checked="checked" />
            <?php echo $text_no; ?>
            <?php } else { ?>
            <input type="radio" name="paymate_test" value="0" />
            <?php echo $text_no; ?>
            <?php } ?>
          </label>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-2 control-label" for="input-total"><?php echo $entry_total; ?> <span class="help-block"><?php echo $help_total; ?></span></label>
        <div class="col-lg-10">
          <input type="text" name="paymate_total" value="<?php echo $paymate_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
        <div class="col-lg-10">
          <select name="paymate_order_status_id" id="input-order-status">
            <?php foreach ($order_statuses as $order_status) { ?>
            <?php if ($order_status['order_status_id'] == $paymate_order_status_id) { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
        <div class="col-lg-10">
          <select name="paymate_geo_zone_id" id="input-geo-zone">
            <option value="0"><?php echo $text_all_zones; ?></option>
            <?php foreach ($geo_zones as $geo_zone) { ?>
            <?php if ($geo_zone['geo_zone_id'] == $paymate_geo_zone_id) { ?>
            <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
        <div class="col-lg-10">
          <select name="paymate_status" id="input-status">
            <?php if ($paymate_status) { ?>
            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
            <option value="0"><?php echo $text_disabled; ?></option>
            <?php } else { ?>
            <option value="1"><?php echo $text_enabled; ?></option>
            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
        <div class="col-lg-10">
          <input type="text" name="paymate_sort_order" value="<?php echo $paymate_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="input-mini" />
        </div>
      </div>
    </form>
  </div>
</div>
<?php echo $footer; ?> 