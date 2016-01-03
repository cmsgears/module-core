<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Add Gallery';
$returnUrl		= $this->context->returnUrl;
?>
<div class="box box-cud">
	<div class="box-wrap-header">
		<div class="header">Add Gallery</div>
	</div>
	<div class="box-wrap-content frm-split-40-60">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-gallery' ] );?>

    	<?= $form->field( $model, 'name' ) ?>
    	<?= $form->field( $model, 'title' ) ?>
    	<?= $form->field( $model, 'description' )->textarea() ?>
		<?= $form->field( $model, 'active' )->checkbox() ?>

		<div class="clear filler-height"></div>

		<div class="align align-middle">
			<?=Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] );?>
			<input class="btn btn-medium" type="submit" value="Create" />
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>