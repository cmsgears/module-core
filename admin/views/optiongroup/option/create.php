<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\icons\widgets\IconChooser;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Add Option | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
?>
<div class="box box-cud">
	<div class="box-wrap-header">
		<div class="header">Add Option</div>
	</div>
	<div class="box-wrap-content frm-split-40-60">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-option' ] );?>

    	<?= $form->field( $model, 'name' ) ?>
    	<?= $form->field( $model, 'value' ) ?>
    	<?= IconChooser::widget( [ 'model' => $model, 'options' => [ 'class' => 'wrap-icon-picker clearfix' ] ] ) ?>
    	<?= $form->field( $model, 'htmlOptions' )->textarea() ?>

		<div class="clear filler-height"></div>

		<div class="align align-center">
			<?=Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] );?>
			<input class="element-medium" type="submit" value="Create" />
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>