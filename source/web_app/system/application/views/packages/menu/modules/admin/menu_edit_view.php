<?=  $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); ?>

<form method="post" action="<?= base_url(); ?>admin/menus/menu_edit/<?= $menu->menu_id; ?>">

<table>
	<tr>
		<td>
			Naziv
		</td>
		<td>
			<input type="text" name="menu_name" size="40" maxlength="40" value="<?= set_value('menu_name') ? set_value('menu_name') : $menu->menu_name ?>" />
		</td>
	</tr>
	<tr>
		<td>
			Opis
		</td>
		<td>
			<textarea cols="30" name="menu_description" ><?= !empty($menu->menu_description) ? $menu->menu_description : ""?></textarea>
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