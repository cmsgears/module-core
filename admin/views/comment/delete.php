<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$title			= ucfirst( $this->context->commentUrl );
$coreProperties = $this->context->getCoreProperties();
$this->title 	= "Add $title | " . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;

$modelClass		= $this->context->modelService->getModelClass();
$statusMap		= $modelClass::$statusMap;
?>
<div class="box box-cud">
    <div class="box-wrap-header">
        <div class="header">Delete <?= $title ?></div>
    </div>
    <div class="box-wrap-content frm-split-40-60">
        <?php $form = ActiveForm::begin( [ 'id' => 'frm-testimonial' ] );?>

        <?= $form->field( $model, 'name' )->textInput( [ 'readonly' => true ] ) ?>
        <?= $form->field( $model, 'email' )->textInput( [ 'readonly' => true ] ) ?>
        <?= $form->field( $model, 'content' )->textarea( [ 'readonly' => true ] ) ?>
        <?= $form->field( $model, 'status')->dropDownList( $statusMap, [ 'disabled' => true ] ) ?>

        <div class="clear filler-height"></div>

        <div class="align align-center">
            <?=Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] );?>
            <input class="element-medium" type="submit" value="Delete" />
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>