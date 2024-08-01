<?php

use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Authors $model */
/** @var app\models\Authors $author */

$this->title = 'Подписка на уведомление новых книг автора: ' . $author->fullName;
YiiAsset::register($this);
?>
<div class="authors-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="authors-form">

        <?php
        $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'phone_number')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Подписаться', ['class' => 'btn btn-success']) ?>
        </div>

        <?php
        ActiveForm::end(); ?>

    </div>

</div>
