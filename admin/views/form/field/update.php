<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Update Form Field';

// Sidebar
$sidebar						= $this->context->sidebar;
$returnUrl						= $this->context->returnUrl;
$this->params['sidebar-parent'] = $sidebar[ 'parent' ];
$this->params['sidebar-child'] 	= $sidebar[ 'child' ];
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Update Form Field</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-field-update', 'options' => ['class' => 'frm-split' ] ] );?>

		<?= $form->field( $model, 'name' ) ?>
		<?= $form->field( $model, 'label' ) ?>
		<?= $form->field( $model, 'type' )->dropDownList( $typeMap ) ?>
		<?= $form->field( $model, 'compress' )->checkbox() ?>
		<?= $form->field( $model, 'validators' ) ?>
		<?= $form->field( $model, 'options' )->textarea() ?>

		<div class="box-filler"></div>

		<?=Html::a( 'Cancel', $returnUrl, ['class' => 'btn' ] );?>
		<input type="submit" value="Update" />

		<?php ActiveForm::end(); ?>
	</div>
</section>