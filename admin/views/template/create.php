<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\widgets\ActiveForm;
use cmsgears\core\common\widgets\Editor;
use cmsgears\files\widgets\ImageUploader;
use cmsgears\icons\widgets\IconChooser;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Add Template | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
$fileRender		= $this->context->fileRender;
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
						<div class="col col2">
							<?= $form->field( $model, 'name' ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'title' ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= IconChooser::widget( [ 'model' => $model, 'options' => [ 'class' => 'icon-picker-wrap' ] ] ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'renderer' )->dropDownList( $renderers, [ 'class' => 'cmt-select' ] ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'active' ) ?>
						</div>
						<div class="col col2">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'frontend' ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $model, 'description' )->textarea() ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'htmlOptions' )->textarea() ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $model, 'classPath' ) ?>
						</div>
						<?php if( $fileRender ) { ?>
							<div class="col col2">
								<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'fileRender', [ 'class' => 'cmt-checkbox choice cmt-field-group', 'group-target' => 'render-file', 'group-alt' => 'render-content' ] ) ?>
							</div>
						<?php } ?>
					</div>
					<?php if( $fileRender ) { ?>
						<div class="row render-file">
							<div class="col col2">
								<?= $form->field( $model, 'dataPath' ) ?>
							</div>
							<div class="col col2">
								<?= $form->field( $model, 'dataForm' ) ?>
							</div>
						</div>
						<div class="row render-file">
							<div class="col col2">
								<?= $form->field( $model, 'attributesPath' ) ?>
							</div>
							<div class="col col2">
								<?= $form->field( $model, 'attributesForm' ) ?>
							</div>
						</div>
						<div class="row render-file">
							<div class="col col2">
								<?= $form->field( $model, 'configPath' ) ?>
							</div>
							<div class="col col2">
								<?= $form->field( $model, 'configForm' ) ?>
							</div>
						</div>
						<div class="row render-file">
							<div class="col col2">
								<?= $form->field( $model, 'settingsPath' ) ?>
							</div>
							<div class="col col2">
								<?= $form->field( $model, 'settingsForm' ) ?>
							</div>
						</div>
						<div class="row render-file">
							<div class="col col2">
								<?= $form->field( $model, 'layout' ) ?>
							</div>
							<div class="col col2">
								<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'layoutGroup' ) ?>
							</div>
						</div>
						<div class="row render-file">
							<div class="col col2">
								<?= $form->field( $model, 'viewPath' ) ?>
							</div>
							<div class="col col2">
								<?= $form->field( $model, 'view' ) ?>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php if( $fileRender ) { ?>
			<div class="filler-height filler-height-medium render-file"></div>
			<div class="box box-crud render-file">
				<div class="box-header">
					<div class="box-header-title">Files</div>
				</div>
				<div class="box-content">
					<div class="box-content">
						<div class="row max-cols-50 padding padding-small-v">
							<div class="col col12x4">
								<label>Preview</label>
								<?= ImageUploader::widget( [ 'model' => $preview ] ) ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
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
			<input class="frm-element-medium" type="submit" value="Create" />
		</div>
		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
	</div>
</div>
