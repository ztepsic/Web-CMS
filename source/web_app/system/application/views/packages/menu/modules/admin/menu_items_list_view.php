<a href="<?= base_url(); ?>admin/menus/menu_item_create/<?= $menuId; ?>">Dodaj novi element izbornika</a>
<table border="1">
	<tr>
		<th>Akcije</th>
		<th>Element izbornika</th>
	</tr>
	<? foreach ($menuItems as $menuItem): ?>
	<tr>
		<td>
			<a href="<?= base_url(); ?>admin/menus/menu_item_edit/<?= $menuItem->menu_item_id; ?>">Uredi</a>
			<a href="<?= base_url(); ?>admin/menus/menu_item_delete/<?= $menuItem->menu_item_id; ?>">Obriši</a>
		</td>
		<td><?= $menuItem->menu_item_name; ?></td>
	</tr>
	<? endforeach; ?>

</table>