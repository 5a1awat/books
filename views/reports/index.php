<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $topAuthors array */
/* @var $year int */

$this->title = "Топ 10 авторов, выпустивших больше всего книг в $year году";
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="report-top-authors">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (empty($topAuthors)): ?>
        <p>За текущий год не было выпущено ни одной книги.</p>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Автор</th>
                <th>Количество книг</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($topAuthors as $index => $author): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= Html::encode($author['first_name'] . ' ' . $author['last_name']) ?></td>
                    <td><?= $author['book_count'] ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
