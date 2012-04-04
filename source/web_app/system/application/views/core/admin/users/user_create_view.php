<?=  $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); ?>

<form method="post" action="<?= base_url(); ?>admin/users/create">
<table>
	<tr>
		<td>Ime i prezime:</td>
		<td>
			<input type="text" size="40" maxlength="40" name="user_name" value="<?= set_value('user_name'); ?>">
			<?= form_error('user_name'); ?>
		</td>
	</tr>
	<tr>
		<td>Korisničko ime:</td>
		<td>
			<input type="text" size="40" maxlength="40" name="user_username" value="<?= set_value('user_username'); ?>">
			<?= form_error('user_username'); ?>
		</td>
	</tr>
	<tr>
		<td>Lozinka:</td>
		<td>
			<input type="password" size="10" maxlength="10" name="user_password" value="<?= set_value('user_password'); ?>">
			<?= form_error('user_password'); ?>
		</td>
	</tr>
	<tr>
		<td>Email:</td>
		<td>
			<input type="text" size="40" maxlength="40" name="user_email" value="<?= set_value('user_email'); ?>">
			<?= form_error('user_email'); ?>
		</td>
	</tr>
	<tr>
		<td>Aktivan:</td>
		<td><input type="checkbox" name="user_active" value="1"></td>
	</tr>
	<tr>
		<td colspan="2">
			<input name="save" value="Spremi" type="submit" />
			<input name="cancel" value="Odustani" type="submit" />
		</td>
	</tr>
</table>
</form>