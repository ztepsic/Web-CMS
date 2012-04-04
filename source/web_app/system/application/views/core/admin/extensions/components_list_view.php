<? if($this->authorization->CheckPermission('create', 'zt_pages', $this->pageId)): ?>
<a href="<?= base_url(); ?>admin/extensions/component_create">Stvori novu instancu komponente</a>
<? endif; ?>
<table border="1">
	<tr>
		<th>Akcije</th>
		<th>Id</th>
		<th>Instanca</th>
		<th>Opis</th>
		<th>Alias</th>
		<th>Tip komponente</th>
		<th>Aministracija komponente</th>
	</tr>
	<? foreach ($components as $component): ?>
	<tr>
		<td>
		<? if($this->authorization->CheckPermission('delete', 'zt_pages', $this->pageId)): ?>
			<a href="<?= base_url(); ?>admin/extensions/component_delete/<?= $component->component_id; ?>">Obriši</a>
		<? else: ?>
			Obriši
		<? endif; ?>
		</td>
		<td>
			<?= $component->component_id; ?>
		</td>
		<td>
			<?= $component->component_name; ?>
		</td>
		<td>
			<?= $component->component_description; ?>
		</td>
		<td>
			<?= $component->component_alias; ?>
		</td>
		<td>
			<?= $component->component_type_name; ?>
		</td>
		<td>
			<? if($component->component_type_alias != "home"): ?>
			<a href="<?= base_url(); ?>admin/<?= $component->component_type_alias; ?>">Administracija <?= $component->component_name; ?></a>
			<? endif; ?>
		</td>
	</tr>
	<? endforeach; ?>
</table>