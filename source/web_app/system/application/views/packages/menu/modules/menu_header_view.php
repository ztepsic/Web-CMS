<div id="navigation-top">
    <ul id="main-navigation" class="navigation-list">
    <? foreach ($menuItems as $menuItem): ?>
    <?
		if(empty($menuItem->page_link)){
			$menuItemLink = $menuItem->page_link_generated;
		} else {
			$menuItemLink = $menuItem->page_link;
		}
    ?>
        <li <? if(comparePageLinks($pageLink, $menuItemLink) == 0):?> class="current" <? endif; ?>>
            <a href="<?= site_url($menuItemLink); ?>" title="<?= $menuItem->menu_item_name . " - " . $menuItem->menu_item_description; ?>">
            	<?= $menuItem->menu_item_name; ?>
            	<em>
            		<? if(!empty($menuItem->menu_item_description)): ?>
            			<?= $menuItem->menu_item_description; ?>
            		<? else: ?>
            			&nbsp;
            		<? endif; ?>
            	</em>
            </a>
        </li>
    <? endforeach; ?>
    </ul>
</div>
<!-- #navigation-top -->
<div class="clear"></div>
