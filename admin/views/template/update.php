<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\widgets\Editor;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Update Template | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;

Editor::widget( [ 'selector' => '.content-editor', 'loadAssets' => true ] );
?>
<div class="box box-cud">
	<div class="box-wrap-header">
		<div class="header">Update Template</div>
	</div>
	<div class="box-wrap-content frm-split-40-60">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-template' ] );?>

    	<?= $form->field( $model, 'name' ) ?>
    	<?= $form->field( $model, 'description' )->textarea() ?>
    	<?= $form->field( $model, 'icon' ) ?>
    	<?= $form->field( $model, 'renderer' )->dropDownList( $renderers ) ?>

		<?= $form->field( $model, 'renderFile' )->checkbox( [ 'class' => 'template-file' ] ) ?>

    	<div class="render-file">
	    	<?= $form->field( $model, 'layout' ) ?>
			<?= $form->field( $model, 'viewPath' ) ?>
			<?= $form->field( $model, 'adminView' ) ?>
			<?= $form->field( $model, 'userView' ) ?>
			<?= $form->field( $model, 'publicView' ) ?>
		</div>

		<div class="render-content box-content clearfix">
			<div class="header">Template Content</div>
			<?= $form->field( $model, 'content' )->textarea( [ 'class' => 'content-editor' ] )->label( false ) ?>
		</div>

		<div class="filler-height"></div>

		<div class="align align-center">
			<?=Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] );?>
			<input class="element-medium" type="submit" value="Update" />
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>