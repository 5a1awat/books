<?php

declare(strict_types=1);

namespace app\controllers;

use app\services\AuthorService;
use yii\web\Controller;

/**
 * AuthorController implements the CRUD actions for Authors model.
 */
class ReportsController extends Controller
{
    public function actionIndex(): string
    {
        $year = intval(date('Y'));
        $topAuthors = ((new AuthorService)->getTopAuthors($year));

        return $this->render('index', [
            'topAuthors' => $topAuthors,
            'year' => $year,
        ]);
    }
}
