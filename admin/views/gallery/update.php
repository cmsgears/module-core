<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Update Gallery';

// Sidebar
$this->params['sidebar-parent'] = 'sidebar-gallery';
$this->params['sidebar-child'] 	= 'gallery';
?>
<section class="wrap-content container clearfix">
	<div class="cud-box">
		<h2>Update Gallery</h2>
		<?php $form = ActiveForm::begin( ['id' => 'frm-gallery-update', 'options' => ['class' => 'frm-split' ] ] );?>

    	<?= $form->field( $model, 'name' ) ?>
    	<?= $form->field( $model, 'description' )->textarea() ?>

		<div class="box-filler"></div>
		<?=Html::a( "Back", [ '/cmgcore/gallery/all' ], ['class' => 'btn' ] );?>
		<input type="submit" value="Update" />

		<?php ActiveForm::end(); ?>
	</div>
</section>