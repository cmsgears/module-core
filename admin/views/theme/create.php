<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\files\widgets\AvatarUploader;
use cmsgears\files\widgets\ImageUploader;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Add Theme | ' . $coreProperties->getSiteTitle();
?>
<div class="box box-cud">
	<div class="box-wrap-header">
		<div class="header">Add Theme</div>
	</div>
	<div class="box-wrap-content frm-split-40-60">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-site' ] );?>

    	<?= $form->field( $model, 'name' ) ?>
    	<?= $form->field( $model, 'description' )->textarea() ?> 
		<?= $form->field( $model, 'renderer' )->dropDownList( $renderers ) ?>
		<?= $form->field( $model, 'basePath' ) ?>

		<div class="filler-height"></div>

		<div class="align align-center">
			<?= Html::a( 'Cancel', 'all', [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="element-medium" type="submit" value="Create" />
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>