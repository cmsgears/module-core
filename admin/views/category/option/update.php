<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html; 

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Update Option';

// Sidebar
$this->params['sidebar-parent'] = 'sidebar-core';
$this->params['sidebar-child'] 	= 'dropdown';
 
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Update Option</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-option-update', 'options' => ['class' => 'frm-split form-with-editor' ] ] );?>

    	<?= $form->field( $model, 'name' ) ?>
    	<?= $form->field( $model, 'value' ) ?>
    	<?= $form->field( $model, 'icon' ) ?>    
		<div class="box-filler"></div>
		
		<?=Html::a( "Cancel", $returnUrl, ['class' => 'btn' ] );?>
		<input type="submit" value="Update" />

		<?php ActiveForm::end(); ?>
	</div>
</section>