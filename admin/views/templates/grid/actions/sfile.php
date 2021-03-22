<?php
use yii\helpers\Html;
?>
<span title="Update"><?= Html::a( "", [ "update?id=$model->id" ], [ 'class' => 'cmti cmti-edit' ] )  ?></span>

<span class="action action-pop action-delete cmti cmti-bin" title="Delete" target="<?= $model->id ?>" popup="popup-grid-delete"></span>
