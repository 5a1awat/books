<?php

/** @var yii\web\View $this */

use yii\helpers\Url;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">Тестовое приложение!</h1>

        <p><a class="btn btn-lg btn-success" href="<?= Url::to(['/books/index']) ?>">Перейти в книги</a></p>
    </div>
</div>
