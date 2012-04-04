<?=  $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); ?>

<form method="post" action="<?= base_url(); ?>admin/simplepages/simplepage_create">

<table>
	<tr>
		<td>
			Alias
		</td>
		<td>
			<input type="text" name="simple_page_alias" size="40" maxlength="40" value="<?= set_value("simple_page_alias") ? set_value("simple_page_alias") : "" ?>"/>
		</td>
	</tr>
	<tr>
		<td>
			Naziv
		</td>
		<td>
			<input type="text" name="simple_page_name" size="40" maxlength="40" value="<?= set_value("simple_page_name") ? set_value("simple_page_name") : "" ?>" />
		</td>
	</tr>
	<tr>
		<td>
			Sadržaj
		</td>
		<td>
			<textarea cols="30" name="simple_page_body" ></textarea>
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