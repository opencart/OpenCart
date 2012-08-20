<?php if ($address_match) { ?>
    <form>
        <?php if ($country_code == 'DEU') { ?>
            <div class="buttons">
                <input type="checkbox" name="deu_toc" value="1" />
                Mit der Übermittlung der für die Abwicklung des Rechnungskaufes und einer Identitäts - und Bonitätsprüfung erforderlichen 
                Daten an Klarna bin ich einverstanden. Meine <a href="https://online.klarna.com/consent_de.yaws" target="_blank">Einwilligung</a> kann ich jederzeit mit Wirkung für die Zukunft widerrufen.
            </div>
        <?php } elseif ($country_code == 'NLD') { ?>
            <img src="http://www.afm.nl/~/media/Images/wetten-regels/kredietwaarschuwing/balk_afm3-jpg.ashx" /> 
        <?php } ?>
    </form>
<?php } else { ?>
    <div class="warning">
        <?php echo $error_address_match ?>
    </div>
<?php } ?>
<!--
<h2><?php echo $text_information; ?></h2>
<div id="payment" class="content">
  <p><?php echo $text_additional; ?></p>
  <table class="form">
    <tr>
      <td><?php echo $entry_gender; ?></td>
      <td><input type="radio" name="gender" value="M" id="male" checked="checked" />
        <label for="male"><?php echo $text_male; ?></label>
        <input type="radio" name="gender" value="F" id="female" />
        <label for="female"><?php echo $text_female; ?></label></td>
    </tr>
    <tr>
      <td><?php if ($iso_code_2 == 'SE') { ?>
        <div class="required">*</div>
        <?php } ?>
        <?php echo $entry_pno; ?></td>
      <td><input type="text" name="pno" value="" /></td>
    </tr>
    <tr>
      <td><?php echo $entry_cellno; ?></td>
      <td><input type="text" name="cellno" value="" /></td>
    </tr>
    <tr>
      <td><?php echo $entry_house_no; ?></td>
      <td><input type="text" name="house_no" value="" size="3" /></td>
    </tr>
    <tr>
      <td><?php echo $entry_house_ext; ?></td>
      <td><input type="text" name="house_ext" value="" size="3" /></td>
    </tr>
  </table>
</div>
<div class="buttons">
  <div class="right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="button" />
  </div>
</div>
<script type="text/javascript">
$('#button-confirm').bind('click', function() {
	$.ajax({
		url: 'index.php?route=payment/klarna_invoice/send',
		type: 'post',
		data: $('#payment input[type=\'text\'], #payment input[type=\'password\'], #payment input[type=\'checkbox\']:checked, #payment input[type=\'radio\']:checked, #payment input[type=\'hidden\'], #payment select'),
		dataType: 'json',		
		beforeSend: function() {
			$('#button-confirm').attr('disabled', true);
			$('#payment').before('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('#button-confirm').attr('disabled', false);
			$('.attention').remove();
		},		
		success: function(json) {
			$('.warning, .error').remove();
			
			if (json['error']) {
				$('#payment').prepend('<div class="warning">' + json['error'] + '</div>');
			}
			
			if (json['redirect']) {
				location = json['redirect'];
			}
		}
	});
});
</script> 
-->