<a href="<?= base_url(); ?>admin/simplepages/simplepage_create">Dodaj novu stranicu</a>
<table border="1">
	<tr>
		<th>Akcije</th>
		<th>Jednostavna stranica</th>
	</tr>
	<? foreach ($simplePages as $simplePage): ?>
	<tr>
		<td>
			<a href="<?= base_url(); ?>admin/simplepages/simplepage_edit/<?= $simplePage->simple_page_id; ?>">Uredi</a>
			<a href="<?= base_url(); ?>admin/simplepages/simplepage_delete/<?= $simplePage->simple_page_id; ?>">Obriši</a>
		</td>
		<td><?= $simplePage->simple_page_name; ?></td>
	</tr>
	<? endforeach; ?>

</table>