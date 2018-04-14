<?php
use yii\helpers\Html;

$parent = $widget->data[ 'parent' ];
?>
<span title="Update"><?= Html::a( "", [ "update?id=$model->id&pid=$parent->id" ], [ 'class' => 'cmti cmti-edit' ] )  ?></span>

<span class="action action-pop action-delete cmti cmti-close-c" title="Delete" target="<?= $model->id ?>" popup="popup-grid-delete"></span>
