<?php
// Yii Imports
use yii\helpers\Html;
?>

<span class="action"><?= Html::a( "<i class=\"cmti cmti-list-small\" title=\"Options\"></i>", [ "optiongroup/option/all?cid=$model->id" ] ) ?></span>

<span class="action"><?= Html::a( "<i class=\"cmti cmti-edit\" title=\"Update\"></i>", [ "update?id=$model->id" ] ) ?></span>

<span class="action action-pop action-delete cmti cmti-bin" title="Delete" target="<?= $model->id ?>" popup="popup-grid-delete"></span>
