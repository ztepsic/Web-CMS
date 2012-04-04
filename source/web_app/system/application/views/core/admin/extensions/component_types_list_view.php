<table border="1">
	<tr>
		<th>Akcije</th>
		<th>Id</th>
		<th>Tip komponente</th>
		<th>Opis komponente</th>
		<th>Višestruke instance</th>
	</tr>
	<? foreach ($componentTypes as $componentType): ?>
	<tr>
		<td>
			<? if($this->authorization->CheckPermission('delete', 'zt_pages', $this->pageId)): ?>
				<a href="<?= base_url();?>admin/extensions/componenttype_delete/<?= $componentType->component_type_id; ?>">Deinstaliraj</a>
			<? else: ?>
				Deinstaliraj
			<? endif; ?>
		</td>
		<td>
			<?= $componentType->component_type_id; ?>
		</td>
		<td>
			<?= $componentType->component_type_name; ?>
		</td>
		<td>
			<?= $componentType->component_type_description; ?>
		</td>
		<td>
			<?= $componentType->component_type_mulltiple_instances ? "Da" : "Ne" ?>
		</td>
	</tr>
	<? endforeach; ?>
</table>