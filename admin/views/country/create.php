<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html; 

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Add Country';

// Sidebar
$this->params['sidebar-parent'] = 'sidebar-country';
$this->params['sidebar-child'] 	= 'country';
 
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Add Country</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-country-create', 'options' => ['class' => 'frm-split form-with-editor' ] ] );?>

    	<?= $form->field( $model, 'code' ) ?>  
    	<?= $form->field( $model, 'name' ) ?>  
		<div class="box-filler"></div>
		
		<?=Html::a( "Cancel", [ 'country/all' ], ['class' => 'btn' ] );?>
		<input type="submit" value="Create" />

		<?php ActiveForm::end(); ?>
	</div>
</section>