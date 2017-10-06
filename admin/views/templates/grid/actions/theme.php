<?php
// Yii Imports
use yii\helpers\Html;
?>

<?php if( !$model->default ) { ?>
<span class="action action-generic cmti cmti-check" title="Make Default" target="<?= $model->id ?>" action="Make Default" generic popup="popup-grid-generic"></span>
<?php } ?>

<span class="action"><?= Html::a( "<i class=\"cmti cmti-edit\" title=\"Update\"></i>", [ "update?id=$model->id" ] ) ?></span>

<span class="action action-pop action-delete cmti cmti-close-c" title="Delete" target="<?= $model->id ?>" popup="popup-grid-delete"></span>
