<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="hr" xml:lang="hr">
    <head>
        <title><?= !empty($head->site_name) ? $head->site_name . " | " : "" ?><?= !empty($head->title) ? $head->title : "" ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="Content-Language" content="hr, croatian" />
        <?= meta($head->meta); ?>

        <link href="<?= base_url(); ?>css/main.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <div id="main-frame">
        	<div id="header">
        		<a href="<?= base_url(); ?>" title="Povratak na naslovnu stranicu">
	        		<img class="floatleft" src="<?= base_url(); ?>img/globe_48.png" />
        		</a>

	            <? if (!empty($header)): ?>
	            	<?= $header; ?>
	            <? endif; ?>

            </div>
			<!-- #header -->
            <div id="content">

                <? if (!empty($content_top)): ?>
	            	<?= $content_top; ?>
	            <? endif; ?>

                <div id="main-column" class="floatleft">
					<? if (!empty($main_column)): ?>
		            	<?= $main_column; ?>
		            <? endif; ?>
                </div>
                <!-- #main-column -->


                <div id="main-column-bottom" class="floatleft">
                	<? if (!empty($main_column_bottom)): ?>
	            		<?= $main_column_bottom; ?>
	            	<? endif; ?>
                </div>
                <!-- #main-column-bottom -->

                <div id="side-column" class="floatright">
					<? if (!empty($side_column)): ?>
	            		<?= $side_column; ?>
	            	<? endif; ?>

                </div>
                <!-- #side-column -->
                <div class="clear">
                </div>
            </div>
            <!-- #content -->
            <div id="footer">
            	 <div class="floatleft">
                    
                </div>
                <? if (!empty($footer)): ?>
	            		<?= $footer; ?>
	            	<? endif; ?>
                <div class="support">
                    <p class="floatleft">
                        Ova stranica podržava:
                    </p>
                    <a href="http://validator.w3.org/check?uri=<?= base_url() . $this->uri->uri_string(); ?>">XHTML</a>
                    <a href="http://jigsaw.w3.org/css-validator/validator?uri=http://www.ztepsic.com/css/main.css">CSS</a>
                    <a href="http://feeds.feedburner.com/ztepsic">RSS</a>
                    <span class="floatright">&copy; Copyright <?= date("Y", time()); ?>. </span>
                </div>
                <!-- .support -->
            </div>
            <!-- #footer -->
        </div>
        <!-- #main-frame -->
    </body>
</html>
