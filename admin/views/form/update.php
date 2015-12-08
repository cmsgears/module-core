<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Update Form';

// Sidebar
$sidebar						= $this->context->sidebar;
$returnUrl						= $this->context->returnUrl;
$this->params['sidebar-parent'] = $sidebar[ 'parent' ];
$this->params['sidebar-child'] 	= $sidebar[ 'child' ];
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Update Form</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-form-update', 'options' => ['class' => 'frm-split' ] ] );?>

		<?= $form->field( $model, 'name' ) ?>
		<?= $form->field( $model, 'description' )->textarea() ?>
		<?= $form->field( $model, 'templateId' )->dropDownList( $templatesMap ) ?>
		<?= $form->field( $model, 'successMessage' )->textarea() ?>
		<?= $form->field( $model, 'captcha' )->checkbox() ?>
		<?= $form->field( $model, 'visibility' )->dropDownList( $visibilityMap ) ?>
		<?= $form->field( $model, 'active' )->checkbox() ?>
		<?= $form->field( $model, 'userMail' )->checkbox() ?>
		<?= $form->field( $model, 'adminMail' )->checkbox() ?>
		<?= $form->field( $model, 'options' )->textarea() ?>

		<div class="box-filler"></div>

		<?=Html::a( "Cancel", $returnUrl, ['class' => 'btn' ] );?>
		<input type="submit" value="update" />

		<?php ActiveForm::end(); ?>
	</div>
</section>