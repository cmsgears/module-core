<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html; 

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Delete Country';

// Sidebar
$this->params['sidebar-parent'] = 'sidebar-country';
$this->params['sidebar-child'] 	= 'country';
 
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Delete Country</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-country-delete', 'options' => ['class' => 'frm-split form-with-editor' ] ] );?>

    	<?= $form->field( $model, 'code' )->textInput( [ 'readonly' => true ] ) ?>  
    	<?= $form->field( $model, 'name' )->textInput( [ 'readonly' => true ] ) ?>  
		<div class="box-filler"></div>
		
		<?=Html::a( "Cancel", [ 'country/all' ], ['class' => 'btn' ] );?>
		<input type="submit" value="Delete" />

		<?php ActiveForm::end(); ?>
	</div>
</section>