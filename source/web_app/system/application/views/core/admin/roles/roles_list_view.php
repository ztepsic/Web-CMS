<? if($this->authorization->CheckPermission('create', 'zt_pages', $this->pageId)): ?>
<a href="<?= base_url(); ?>admin/roles/create">Dodaj novu ulogu</a>
<? endif; ?>
<table border="1">
	<tr>
		<th>Akcije</th>
		<th>Uloga</th>
		<th>Opis</th>
	</tr>
	<? foreach ($roles as $role): ?>
	<tr>
		<? if($role->role_locked): ?>
		<td>Uredi Obriši</td>
		<? elseif($this->authorization->CheckPermission('delete', 'zt_pages', $this->pageId)): ?>

				<td><a href="<?= base_url(); ?>admin/roles/edit/<?= $role->role_id; ?>">Uredi</a>
		<a href="<?= base_url(); ?>admin/roles/delete/<?= $role->role_id; ?>">Obriši</a></td>
		<? else: ?>
			<td>Uredi Obriši</td>
		<? endif; ?>
		<td><? indent($role->depth); ?><?= $role->role_name; ?></td>
		<td><?= $role->role_description; ?></td>
	</tr>
	<? endforeach; ?>

</table>

<?php
	function indent($count){
		for($i=0; $i < $count; $i++){
			echo "-&nbsp;";
		}

	}
?>