<?=  $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); ?>

<form method="post" action="<?= base_url(); ?>admin/menus/menu_item_create/<?= $menuId; ?>">

<table>
	<tr>
		<td>
			Naziv
		</td>
		<td>
			<input type="text" name="menu_item_name" size="40" maxlength="40" value="<?= set_value("menu_item_name") ? set_value("menu_item_value") : "" ?>"/>
		</td>
	</tr>
	<tr>
		<td>
			Opis
		</td>
		<td>
			<textarea cols="30" name="menu_item_description" ></textarea>
		</td>
	</tr>
	<tr>
		<td>
			Stranice
		</td>
		<td>
			<select name="page_id">
			<? foreach ($pages as $page): ?>
				<option value="<?= $page->page_id; ?>" ><?= $page->page_name; ?></option>
			<? endforeach; ?>
			</select>
		</td>
	</tr>
	<tr>
		<td>
			Redosljed
		</td>
		<td>
			<input type="text" name="menu_item_order" size="10" maxlength="10" />
		</td>
	</tr>
	<tr>
		<td>
			Objevljeno
		</td>
		<td>
			<input type="checkbox" name="menu_item_published" value="1"/>
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