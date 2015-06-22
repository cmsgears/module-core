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
						$counter	= 0;

						foreach ( $settings as $setting ) {

					?>
					<li>
						<form id="frm-settings-core-<?=$counter?>" group="0" key="35" class="frm-ajax frm-settings" action="<?= Url::toRoute('/cmgcore/apix/settings/update' ) ?>" method="POST" keepData="true">
							<label> <?=  $setting->name ?> </label>
							<?php if( strcmp( $setting->fieldType, "password" ) == 0 ) { ?>
								<span> <input type="password" name="ModelMeta[value]" value="" > </span>
							<?php } else { ?>
								<span> <input type="text" name="ModelMeta[value]" value="<?= $setting->value ?>" > </span>
							<?php } ?>
							<input type="hidden" name="ModelMeta[name]" value="<?= $setting->name ?>" >
							<input type="hidden" name="ModelMeta[type]" value="<?= $setting->type ?>" >
							<input type="submit" name="submit" value="Save" />
							<div class="spinner"></div>
							<div class="frm-message"></div>
						</form>
					</li>
					<?php
							$counter++;
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