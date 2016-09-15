<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\icons\widgets\IconChooser;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Delete Tag | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
?>
<div class="box box-cud">
    <div class="box-wrap-header">
        <div class="header">Delete Tag</div>
    </div>
    <div class="box-wrap-content frm-split-40-60">
        <?php $form = ActiveForm::begin( [ 'id' => 'frm-tag' ] );?>

        <?= $form->field( $model, 'name' )->textInput( [ 'readonly' => true ] ) ?>
        <?= $form->field( $model, 'description' )->textarea( [ 'readonly' => true ] ) ?>
        <?= IconChooser::widget( [ 'model' => $model, 'options' => [ 'class' => 'wrap-icon-picker clearfix' ], 'disabled' => true ] ) ?>

        <div class="filler-height"></div>

        <div class="align align-center">
            <?= Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] ) ?>
            <input class="element-medium" type="submit" value="Delete" />
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>