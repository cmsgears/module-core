<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Delete Form Field';

// Sidebar
$sidebar						= $this->context->sidebar;
$returnUrl						= $this->context->returnUrl;
$this->params['sidebar-parent'] = $sidebar[ 'parent' ];
$this->params['sidebar-child'] 	= $sidebar[ 'child' ];
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Delete Form Field</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-field-delete', 'options' => ['class' => 'frm-split' ] ] );?>

		<?= $form->field( $model, 'name' )->textInput( [ 'readonly' => true ] ) ?>
		<?= $form->field( $model, 'label' )->textInput( [ 'readonly' => true ] ) ?>
		<?= $form->field( $model, 'type' )->dropDownList( $typeMap, [ 'disabled' => true ] ) ?>
		<?= $form->field( $model, 'compress' )->checkbox( [ 'disabled' => true ] ) ?>
		<?= $form->field( $model, 'validators' )->textInput( [ 'readonly' => true ] ) ?>
		<?= $form->field( $model, 'htmlOptions' )->textarea( [ 'readonly' => true ] ) ?>

		<div class="box-filler"></div>

		<?=Html::a( 'Cancel', $returnUrl, ['class' => 'btn' ] );?>
		<input type="submit" value="Delete" />

		<?php ActiveForm::end(); ?>
	</div>
</section>