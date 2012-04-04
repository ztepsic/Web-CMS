<?=  $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); ?>

<form method="post" action="<?= base_url(); ?>admin/extensions/component_mehtods_create">
<table>
	<? foreach ($componentTypeMethods as $componentTypeMethod): ?>
	<tr>
		<td>Alias za <b><?= $componentTypeMethod->component_type_method_name; ?></b> metodu:</td>
		<td>
			<input type="text" size="40" maxlength="40" name="component_type_method_<?= $componentTypeMethod->component_type_method_id; ?>">
		</td>
	</tr>
	<? endforeach; ?>
	<tr>
		<td colspan="2">
			<input name="save" value="Spremi" type="submit" />
			<input name="cancel" value="Odustani" type="submit" />
		</td>
	</tr>
</table>

</form>
