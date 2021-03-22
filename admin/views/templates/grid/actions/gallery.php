<?php
// Yii Imports
use yii\helpers\Html;

$template = $model->template;
?>

<span class="action"><?= Html::a( "<i class=\"cmti cmti-list-small\" title=\"Items\"></i>", [ "items?id=$model->id" ] ) ?></span>
<span class="action"><?= Html::a( "<i class=\"cmti cmti-edit\" title=\"Update\"></i>", [ "update?id=$model->id" ] ) ?></span>

<?php if( isset( $template ) ) { ?>
	<?php if( !empty( $template->dataForm ) ) { ?>
		<span title="Data"><?= Html::a( "", [ "data?id=$model->id" ], [ 'class' => 'cmti cmti-briefcase' ] ) ?></span>
	<?php } ?>
	<?php if( !empty( $template->attributesForm ) ) { ?>
		<span title="Attributes"><?= Html::a( "", [ "attributes?id=$model->id" ], [ 'class' => 'cmti cmti-tag-o' ] ) ?></span>
	<?php } ?>
	<?php if( !empty( $template->configForm ) ) { ?>
		<span title="Config"><?= Html::a( "", [ "config?id=$model->id" ], [ 'class' => 'cmti cmti-setting-o' ] ) ?></span>
	<?php } ?>
	<?php if( !empty( $template->settingsForm ) ) { ?>
		<span title="Settings"><?= Html::a( "", [ "settings?id=$model->id" ], [ 'class' => 'cmti cmti-setting' ] ) ?></span>
	<?php } ?>
<?php } ?>

<span class="action action-pop action-delete cmti cmti-bin" title="Delete" target="<?= $model->id ?>" popup="popup-grid-delete"></span>
