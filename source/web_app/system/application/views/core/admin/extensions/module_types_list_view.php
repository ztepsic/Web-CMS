<table border="1">
	<tr>
		<th>Akcije</th>
		<th>Id</th>
		<th>Tip modula</th>
		<th>Opis modula</th>
		<th>Višestruke Instance</th>
		<th>Tip komponente</th>
	</tr>
	<? foreach ($moduleTypes as $moduleType): ?>
	<tr>
			 <td>
			 <? if(empty($moduleType->component_type_id) && $this->authorization->CheckPermission('delete', 'zt_pages', $this->pageId)): ?>
			 					 <a href="<?= base_url();?>admin/extensions/moduletype_delete/<?= $moduleType->module_type_id; ?>">Deinstaliraj</a>
			 <? else: ?>
			 	Deinstaliraj
			 <? endif; ?>
			</td>
		<td>
			<?= $moduleType->module_type_id; ?>
		</td>
		<td>
			<?= $moduleType->module_type_name; ?>
		</td>
		<td>
			<?= $moduleType->module_type_description; ?>
		</td>
		<td>
			<?= $moduleType->module_type_mulltiple_instances ? "Da" : "Ne" ?>
		</td>
		<td>
			<?= $moduleType->component_type_name ? $moduleType->component_type_name : " " ?>
		</td>
	</tr>
	<? endforeach; ?>
</table>