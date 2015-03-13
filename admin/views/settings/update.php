<?php
use yii\helpers\Html; 
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<div class="settings-wrapper clearfix">
	<div class="data-grid">
		<?php
			include 'header.php';
		?>
		<div class="wrap-grid-table">
			<div class="settings-title-wrapper">
				<h1>Update <?= ucfirst( $type ) ?> Settings</h1>
			</div>
			<div class="settings-core-view">
				<?php if( isset( $settings ) ) { ?>
				<ul>
					<?php

						foreach ( $settings as $key => $setting ) {

							$id = $setting->getId();
					?>
					<li>
						<form id="frm-settings-core-<?=$id?>" group="0" key="35" class="frm-settings" action="<?= Url::toRoute('/cmgcore/apix/settings/update?id='. $id ) ?>" method="POST" keepData="true">
							<label> <?=  $setting->getKey() ?> </label>
							<span> <input type="text" name="Config[config_value]" value="<?= strcmp( $setting->getFieldType(), "password" ) == 0 ? '' : $setting->getValue() ?>" > </span>
							<input type="submit" name="submit" value="Save" />
							<div class="spinner"></div>
							<div class="frm-message"></div>
						</form>
					</li>
					<?php
						}
					?>
				</ul>
				<?php } else { ?>
				<p>No settings found.</p>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	initSidebar( "sidebar-settings", 1 );

	jQuery("ul.settings-tab-wrapper li").removeClass('active');
	jQuery("ul.settings-tab-wrapper li.<?=$type?>").addClass('active');
	
	jQuery( 'form' ).processAjax();
</script>