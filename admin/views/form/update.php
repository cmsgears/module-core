<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Update Form | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
?>
<div class="box box-cud">
    <div class="box-wrap-header">
        <div class="header">Update Form</div>
    </div>
    <div class="box-wrap-content frm-split-40-60">
        <?php $form = ActiveForm::begin( [ 'id' => 'frm-form' ] );?>

        <?= $form->field( $model, 'name' ) ?>
        <?= $form->field( $model, 'description' )->textarea() ?>
        <?= $form->field( $model, 'templateId' )->dropDownList( $templatesMap ) ?>
        <?= $form->field( $model, 'successMessage' )->textarea() ?>
        <?= $form->field( $model, 'captcha' )->checkbox() ?>
        <?= $form->field( $model, 'visibility' )->dropDownList( $visibilityMap ) ?>
        <?= $form->field( $model, 'active' )->checkbox() ?>
        <?= $form->field( $model, 'userMail' )->checkbox() ?>
        <?= $form->field( $model, 'adminMail' )->checkbox() ?>
        <?= $form->field( $model, 'htmlOptions' )->textarea() ?>

        <div class="clear filler-height"></div>

        <div class="align align-center">
            <?=Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] );?>
            <input class="element-medium" type="submit" value="Update" />
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>