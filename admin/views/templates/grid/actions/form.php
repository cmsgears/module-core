<?php
// Yii Imports
use yii\helpers\Html;

$controllerName	= Yii::$app->controller->id;
$submits		= $widget->data[ 'submits' ];
?>

<?php if( $submits ) { ?>
	<span title="Submits"><?= Html::a( "", [ "$controllerName/submit/all?fid=$model->id" ], [ 'class' => 'cmti cmti-checkbox-b-active' ] ) ?></span>
<?php } ?>
<span title="Fields"><?= Html::a( "", [ "$controllerName/field/all?fid=$model->id" ], [ 'class' => 'cmti cmti-list-small' ] ) ?></span>
<span title="Update"><?= Html::a( "", [ "update?id=$model->id" ], [ 'class' => 'cmti cmti-edit' ] ) ?></span>

<span class="action action-pop action-delete cmti cmti-close-c" title="Delete" target="<?= $model->id ?>" popup="popup-grid-delete"></span>
