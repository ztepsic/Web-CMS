<?=  $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); ?>

<form method="post" action="<?= base_url(); ?>/admin/extensions/component_create">

<table>
	<tr>
		<td>Naziv:</td>
		<td>
			<input type="text" size="40" maxlength="40" name="component_name" value="<?= set_value('component_name') ? set_value('component_name') : ""  ?>">
			<?= form_error('component_name'); ?>
		</td>
	</tr>
	<tr>
		<td>Opis:</td>
		<td>
			<textarea cols="30" name="component_description" ><?= set_value('component_description') ? set_value('component_description') : ""  ?></textarea>
			<?= form_error('component_description'); ?>
		</td>
	</tr>
	<tr>
		<td>Alias:</td>
		<td>
			<input type="text" size="40" maxlength="40" name="component_alias" value="<?= set_value('component_alias') ? set_value('component_alias') : ""  ?>">
			<?= form_error('component_alias'); ?>
		</td>
	</tr>
	<tr>
		<td>Tip komponente:</td>
		<td>
			<select name="component_type_id">
			<? foreach ($componentTypes as $componentType): ?>
				<option value="<?= $componentType->component_type_id; ?>"><?= $componentType->component_type_name; ?></option>
			<? endforeach; ?>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<input name="next-step" value="Slijedeći korak" type="submit" />
			<input name="cancel" value="Odustani" type="submit" />
		</td>
	</tr>
</table>

</form>
