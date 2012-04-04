<?=  $this->form_validation->set_error_delimiters('<div class="error">', '</div>'); ?>

<form method="post" action="<?= base_url(); ?>admin/site_settings">
<table>
	<tr>
		<td>Naziv stranice:</td>
		<td>
		<?
			$siteName = "";
			if(set_value('general_setting_site_name')){
				$siteName = set_value('general_setting_site_name');
			} elseif(!empty($siteSetting['general_setting']->site_name)){
				$siteName = $siteSetting['general_setting']->site_name;
			} else {
				$siteName = "";
			}
		?>
			<input type="text" size="40" maxlength="40" name="general_setting_site_name" value="<?= $siteName; ?>">
			<?= form_error('general_setting_site_name'); ?>
		</td>
	</tr>
	<tr>
		<td>Opis stranice:</td>
		<td>
				<?
			$metaDescription = "";
			if(set_value('metadata_setting_description')){
				$metaDesription = set_value('metadata_setting_description');
			} elseif(!empty($siteSetting['metadata_setting']->description)){
				$metaDescription = $siteSetting['metadata_setting']->description;
			} else {
				$metaDescription = "";
			}
		?>
			<textarea cols="30" name="metadata_setting_description" ><?= $metaDescription; ?></textarea>
			<?= form_error('metadata_setting_description'); ?>
		</td>
	</tr>
	<tr>
		<td>Ključne riječi:</td>
		<td>
		<?
			$metaKeywords = "";
			if(set_value('metadata_setting_description')){
				$metaKeywords = set_value('metadata_setting_description');
			} elseif(!empty($siteSetting['metadata_setting']->keywords)){
				$metaKeywords = $siteSetting['metadata_setting']->keywords;
			} else {
				$metaKeywords = "";
			}
		?>
			<textarea cols="30" name="metadata_setting_keywords" ><?= $metaKeywords; ?></textarea>
			<?= form_error('metadata_setting_keywords'); ?>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<input name="save" value="Spremi" type="submit" />
			<input name="cancel" value="Odustani" type="submit" />
		</td>
	</tr>
</table>

</form>