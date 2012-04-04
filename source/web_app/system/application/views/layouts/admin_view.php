<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="hr" xml:lang="hr">
    <head>
        <title><?= !empty($head->site_name) ? $head->site_name . " | " : "" ?><?= !empty($head->title) ? $head->title : "" ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="Content-Language" content="hr, croatian" />
        <meta name="Author" content="Željko Tepšić - ztepsic.com" />
        <meta name="Copyright" content="Copyright © 2008 ztepsic.com"/>
        <meta name="DC.Title" content="ztepsic.com | Željko Tepšić"/>
        <?= meta($head->meta); ?>

        <link href="<?= base_url(); ?>/css/admin/admin.css" rel="stylesheet" type="text/css"/>

    </head>
    <body>
        <div id="header">
            <div class="main-frame">
            	<div class="floatleft">
					<h1>
					<?= !empty($head->site_name) ? $head->site_name : "" ?>
					</h1>
            	</div>
				<div id="account" class="floatright">
					<ul>
						<li>
							<?= $this->session->userdata('user_name'); ?>
						</li>
						<li>
							|
						</li>
						<li>
							<a href="<?= base_url(); ?>admin/logout">Odjava</a>
						</li>
					</ul>
				</div>
				<div class="clear"></div>
                <div id="main-navigation-top">
                    <ul>
                        <li class="selected_">
                            <a href="<?= base_url(); ?>admin">Naslovnica</a>
                        </li>
                        <li>
                            <a href="<?= base_url(); ?>admin/site_settings">Postavke</a>
                        </li>
                        <li>
                            <a href="<?= base_url(); ?>admin/menus">Izbornici</a>
                        </li>
                        <li>
                            <a href="<?= base_url(); ?>admin/pages">Stranice</a>
                        </li>
                         <li>
                            <a href="<?= base_url(); ?>admin/modules">Grupa modula</a>
                        </li>
                        <li>
                            <a href="<?= base_url(); ?>admin/extensions/modules">Moduli</a>
                        </li>
                        <li>
                            <a href="<?= base_url(); ?>admin/extensions/components">Komponente</a>
                        </li>
                        <li>
                            <a href="<?= base_url(); ?>admin/extensions/moduletypes">Tipovi Modula</a>
                        </li>
                        <li>
                            <a href="<?= base_url(); ?>admin/extensions/componenttypes">Tipovi komponenata</a>
                        </li>
                        <li>
                            <a href="<?= base_url(); ?>admin/roles">Uloge</a>
                        </li>
						<li>
                            <a href="<?= base_url(); ?>admin/users">Korisnici</a>
                        </li>
                        <li><a href="<?= base_url(); ?>admin/installer">Instalacija</a></li>
                    </ul>
					<div class="clear"></div>
                </div>
                <!-- main-navigation-top -->
            </div>
            <!-- .main-frame -->
        </div>
        <!-- #header -->
        <div id="title-holder">
            <div class="main-frame">
            	<h2><?= !empty($head->title) ? $head->title : "" ?></h2>
            </div>
            <!-- .main-frame -->
        </div>
        <!-- #title-holder -->
        <div id="content">
            <div class="main-frame">
                <?= $main_column; ?>
            </div>
            <!-- main-frame -->
        </div>
        <!-- #content -->
        <div id="footer">
        </div>
        <!-- #footer -->
    </body>
</html>
