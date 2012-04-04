<?=  $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); ?>

<form method="post" action="<?= base_url(); ?>admin/extensions/module_create">

<table>
	<tr>
		<td>Naziv:</td>
		<td>
			<input type="text" size="40" maxlength="40" name="module_name" value="<?= set_value('module_name') ? set_value('module_name') : ""  ?>">
			<?= form_error('module_name'); ?>
		</td>
	</tr>
	<tr>
		<td>Opis:</td>
		<td>
			<textarea cols="30" name="module_description" ><?= set_value('module_description') ? set_value('module_description') : ""  ?></textarea>
			<?= form_error('module_description'); ?>
		</td>
	</tr>
	<tr>
		<td>Tip modula:</td>
		<td>
			<select name="module_type_id">
			<? foreach ($moduleTypes as $moduleType): ?>
				<option value="<?= $moduleType->module_type_id; ?>"><?= $moduleType->module_type_name; ?></option>
			<? endforeach; ?>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<input name="save" value="Spremi" type="submit" />
			<input name="cancel" value="Odustani" type="submit" />
		</td>
	</tr>
</table>

</form>