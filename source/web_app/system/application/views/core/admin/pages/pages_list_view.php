<script>
function go(id){
	var url = "<?= base_url(); ?>admin/pages/";
	if(id){
		window.open(url+id,'_top');
	}

}
</script>
<select onchange="go(this.options[this.selectedIndex].value)" name="kategorija">
<option value="">Odaberi prikaz</option>
<option value="0">--- Javne stranice</option>
<option value="1">--- Administratorske stranice</option>
<option value="2">--- Prikaži sve stranice</option>
</select>
<table border="1">
	<tr>
		<th>Akcije</th>
		<th>Id</th>
		<th>Stranica</th>
		<th>Dozvole</th>
		<th>Moduli</th>
	</tr>
	<? foreach ($pages as $page): ?>
	<tr>
		<td>
			<? if($page->page_locked_by_component): ?>
				Obriši
			<? elseif($this->authorization->CheckPermission('delete', 'zt_pages', $this->pageId)): ?>
				Obriši
			<? else: ?>
				<a href="<?= base_url(); ?>admin/pages/page_delete/<?= $page->page_id; ?>">Obriši</a>
			<? endif; ?>

		</td>
		<td>
			<?= $page->page_id; ?>
		</td>
		<td>
			<?= $page->page_name; ?>
		</td>
		<td>
		<? if($this->authorization->CheckPermission('read', 'zt_pages', $this->pageId) &&
		$this->authorization->CheckPermission('update', 'zt_pages', $this->pageId)): ?>
			<a href="<?= base_url(); ?>admin/pages/page_permissions/<?= $page->page_id; ?>">Dozvole</a>
			<? else: ?>
			Dozvole
			<? endif; ?>
		</td>
		<td>
				<? if($this->authorization->CheckPermission('read', 'zt_pages', $this->pageId) &&
		$this->authorization->CheckPermission('update', 'zt_pages', $this->pageId)): ?>
			<a href="<?= base_url(); ?>admin/pages/page_modules/<?= $page->page_id; ?>">Moduli</a>
						<? else: ?>
			Moduli
			<? endif; ?>
		</td>
	</tr>
	<? endforeach; ?>
</table>