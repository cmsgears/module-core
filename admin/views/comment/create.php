<?php
// Yii Imports
use yii\helpers\Html;

// CMG Imports
use cmsgears\core\common\widgets\ActiveForm;
use cmsgears\core\common\widgets\Editor;
use cmsgears\files\widgets\AvatarUploader;
use cmsgears\files\widgets\ImageUploader;
use cmsgears\files\widgets\VideoUploader;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= "Create $title | " . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;

$stars	= $this->context->stars;
$user	= isset( $model->userId ) ? $model->user->getName() : null;

Editor::widget();
?>
<div class="box-crud-wrap">
	<div class="box-crud-wrap-main">
		<?php $form = ActiveForm::begin( [ 'id' => 'frm-comment', 'options' => [ 'class' => 'form' ] ] ); ?>
		<div class="box box-crud layer layer-10">
			<div class="box-header">
				<div class="box-header-title">Basic Details</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row max-cols-100">
						<div class="row row-medium">
							<?= Yii::$app->formDesigner->getAutoSuggest( $form, $model, 'userId', [
								'placeholder' => 'Search User', 'icon' => 'cmti cmti-search',
								'app' => 'core', 'controller' => 'user',
								'value' => $user, 'url' => 'core/user/auto-search'
							]) ?>
						</div>
						<div class="note">Notes: Use Search User only in case submit need to be done on behalf of selected user.</div>
					</div>
					<div class="filler-height filler-height-medium"></div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $model, 'name' ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'email' ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $model, 'mobile' ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'title' ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $model, 'avatarUrl' ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'websiteUrl' ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $model, 'status' )->dropDownList( $statusMap, [ 'class' => 'cmt-select' ] ) ?>
						</div>
						<div class="col col2">
							<?= Yii::$app->formDesigner->getRatingField( [ 'model' => $model, 'stars' => $stars ] ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col3">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'pinned' ) ?>
						</div>
						<div class="col col3">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'featured' ) ?>
						</div>
						<div class="col col3">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'popular' ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col3">
							<?= Yii::$app->formDesigner->getIconCheckbox( $form, $model, 'anonymous' ) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium layer layer-5"></div>
		<div class="box box-crud layer layer-5">
			<div class="box-header">
				<div class="box-header-title">Stars</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row max-cols-100">
						<div class="col col2">
							<?= Yii::$app->formDesigner->getRatingField( [ 'model' => $model, 'stars' => $stars, 'fieldName' => 'rate1' ] ) ?>
						</div>
						<div class="col col2">
							<?= Yii::$app->formDesigner->getRatingField( [ 'model' => $model, 'stars' => $stars, 'fieldName' => 'rate2' ] ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= Yii::$app->formDesigner->getRatingField( [ 'model' => $model, 'stars' => $stars, 'fieldName' => 'rate3' ] ) ?>
						</div>
						<div class="col col2">
							<?= Yii::$app->formDesigner->getRatingField( [ 'model' => $model, 'stars' => $stars, 'fieldName' => 'rate4' ] ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= Yii::$app->formDesigner->getRatingField( [ 'model' => $model, 'stars' => $stars, 'fieldName' => 'rate5' ] ) ?>
						</div>
						<div class="col col2 margin margin-bottom-small">
							<p class="note">If stars are set, average value of the stars will be used to set the rating.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium"></div>
		<div class="box box-crud">
			<div class="box-header">
				<div class="box-header-title">Files</div>
			</div>
			<div class="box-content">
				<div class="box-content">
					<div class="row max-cols-50 padding padding-small-v">
						<div class="col col12x4">
							<label>Avatar</label>
							<?= AvatarUploader::widget( [ 'model' => $avatar, 'clearAction' => true ] ) ?>
						</div>
						<div class="col col12x4">
							<label>Banner</label>
							<?= ImageUploader::widget( [ 'model' => $banner, 'clearAction' => true ] ) ?>
						</div>
						<div class="col col12x4">
							<label>Video</label>
							<?= VideoUploader::widget( [ 'model' => $video, 'clearAction' => true ] ) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium layer layer-5"></div>
		<div class="box box-crud layer layer-5">
			<div class="box-header">
				<div class="box-header-title">Additional Fields</div>
			</div>
			<div class="box-content-wrap frm-split-40-60">
				<div class="box-content">
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $model, 'field1' ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'field2' ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $model, 'field3' ) ?>
						</div>
						<div class="col col2">
							<?= $form->field( $model, 'field4' ) ?>
						</div>
					</div>
					<div class="row max-cols-100">
						<div class="col col2">
							<?= $form->field( $model, 'field5' ) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium layer layer-5"></div>
		<div class="box box-crud layer layer-5">
			<div class="box-header">
				<div class="box-header-title">Content</div>
			</div>
			<div class="box-content-wysiwyg">
				<div class="box-content">
					<?= $form->field( $model, 'content' )->textarea( [ 'class' => 'content-editor' ] )->label( false ) ?>
				</div>
			</div>
		</div>
		<div class="filler-height filler-height-medium layer layer-5"></div>
		<div class="align align-right layer layer-5">
			<?= Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] ); ?>
			<input class="frm-element-medium" type="submit" value="Create" />
		</div>
		<div class="filler-height filler-height-medium"></div>
		<?php ActiveForm::end(); ?>
	</div>
</div>
