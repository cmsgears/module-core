<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\config\CoreGlobal;
?>

<?php if( $model->type == CoreGlobal::TYPE_SITE && !$model->default ) { ?>
<span class="action action-generic cmti cmti-check" title="Make Default" target="<?= $model->id ?>" action="Make Default" generic popup="popup-grid-generic"></span>
<?php } ?>

<span class="action"><?= Html::a( "<i class=\"cmti cmti-edit\" title=\"Update\"></i>", [ "update?id=$model->id" ] ) ?></span>

<span class="action action-pop action-delete cmti cmti-bin" title="Delete" target="<?= $model->id ?>" popup="popup-grid-delete"></span>
