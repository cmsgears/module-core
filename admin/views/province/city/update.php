<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title	= 'Update City | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
?>
<div class="box box-cud">
	<div class="box-wrap-header">
		<div class="header">Update City</div>
	</div>
	<div class="box-wrap-content frm-split-40-60">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-city' ] ); ?>

		<?= $form->field( $model, 'name' ) ?>
		<?= $form->field( $model, 'zone' ) ?>
		<?= $form->field( $model, 'latitude' ) ?>
		<?= $form->field( $model, 'longitude' ) ?>

		<div class="clear filler-height"></div>

		<div class="align align-center">
			<?= Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="element-medium" type="submit" value="Update" />
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>