<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\widgets\Editor;
use cmsgears\icons\widgets\IconChooser;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Add Template | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
$renderers		= Yii::$app->templateManager->renderers;

Editor::widget( [ 'selector' => '.content-editor', 'loadAssets' => true ] );
?>
<div class="box box-cud">
	<div class="box-wrap-header">
		<div class="header">Add Template</div>
	</div>
	<div class="box-wrap-content frm-split-40-60">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-template' ] );?>

    	<?= $form->field( $model, 'name' ) ?>
    	<?= $form->field( $model, 'description' )->textarea() ?>
    	<?= IconChooser::widget( [ 'model' => $model, 'options' => [ 'class' => 'wrap-icon-picker clearfix' ] ] ) ?>
    	<?= $form->field( $model, 'renderer' )->dropDownList( $renderers ) ?>

		<?= $form->field( $model, 'fileRender' )->checkbox( [ 'class' => 'template-file' ] ) ?>

    	<div class="render-file">
	    	<?= $form->field( $model, 'layout' ) ?>
	    	<?= $form->field( $model, 'layoutGroup' )->checkbox() ?>
			<?= $form->field( $model, 'viewPath' ) ?>
		</div>

		<div class="render-content box-content clearfix">
			<div class="header">Template Content</div>
			<?= $form->field( $model, 'content' )->textarea( [ 'class' => 'content-editor' ] )->label( false ) ?>
		</div>

		<div class="filler-height"></div>

		<div class="align align-center">
			<?=Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] );?>
			<input class="element-medium" type="submit" value="Create" />
		</div>

		<?php ActiveForm::end(); ?>
	</div>
</div>