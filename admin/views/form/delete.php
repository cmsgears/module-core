<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Delete Form';

// Sidebar
$sidebar						= $this->context->sidebar;
$returnUrl						= $this->context->returnUrl;
$this->params['sidebar-parent'] = $sidebar[ 'parent' ];
$this->params['sidebar-child'] 	= $sidebar[ 'child' ];
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Delete Form</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-form-delete', 'options' => ['class' => 'frm-split' ] ] );?>

		<?= $form->field( $model, 'name' )->textInput( [ 'readonly' => true ] ) ?>
		<?= $form->field( $model, 'description' )->textarea( [ 'readonly' => true ] ) ?>
		<?= $form->field( $model, 'templateId' )->dropDownList( $templatesMap, [ 'disabled' => true ] ) ?>
		<?= $form->field( $model, 'successMessage' )->textarea( [ 'readonly' => true ] ) ?>
		<?= $form->field( $model, 'captcha' )->checkbox( [ 'readonly' => true ] ) ?>
		<?= $form->field( $model, 'visibility' )->dropDownList( $visibilityMap, [ 'disabled' => true ] ) ?>
		<?= $form->field( $model, 'active' )->checkbox( [ 'disabled'=>'true' ] ) ?>
		<?= $form->field( $model, 'userMail' )->checkbox( [ 'readonly' => true ] ) ?>
		<?= $form->field( $model, 'adminMail' )->checkbox( [ 'readonly' => true ] ) ?>
		<?= $form->field( $model, 'htmlOptions' )->textarea( [ 'readonly' => true ] ) ?>

		<div class="box-filler"></div>

		<?=Html::a( "Cancel", $returnUrl, ['class' => 'btn' ] );?>
		<input type="submit" value="Delete" />

		<?php ActiveForm::end(); ?>
	</div>
</section>