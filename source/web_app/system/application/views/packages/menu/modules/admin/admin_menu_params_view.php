Odaberite izbornik koji će predstavljati instanca modula:<br/>
<select name="menu_id">
<? foreach ($menus as $menu): ?>
<?
	$selected = "";
	if(!empty($currentMenuId) && $menu->menu_id == $currentMenuId){
		$selected = "selected=\"true\"";
	}
?>
	<option value="<?= $menu->menu_id; ?>" <?= $selected; ?>><?= $menu->menu_name; ?></option>
<? endforeach; ?>
</select>