<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html; 

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Add Province';

// Sidebar
$this->params['sidebar-parent'] = 'sidebar-dropdown';
$this->params['sidebar-child'] 	= 'dropdown';
 
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Add Province</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-province-create', 'options' => ['class' => 'frm-split form-with-editor' ] ] );?>

    	<?= $form->field( $model, 'code' ) ?>  
    	<?= $form->field( $model, 'name' ) ?>   
		<div class="box-filler"></div>
		
		<?=Html::a( "Cancel", $returnUrl, ['class' => 'btn' ] );?>
		<input type="submit" value="Create" />

		<?php ActiveForm::end(); ?>
	</div>
</section>