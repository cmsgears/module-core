<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\widgets\ActiveForm;
use cmsgears\core\common\widgets\Editor;
use cmsgears\icons\widgets\IconChooser;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Delete Template | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
$renderers		= Yii::$app->templateManager->renderers;

Editor::widget();
?>
<div class="box-crud-wrap">
	<div class="box-crud-wrap-main">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-template', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Basic Details</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row max-cols-100">
						<div class="col col3">
							<?= $form->field( $model, 'name' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
						<div class="col col3">
							<?= $form->field( $model, 'slug' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
						<div class="col col3">
							<?= $form->field( $model, 'title' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= IconChooser::widget( [ 'model' => $model, 'options' => [ 'class' => 'icon-picker-wrap' ], 'disabled' => true ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'renderer' )->dropDownList( $renderers, [ 'class' => 'cmt-select', 'disabled' => true ] ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'active', [ 'disabled' => true ] ) ?>
						</div>
						<div class="col col2">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'frontend', [ 'disabled' => true ] ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $model, 'description' )->textarea( [ 'readonly' => 'true' ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'htmlOptions' )->textarea( [ 'readonly' => 'true' ] ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $model, 'classPath' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
						<div class="col col2">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'fileRender', [ 'class' => 'cmt-checkbox cmt-choice cmt-field-group', 'disabled' => true, 'group-target' => 'render-file', 'group-alt' => 'render-content' ] ) ?>
						</div>
					</div>
					<div class="row render-file">
						<div class="col col2">
							<?= $form->field( $model, 'dataPath' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'dataForm' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
					</div>
					<div class="row render-file">
						<div class="col col2">
							<?= $form->field( $model, 'attributesPath' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'attributesForm' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
					</div>
					<div class="row render-file">
						<div class="col col2">
							<?= $form->field( $model, 'configPath' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'configForm' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
					</div>
					<div class="row render-file">
						<div class="col col2">
							<?= $form->field( $model, 'settingsPath' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'settingsForm' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
					</div>
					<div class="row render-file">
						<div class="col col2">
							<?= $form->field( $model, 'layout' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
						<div class="col col2">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'layoutGroup', [ 'disabled' => true ] ) ?>
						</div>
					</div>
					<div class="row render-file">
						<div class="col col2">
							<?= $form->field( $model, 'viewPath' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'view' )->textInput( [ 'readonly' => 'true' ] ) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="box box-crud render-content">
			<div class="box-header">
				<div class="box-header-title">Content</div>
			</div>
			<div class="box-content-wysiwyg">
				<div class="box-content">
					<?= $form->field( $model, 'content' )->textarea( [ 'class' => 'content-editor' ] )->label( false ) ?>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="align align-right">
			<?= Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="frm-element-medium" type="submit" value="Delete" />
		</div>
		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
	</div>
</div>
