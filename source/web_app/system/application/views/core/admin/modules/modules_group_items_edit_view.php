<form method="post" action="<?= base_url(); ?>admin/modules/groupitems/<?= $modulesGroupId ?>">
<table border="1">
	<tr>
		<th>Moduli</th>
	</tr>

	<tr>
		<td>
			<? foreach ($modules as $module): ?>
				<? $checked = false; ?>
				<? foreach ($currentModuleItems as $currentModuleItem): ?>
				<?
					if($currentModuleItem->module_id == $module->module_id){
						$checked = true;
						break;
					}
				?>
				<? endforeach; ?>
			<input type="checkbox" name="modules[]" value="<?= $module->module_id; ?>" <?= $checked ? "checked=\"true\"" : "" ?>/><?= $module->module_name; ?> <br />
			<? endforeach; ?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<input type="hidden" name="post" value="1" />
			<input type="submit" value="Spremi">
		</td>
	</tr>

</table>

</form>