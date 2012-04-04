<a href="<?= base_url(); ?>admin/menus/menu_create">Dodaj novi izbornik</a>
<table border="1">
	<tr>
		<th>Akcije</th>
		<th>Izbornik</th>
		<th>Elementi</th>
	</tr>
	<? foreach ($menus as $menu): ?>
	<tr>
		<td>
			<a href="<?= base_url(); ?>admin/menus/menu_edit/<?= $menu->menu_id; ?>">Uredi</a>
			<a href="<?= base_url(); ?>admin/menus/menu_delete/<?= $menu->menu_id; ?>">Obriši</a>
		</td>
		<td><?= $menu->menu_name; ?></td>
		<td>
			<a href="<?= base_url(); ?>admin/menus/menu_items/<?= $menu->menu_id; ?>">Elementi</a>
		</td>
	</tr>
	<? endforeach; ?>

</table>