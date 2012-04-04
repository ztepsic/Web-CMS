<?=  $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); ?>

<form method="post" action="<?= base_url(); ?>admin/pages/page_permissions/<?= $pageId; ?>">

<table border="1">
	<tr>
		<th>Uloga</th>
		<th>Akcije</th>
	</tr>
	<? foreach ($roles as $role): ?>
	<? if($role->role_id != -1): ?>
	<tr>
		<td>
			<input type="hidden" name="role_<?= $role->role_id; ?>" value="<?= $role->role_id; ?>" />
			<?= $role->role_name; ?>
		</td>
		<td>
		<? foreach ($actions as $action): ?>
			<input type="checkbox" name="actions_<?= $role->role_id; ?>[]" value="<?= $action->action_id; ?>" <?= havePermission($role->role_id, $action->action_id, $rolePermissions) ? "checked=\"true\"" : "" ?>/><?= $action->action_name; ?> <br />
		<? endforeach; ?>
		</td>
	</tr>
	<? endif; ?>
	<? endforeach; ?>
	<tr>
		<td colspan="2">
			<input name="save" value="Spremi" type="submit" />
			<input name="cancel" value="Odustani" type="submit" />
		</td>
	</tr>
</table>

</form>

<?
	/**
	 * Provjerava da li za ulogu i akciju postoji dozvola
	 *
	 * @param unknown_type $roleId
	 * @param unknown_type $actionId
	 * @param std_class array $rolePermissions
	 * @return boolean - true ako postoji, false iance
	 */
	function havePermission($roleId, $actionId, $rolePermissions){
		foreach ($rolePermissions as $rolePermission){
			if($rolePermission->role_id == $roleId &&
				$rolePermission->action_id == $actionId
			) {
				return true;
			}
		}

		return false;
	}

?>