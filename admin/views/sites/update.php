<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

// CMG Imports
use cmsgears\files\widgets\AvatarUploader;
use cmsgears\files\widgets\ImageUploader;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Update Site | ' . $coreProperties->getSiteTitle();
$returnUrl		= $this->context->returnUrl;
?>
<div class="box box-cud">
    <div class="box-wrap-header">
        <div class="header">Update Site</div>
    </div>
    <div class="box-wrap-content frm-split-40-60">
        <?php $form = ActiveForm::begin( [ 'id' => 'frm-site' ] );?>

        <?= $form->field( $model, 'name' ) ?>
        <?= $form->field( $model, 'order' ) ?>
        <?= $form->field( $model, 'themeId' )->dropDownList( $themesMap ) ?>

        <div class="box-content clearfix">
            <div class="header">Site Avatar</div>
            <?= AvatarUploader::widget( [ 'options' => [ 'id' => 'model-avatar', 'class' => 'file-uploader' ], 'model' => $avatar ] );?>
        </div>

        <div class="box-content clearfix">
            <div class="header">Site Banner</div>
            <?= ImageUploader::widget( [ 'options' => [ 'id' => 'model-banner', 'class' => 'file-uploader' ], 'model' => $banner, 'modelClass' => 'Banner', 'directory' => 'banner' ] );?>
        </div>

        <div class="filler-height"></div>

        <div class="align align-center">
            <?=Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] );?>
            <input class="element-medium" type="submit" value="Update" />
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>