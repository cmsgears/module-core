<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\widgets\Editor;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Delete Template | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;

Editor::widget( [ 'selector' => '.content-editor', 'loadAssets' => true ] );
?>
<div class="box box-cud">
	<div class="box-wrap-header">
		<div class="header">Delete Template</div>
	</div>
	<div class="box-wrap-content frm-split-40-60">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-template' ] );?>

    	<?= $form->field( $model, 'name' )->textInput( [ 'readonly' => 'true' ] ) ?>  
    	<?= $form->field( $model, 'description' )->textarea( [ 'readonly' => 'true' ] ) ?> 
    	<?= $form->field( $model, 'layout' )->textInput( [ 'readonly' => 'true' ] ) ?>  
		<?= $form->field( $model, 'viewPath' )->textInput( [ 'readonly' => 'true' ] ) ?>
		<?= $form->field( $model, 'adminView' )->textInput( [ 'readonly' => 'true' ] ) ?>
		<?= $form->field( $model, 'frontendView' )->textInput( [ 'readonly' => 'true' ] ) ?>

		<div class="box-content clearfix">
			<div class="header">Template Content</div>
			<?= $form->field( $model, 'content' )->textarea( [ 'class' => 'content-editor' ] )->label( false ) ?>
		</div>

		<div class="filler-height"></div>

		<div class="align align-middle">
			<?=Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] );?>
			<input class="btn btn-medium" type="submit" value="Delete" />
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>