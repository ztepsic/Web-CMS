<?=  $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); ?>

<form method="post" action="<?= base_url(); ?>admin/pages/page_modules/<?= $pageId; ?>">

<table border="1">
	<tr>
		<th>Grupa modula</th>
		<th>Prikazuje se na stranici</th>
		<th>Područje na layout-u</th>
		<th>Redoslijed grupe u zadanom području layout-a</th>
	</tr>

	<? foreach ($modulesGroups as $modulesGroup): ?>
	<? $pageModulesGroup = contains($modulesGroup->modules_group_id, $pageModulesGroups); ?>
	<tr>
		<td><?= $modulesGroup->modules_group_name; ?></td>

		<?
			$checked = false;
			if(!empty($pageModulesGroup) && $modulesGroup->modules_group_id == $pageModulesGroup->modules_group_id){
				$checked = true;
			}
		?>

		<td>
			<? if(!empty($pageModulesGroup)): ?>
			<input type="hidden" name="page_module_group_<?= $modulesGroup->modules_group_id; ?>" value="<?= $pageModulesGroup->page_modules_group_id; ?>"/>
			<? endif; ?>

			<input type="checkbox" name="modules_group_ids[]" value="<?= $modulesGroup->modules_group_id; ?>" <?= $checked ? "checked=\"true\"" : "" ?> />
		</td>
		<td>
			<select name="layout_position_id_<?= $modulesGroup->modules_group_id; ?>">
			<? foreach ($layoutPositions as $layoutPosition): ?>
			<?
				$selected = false;
				if(!empty($pageModulesGroup) && $pageModulesGroup->layout_position_id == $layoutPosition->layout_position_id){
					$selected = true;
				}
			?>
				<option value="<?= $layoutPosition->layout_position_id; ?>" <?= $selected ? "selected=\"true\"" : "" ?>><?= $layoutPosition->layout_position_name; ?></option>
			<? endforeach; ?>
			</select>
		</td>
		<td>
			<?
				$order = false;
				if(!empty($pageModulesGroup)){
					$order = true;
				}
			?>

			<input type="text" size="5" maxlength="40" name="page_modules_group_odrer_<?= $modulesGroup->modules_group_id; ?>" value="<?= $order ? $pageModulesGroup->page_modules_group_order : "" ?>" />
			<?= form_error('component_alias'); ?>
		</td>
	</tr>
	<? endforeach; ?>
	<tr>
		<td colspan="4">
			<input name="save" value="Spremi" type="submit" />
			<input name="cancel" value="Odustani" type="submit" />
		</td>
	</tr>
</table>

</form>

<?php

/**
 * Trazi da li postoji grupa modula u stranici
 *
 * @param int $moduleGroupId - identifikator grupe modula
 * @param std_class array $pageModulesGroups - grupe modula za neku stranicu
 * @return std_class - n
 */
function contains($moduleGroupId, $pageModulesGroups){
	foreach ($pageModulesGroups as $pageModulesGroup){
		if($moduleGroupId === $pageModulesGroup->modules_group_id){
			return $pageModulesGroup;
		}
	}

	return null;
}
?>