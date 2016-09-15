<?php
// Yii Imports
use yii\widgets\ActiveForm;
use yii\helpers\Html;

use cmsgears\files\widgets\AvatarUploader;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= $coreProperties->getSiteTitle() . ' | Delete User';
$returnUrl		= $this->context->returnUrl;
?>
<div class="box box-cud">
    <div class="box-wrap-header">
        <div class="header">Update User</div>
    </div>
    <div class="box-wrap-content frm-split-40-60">
        <?php $form = ActiveForm::begin( [ 'id' => 'frm-user' ] );?>

        <?= $form->field( $model, 'email' )->textInput( [ 'readonly' => 'true' ] ) ?>
        <?= $form->field( $model, 'username' )->textInput( [ 'readonly' => 'true' ] ) ?>
        <?= $form->field( $model, 'firstName' )->textInput( [ 'readonly' => 'true' ] ) ?>
        <?= $form->field( $model, 'lastName' )->textInput( [ 'readonly' => 'true' ] ) ?>
        <?= $form->field( $siteMember, 'roleId' )->dropDownList( $roleMap, [ 'disabled' => 'true' ] )  ?>
        <?= $form->field( $model, 'status' )->dropDownList( $status, [ 'disabled' => 'true' ] ) ?>

        <div class="clear filler-height"></div>

        <div class="align align-center">
            <?=Html::a( 'Cancel', $returnUrl, [ 'class' => 'btn btn-medium' ] );?>
            <input class="element-medium" type="submit" value="Delete" />
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>