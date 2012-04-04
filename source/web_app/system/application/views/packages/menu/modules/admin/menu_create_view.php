<?=  $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); ?>

<form method="post" action="<?= base_url(); ?>admin/menus/menu_create">

<table>
	<tr>
		<td>
			Naziv
		</td>
		<td>
			<input type="text" name="menu_name" size="40" maxlength="40" />
		</td>
	</tr>
	<tr>
		<td>
			Opis
		</td>
		<td>
			<textarea cols="30" name="menu_description" ></textarea>
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