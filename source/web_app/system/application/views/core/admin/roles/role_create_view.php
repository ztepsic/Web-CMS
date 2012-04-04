<?=  $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); ?>

<form method="post" action="<?= base_url(); ?>admin/roles/create">
<table>
	<tr>
		<td>Naziv:</td>
		<td>
			<input type="text" size="40" maxlength="40" name="role_name" value="<?= set_value('role_name') ? set_value('role_name') : "" ?>">
			<?= form_error('role_name'); ?>
		</td>
	</tr>
	<tr>
		<td>Opis:</td>
		<td>
			<textarea cols="30" rows="10" name="role_description"><?= set_value('role_description') ? set_value('role_description') : "" ?></textarea>
			<?= form_error('role_description'); ?>
		</td>
	</tr>
	<tr>
		<td>Roditeljska uloga:</td>
		<td>
			<select name="role_parent_id">
				<option value="-">----</option>
				<? foreach ($roles as $role): ?>
				<? if($roleParentId == $role->role_id): ?>
				<option selected="true"  value="<?= $role->role_id; ?>"><? indent($role->depth); ?><?= $role->role_name; ?></option>
				<? elseif(!empty($roleId) && $roleId == $role->role_id): ?>
				<? // nemoj nista ispisat jer nemozes stavit ulogu kao roditelja samom sebi ?>
				<? else: ?>
				<option  value="<?= $role->role_id; ?>"><? indent($role->depth); ?><?= $role->role_name; ?></option>
				<? endif; ?>
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

<?php
	function indent($count){
		for($i=0; $i < $count; $i++){
			echo "-&nbsp;";
		}

	}
?>