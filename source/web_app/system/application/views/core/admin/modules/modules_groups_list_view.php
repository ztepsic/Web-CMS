<? if($this->authorization->CheckPermission('create', 'zt_pages', $this->pageId)): ?>
<a href="<?= base_url(); ?>admin/modules/create">Dodaj novu grupu modula</a>
<? endif; ?>
<table border="1">
	<tr>
		<th>Akcije</th>
		<th>Grupa</th>
		<th>Objavljena</th>
	</tr>
	<? foreach ($modulesGroups as $modulesGroup): ?>
	<tr>
		<? if($modulesGroup->modules_group_locked): ?>
		<td>Elementi Uredi Obriši</td>
		<? else: ?>
		<td><a href="<?= base_url(); ?>admin/modules/groupitems/<?= $modulesGroup->modules_group_id; ?>">Elementi</a>
		<? if($this->authorization->CheckPermission('update', 'zt_pages', $this->pageId)): ?>
		 <a href="<?= base_url(); ?>admin/modules/edit/<?= $modulesGroup->modules_group_id; ?>">Uredi</a>
		 <? else: ?>
		 Uredi
		 <? endif; ?>
		 <? if($this->authorization->CheckPermission('delete', 'zt_pages', $this->pageId)): ?>
		  <a href="delete/<?= $modulesGroup->modules_group_id; ?>">Obriši</a></td>
		  <? else: ?>
		  Obriši
		  <? endif;?>
		<? endif; ?>
		<td><?= $modulesGroup->modules_group_name; ?></td>
		<td><?= $modulesGroup->modules_group_published ? "DA" : "NE" ?></td>
	</tr>
	<? endforeach; ?>

</table>