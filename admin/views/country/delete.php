<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Delete Country';
$returnUrl		= $this->context->returnUrl;
?>
<div class="box box-cud">
	<div class="box-wrap-header">
		<div class="header">Delete Country</div>
	</div>
	<div class="box-wrap-content frm-split-40-60">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-country' ] );?>

    	<?= $form->field( $model, 'code' )->textInput( [ 'readonly' => true ] ) ?>
    	<?= $form->field( $model, 'name' )->textInput( [ 'readonly' => true ] ) ?>

		<div class="clear filler-height"></div>

		<div class="align align-center">
			<?=Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] );?>
			<input class="element-medium" type="submit" value="Delete" />
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>