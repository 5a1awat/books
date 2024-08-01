<?php

declare(strict_types=1);

namespace app\services;

use app\contracts\SmsInterface;
use app\models\AuthorsNotification;
use app\models\Books;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use Yii;

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
            $this->sendNotification($model);
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

    private function sendNotification(Books $model): void
    {
        $notifications = AuthorsNotification::find()
            ->andWhere(['author_id' => $model->authors])
            ->andWhere(['status' => AuthorsNotification::STATUS_NEW])
            ->all();

        $message = 'Новое поступление книги: ' . $model->name;

        $updatedId = [];
        foreach ($notifications as $notification) {
            $status = $this->smsService->send($notification->phone_number, $message);
            if ($status) {
                $updatedId[] = $notification->id;
            }
        }

        $this->updateNotification($updatedId);
    }

    private function updateNotification(array $idList): void
    {
        if (empty($idList)) {
            return;
        }

        Yii::$app->db->createCommand()
            ->update(
                AuthorsNotification::tableName(),
                ['status' => AuthorsNotification::STATUS_COMPLETE],
                'id IN (' . implode(', ', $idList) . ')'
            )->execute();
    }
}