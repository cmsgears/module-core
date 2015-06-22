<?php
use yii\helpers\Html; 
use yii\widgets\LinkPager;
use yii\helpers\Url;
?>
<div class="settings-wrapper clearfix">
	<div class="data-grid">
		<?php include 'header.php'; ?>
		<div class="wrap-grid-table">
			<div class="settings-title-wrapper">
				<h1><?= ucfirst( $type ) ?> Settings</h1>
				<div class="setting-updatebtn-wrapper">
					<?= Html::a( "<input type='button' value='Update' class='btn'>", ["/cmgcore/settings/update?type=$type"], ['class'=>''] )  ?> 
				</div>
			</div>
			<div class="settings-core-view">
				<?php if( isset( $settings ) ) { ?>
				<ul class="view-setting-ul">
					<?php foreach ( $settings as $setting ) { ?>
					<li>
						<label><?= $setting->name ?></label>
						<span><?= strcmp( $setting->fieldType, "password" ) == 0 ? '' : $setting->value ?></span>
					</li>
					<?php } ?>
				</ul>
				<?php } else { ?>
				<p>No settings found.</p>
				<?php } ?>
			</div>
		</div>
	</div>
</div>