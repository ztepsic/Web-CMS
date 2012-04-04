<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="hr" xml:lang="hr">
    <head>
        <title><?= !empty($siteTitle) ? $siteTitle . " | " : "" ?> Administracija - Prijava </title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta http-equiv="Content-Language" content="hr, croatian" />
        <link href="<?= base_url(); ?>css/admin/login.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
    	<div id="main-frame">
    		<div id="login-frame-middle">
    			<div id="login-frame-top" >
    				<div class="content">
    					<h1><?= !empty($siteTitle) ? $siteTitle : "" ?></h1>

    					<? if(validation_errors() || $session_id = $this->session->userdata('login')): ?>
    					<div class="error">
    						<h2>Greške prilikom prijave</h2>
    						<ul>
    							<?= validation_errors(); ?>
    							<li><?= $session_id = $this->session->userdata('login'); ?></li>
    							<? $this->session->unset_userdata('login'); ?>
    						</ul>
    					</div>
    					<? endif; ?>



                        <form  action="<?= base_url();?>admin/login" name="login-form" id="login-form" enctype="multipart/form-data" method="post">
                            <fieldset>
                                <legend>
                                	Podaci za prijavu na sustav
                                </legend>
                                <label title="Upišite vaše korisničko ime" for="username">
                                    Korisničko ime:
                                </label>
                                <input type="text" maxlength="50" size="50" value="<?= set_value('username'); ?>"" tabindex="1" id="username" name="username"/>
                                <label title="Upišite vašu lozinku" for="password">
                                    Lozinka:
                                </label>
                                <input type="password" maxlength="50" size="50" value="" tabindex="2" id="password" name="password"/>
                                <label title="Stavite kvačicu ukoliko želite da vas sustav zapamti" for="autologin">
                                    Zapamti me?
                                </label>
                                <input type="checkbox" tabindex="3" id="autologin" name="autologin" value="true">
                            </fieldset>

                            <fieldset id="formControls">
                                <input type="hidden" value="" name="check"/>
								<input type="hidden" value="submit" name="submit"/>
								<input type="image" alt="Login" src="<?= base_url(); ?>img/admin/login_btn.png" tabindex="6" id="loginBtn" class="button" name="loginBtn"/>
                            </fieldset>
                        </form>

					</div> <!-- .content -->
				</div> <!-- #login-frame-top -->
    		</div> <!-- #login-frame-middle -->
			<div id="login-frame-bottom"></div> <!-- #login-frame-bottom -->
    	</div> <!-- #main-frame -->
    </body>
</html>
