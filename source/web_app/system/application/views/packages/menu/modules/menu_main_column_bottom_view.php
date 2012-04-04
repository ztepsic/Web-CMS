<div id="navigation-bottom">
    <ul class="floatright navigation-list">
	<? foreach ($menuItems as $key => $menuItem): ?>
        <li>
            <a href="<?= site_url($menuItem->page_link); ?>" title="<?= $menuItem->menu_item_name . " - " . $menuItem->menu_item_description; ?>">
	            <?= $menuItem->menu_item_name; ?>
            </a>
        </li>
        <? if($key+1 < sizeof($menuItems)): ?>
        <li>
            |
        </li>
        <? endif; ?>
    <? endforeach; ?>
    </ul>
</div>
<!-- #navigation-bottom -->
<div class="clear"></div>
