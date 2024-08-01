<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Books;
use app\services\AuthorService;
use app\services\BookService;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * BookController implements the CRUD actions for Books model.
 */
class BooksController extends Controller
{
    public function __construct($id, $module, private BookService $bookService, $config = [])
    {
        parent::__construct($id, $module, $config);
    }

    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'only'  => ['create', 'update', 'delete'],
                    'rules' => [
                        [
                            'actions' => ['create', 'update', 'delete'],
                            'allow'   => true,
                            'roles'   => ['@'],
                        ],
                    ],
                ],
                'verbs'  => [
                    'class'   => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ],
        );
    }

    public function actionIndex(): string
    {
        $dataProvider = $this->bookService->getAll();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {
        $model = $this->bookService->getById($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionCreate(): string|Response
    {
        $model = new Books();
        $authors = (new AuthorService())->getFullNameList();

        if ($this->request->isPost) {
            if ($this->bookService->create($model, $this->request->post())) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model'   => $model,
            'authors' => $authors
        ]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionUpdate(int $id): string|Response
    {
        $model = $this->bookService->getById($id);
        $authors = (new AuthorService())->getFullNameList();

        if ($this->request->isPost && $this->bookService->update($model, $this->request->post())) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'authors' => $authors,
        ]);
    }

    /**
     * @throws StaleObjectException
     * @throws \Throwable
     * @throws NotFoundHttpException
     */
    public function actionDelete(int $id): Response
    {
        $this->bookService->delete($id);

        return $this->redirect(['index']);
    }
}
