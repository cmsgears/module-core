<?php
use yii\widgets\ActiveForm;

$coreProperties = $this->context->getCoreProperties();
$this->title 	= 'Activate Account | ' . $coreProperties->getSiteTitle();
?>
<section class="wrap-content wrap-content-full container clearfix">
    <div class="cud-box wrap-login-box frm-split">
        <h1>Activate Account</h1>
        <?php 
            if( Yii::$app->session->hasFlash( 'message' ) ) {
        ?>
            <p><?= Yii::$app->session->getFlash( 'message' ) ?></p>
        <?php
            }
            else {
        
                $form = ActiveForm::begin( [ 'id' => 'frm-activate-account' ] );
        ?>
                <?= $form->field( $model, 'password' )->passwordInput() ?>
                <?= $form->field( $model, 'password_repeat' )->passwordInput() ?>
        
                <div class="box-filler"></div>
                
                <input type="submit" value="Activate" />
        <?php
                ActiveForm::end();
            }
        ?>
    </div>
</section>