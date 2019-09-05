<?php
use yii\helpers\Html;

$baseUrl	= $widget->baseUrl;
$parent		= $widget->data[ 'parent' ];
?>
<span title="Gallery"><?= Html::a( "", [ "$baseUrl/gallery?id=$model->id" ], [ 'class' => 'cmti cmti-image' ] ) ?></span>
<span title="Update"><?= Html::a( "", [ "update?id={$model->id}&pid=$parent->id" ], [ 'class' => 'cmti cmti-edit' ] ) ?></span>

<span class="action action-pop action-delete cmti cmti-bin" title="Delete" target="<?= $model->id ?>" popup="popup-grid-delete"></span>
