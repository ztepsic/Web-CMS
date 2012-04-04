<a href="<?= base_url(); ?>admin/users/create">Dodaj novog korisnika</a>
<table border="1">
	<tr>
		<th>Akcije</th>
		<th>Korisničko ime</th>
		<th>Ime i Prezime</th>
	</tr>
	<? foreach ($users as $user): ?>
	<tr>
		<td>
		<? if($this->authorization->CheckPermission('update', 'zt_pages', $this->pageId)): ?>
			<a href="<?= base_url(); ?>admin/users/edit/<?= $user->user_id; ?>">Uredi</a>
		<? else: ?>
		Uredi
		<? endif; ?>
		<? if($this->authorization->CheckPermission('delete', 'zt_pages', $this->pageId)): ?>
		 <a href="<?= base_url(); ?>admin/users/delete/<?= $user->user_id; ?>">Obriši</a>
		<? else: ?>
		Obriši
		<? endif; ?>
		 </td>
		<td><?= $user->user_username; ?></td>
		<td><?= $user->user_name; ?></td>
	</tr>
	<? endforeach; ?>

</table>