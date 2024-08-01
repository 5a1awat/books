<?php

declare(strict_types=1);

namespace app\controllers;

use app\models\Authors;
use app\models\AuthorsNotification;
use app\services\AuthorService;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Yii;

/**
 * AuthorController implements the CRUD actions for Authors model.
 */
class AuthorsController extends Controller
{
    public function __construct($id, $module, private AuthorService $authorService, $config = [])
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
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function actionIndex(): string
    {
        $dataProvider = $this->authorService->getAll();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {
        $model = $this->authorService->getById($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionCreate(): string|Response
    {
        $model = new Authors();

        if ($this->request->isPost) {
            if ($this->authorService->create($model, $this->request->post())) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate(int $id): string|Response
    {
        $model = $this->authorService->getById($id);

        if ($this->request->isPost && $this->authorService->update($model, $this->request->post())) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete(int $id): Response
    {
        $this->authorService->delete($id);

        return $this->redirect(['index']);
    }

    public function actionNotification(int $id): string|Response
    {
        $model = new AuthorsNotification();
        $author = $this->authorService->getById($id);

        if ($this->request->isPost) {
            $model->load($this->request->post());
            if ($this->authorService->createNotification($model, $author->id)) {
                Yii::$app->session->setFlash('success', 'Вы успешно подписались на этого автора');
                return $this->redirect(['view', 'id' => $author->id]);
            } else {
                Yii::$app->session->setFlash('error', 'Вы уже подписаны на этого автора');
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('notification', [
            'model' => $model,
            'author' => $author,
        ]);
        
    }
}
