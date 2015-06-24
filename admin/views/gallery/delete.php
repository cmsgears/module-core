<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Delete Gallery';

// Sidebar
$this->params['sidebar-parent'] = 'sidebar-gallery';
$this->params['sidebar-child'] 	= 'gallery';
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Delete Gallery</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-gallery-delete', 'options' => ['class' => 'frm-split' ] ] );?>

    	<?= $form->field( $model, 'name' )->textInput( [ 'readonly' => true ] ) ?>
    	<?= $form->field( $model, 'title' )->textInput( [ 'readonly' => true ] ) ?>
    	<?= $form->field( $model, 'description' )->textarea( [ 'readonly' => true ] ) ?>

		<div class="box-filler"></div>
		<?=Html::a( "Cancel", [ '/cmgcore/gallery/all' ], ['class' => 'btn' ] );?>
		<input type="submit" value="Delete" />

		<?php ActiveForm::end(); ?>
	</div>
</section>