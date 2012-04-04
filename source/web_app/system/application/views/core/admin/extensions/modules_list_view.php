<? if($this->authorization->CheckPermission('create', 'zt_pages', $this->pageId)): ?>
<a href="<?= base_url(); ?>admin/extensions/module_create">Stvori novu instancu modula</a>
<? endif; ?>
<table border="1">
	<tr>
		<th>Akcije</th>
		<th>Id</th>
		<th>Instanca</th>
		<th>Opis</th>
		<th>Tip modula</th>
		<th>Vezana komponenta</th>
		<th>Objavljeno</th>
	</tr>
	<? foreach ($modules as $module): ?>
	<tr>
		<td>
		<? if($this->authorization->CheckPermission('update', 'zt_pages', $this->pageId)): ?>
			<a href="<?= base_url(); ?>admin/extensions/module_edit/<?= $module->module_id; ?>">Uredi</a>
		<? else: ?>
			Uredi
		<? endif; ?>
			<? if($this->authorization->CheckPermission('delete', 'zt_pages', $this->pageId)): ?>
			<a href="<?= base_url(); ?>admin/extensions/module_delete/<?= $module->module_id; ?>">Obriši</a>
			<? else: ?>
			Obriši
			<? endif; ?>
		</td>
		<td>
			<?= $module->module_id; ?>
		</td>
		<td>
			<?= $module->module_name; ?>
		</td>
		<td>
			<?= $module->module_description; ?>
		</td>
		<td>
			<?= $module->module_type_name; ?>
		</td>
		<td>
			<?= $module->component_name; ?>
		</td>
		<td>
			<?= $module->module_published ? "Da" : "Ne"	?>
		</td>
	</tr>
	<? endforeach; ?>
</table>