<?php

declare(strict_types=1);

namespace app\services;

use app\contracts\SmsInterface;
use app\models\AuthorsNotification;
use app\models\Books;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;

class BookService
{
    public function __construct(private SmsInterface $smsService)
    {
    }
    
    public function getAll(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Books::find(),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function getById(int $id): Books
    {
        $model = Books::findOne(['id' => $id]);
        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function create(Books $model, array $postData): bool
    {
        $act = $model->load($postData) && $model->save() && $model->saveAuthors();

        if ($act) {
            $notifications = AuthorsNotification::find()
                ->andWhere(['author_id' => $model->authors])
                ->andWhere(['status' => AuthorsNotification::STATUS_NEW])
                ->all();

            $message = 'Новое поступление книги: ' . $model->name;

            foreach ($notifications as $notification) {
                $status = $this->smsService->send($notification->phone_number, $message);
                if ($status) {
                    $doneNotification = AuthorsNotification::findOne($notification->id);
                    $doneNotification->sentNotification();
                    $doneNotification->save();
                }
            }

        }

        return $act;
    }

    public function update(Books $model, array $postData): bool
    {
        return $model->load($postData) && $model->save() && $model->saveAuthors();
    }

    /**
     * @throws StaleObjectException
     * @throws \Throwable
     * @throws NotFoundHttpException
     */
    public function delete(int $id): void
    {
        $this->getById($id)->delete();
    }
}