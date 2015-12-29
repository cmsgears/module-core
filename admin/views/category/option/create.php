<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html; 

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Add Option';

// Sidebar and Return URL
$sidebar						= $this->context->sidebar;
$returnUrl						= $this->context->returnUrl;
$this->params['sidebar-parent'] = $sidebar[ 'parent' ];
$this->params['sidebar-child'] 	= $sidebar[ 'child' ];
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Add Option</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-option-create', 'options' => ['class' => 'frm-split form-with-editor' ] ] );?>

    	<?= $form->field( $model, 'name' ) ?>
    	<?= $form->field( $model, 'value' ) ?>
    	<?= $form->field( $model, 'icon' ) ?>
    	<?= $form->field( $model, 'htmlOptions' )->textarea() ?>
		<?= $form->field( $model, 'categoryId' )->hiddenInput( [ 'value' => $id ] )->label( false )?>
		<div class="box-filler"></div>

		<?=Html::a( "Cancel", $returnUrl, ['class' => 'btn' ] );?>
		<input type="submit" value="Create" />

		<?php ActiveForm::end(); ?>
	</div>
</section>