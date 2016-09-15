<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\icons\widgets\IconChooser;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Add Permission';
$returnUrl		= $this->context->returnUrl;
?>
<div class="box box-cud">
    <div class="box-wrap-header">
        <div class="header">Add Permission</div>
    </div>
    <div class="box-wrap-content frm-split-40-60">
        <?php $form = ActiveForm::begin( [ 'id' => 'frm-permission' ] );?>

        <?= $form->field( $model, 'name' ) ?>
        <?= IconChooser::widget( [ 'model' => $model, 'options' => [ 'class' => 'wrap-icon-picker clearfix' ] ] ) ?>
        <?= $form->field( $model, 'description' )->textarea() ?>

        <?php if( count( $roles ) > 0 ) { ?>
        <div class="box-content clearfix">
            <div class="header">Assign Roles</div>
            <?php foreach ( $roles as $role ) { ?>
                <span class="box-half"><input type="checkbox" name="Binder[bindedData][]" value="<?= $role['id'] ?>" /><?= $role['name'] ?></span>
            <?php } ?>
        </div>
        <?php } ?>

        <div class="clear filler-height"></div>

        <div class="align align-center">
            <?=Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] );?>
            <input class="element-medium" type="submit" value="Create" />
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>