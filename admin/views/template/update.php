<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\widgets\Editor;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Update Template';
$returnUrl		= $this->context->returnUrl;

Editor::widget( [ 'selector' => '.content-editor' ] );
?>
<div class="box box-cud">
	<div class="box-wrap-header">
		<div class="header">Update Template</div>
	</div>
	<div class="box-wrap-content frm-split-40-60">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-template' ] );?>

    	<?= $form->field( $model, 'name' ) ?>  
    	<?= $form->field( $model, 'description' )->textarea() ?> 
    	<?= $form->field( $model, 'layout' ) ?>  
		<?= $form->field( $model, 'viewPath' ) ?>
		<?= $form->field( $model, 'adminView' ) ?>
		<?= $form->field( $model, 'frontendView' ) ?>

		<div class="box-content clearfix">
			<div class="header">Template Content</div>
			<?= $form->field( $model, 'content' )->textarea( [ 'class' => 'content-editor' ] )->label( false ) ?>
		</div>

		<div class="filler-height"></div>

		<div class="align align-middle">
			<?=Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] );?>
			<input class="btn btn-medium" type="submit" value="Update" />
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>