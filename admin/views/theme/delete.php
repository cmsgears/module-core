<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\files\widgets\AvatarUploader;
use cmsgears\files\widgets\ImageUploader;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Delete Theme | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
$renderers		= Yii::$app->templateManager->renderers;
?>
<div class="box box-cud">
	<div class="box-wrap-header">
		<div class="header">Delete Theme</div>
	</div>
	<div class="box-wrap-content frm-split-40-60">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-site' ] );?>

    	<?= $form->field( $model, 'name' )->textInput( [ 'readonly' => true ] ) ?>
    	<?= $form->field( $model, 'description' )->textarea( [ 'readonly' => true ] ) ?>
    	<?= $form->field( $model, 'default' )->checkbox( [ 'disabled' => true ] ) ?>
		<?= $form->field( $model, 'renderer' )->dropDownList( $renderers, [ 'disabled' => true ] ) ?>
		<?= $form->field( $model, 'basePath' )->textInput( [ 'readonly' => true ] ) ?>

		<div class="filler-height"></div>

		<div class="align align-center">
			<?=Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] );?>
			<input class="element-medium" type="submit" value="Delete" />
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>