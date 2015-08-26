<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html; 

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Delete Province';

// Sidebar
$this->params['sidebar-parent'] = 'sidebar-dropdown';
$this->params['sidebar-child'] 	= 'dropdown';
 
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Delete Province</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-province-delete', 'options' => ['class' => 'frm-split form-with-editor' ] ] );?>
    	<?= $form->field( $model, 'code' )->textInput( [ 'readonly' => true ] ) ?>  
    	<?= $form->field( $model, 'name' )->textInput( [ 'readonly' => true ] ) ?>   
		<div class="box-filler"></div>
		
		<?=Html::a( "Cancel", $returnUrl, ['class' => 'btn' ] );?>
		<input type="submit" value="Delete" />

		<?php ActiveForm::end(); ?>
	</div>
</section>