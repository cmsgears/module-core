<?php
// Yii Imports
use yii\helpers\Html;

$controllerName	= Yii::$app->controller->id;

$submits = $widget->data[ 'submits' ];

$template = $model->template;
?>

<?php if( $submits ) { ?>
	<span title="Submits"><?= Html::a( "", [ "$controllerName/submit/all?pid=$model->id" ], [ 'class' => 'cmti cmti-checkbox-b-active' ] ) ?></span>
<?php } ?>
<span title="Fields"><?= Html::a( "", [ "$controllerName/field/all?pid=$model->id" ], [ 'class' => 'cmti cmti-list-small' ] ) ?></span>
<span title="Update"><?= Html::a( "", [ "update?id=$model->id" ], [ 'class' => 'cmti cmti-edit' ] ) ?></span>

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
