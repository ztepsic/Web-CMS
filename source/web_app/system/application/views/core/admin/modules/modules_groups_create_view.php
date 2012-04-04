<?=  $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); ?>

<form method="post" action="<?= base_url(); ?>/admin/modules/create">
<table>
	<tr>
		<td>Naziv:</td>
		<td>
			<input type="text" size="40" maxlength="40" name="modules_group_name" value="<?= set_value('modules_group_name') ? set_value('modules_group_name') : "" ?>">
			<?= form_error('modules_group_name'); ?>
		</td>
	</tr>
	<tr>
		<td>Objavi:</td>
		<td><input type="checkbox" name="modules_group_published" value="1" <?= set_value('modules_group_published') ? "checked=\"true\"" : "" ?> /></td>
	</tr>
	<tr>
		<td colspan="2">
			<input type="submit" value="Spremi">
		</td>
	</tr>
</table>

</form>
