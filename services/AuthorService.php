<?php

declare(strict_types=1);

namespace app\services;

use app\models\Authors;
use app\models\AuthorsNotification;
use app\models\Books;
use app\models\BooksToAuthor;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\db\Query;
use yii\db\StaleObjectException;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class AuthorService
{
    public function getAll(): ActiveDataProvider
    {
        return new ActiveDataProvider([
            'query' => Authors::find(),
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
    public function getById(int $id): Authors
    {
        $model = Authors::findOne(['id' => $id]);
        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function create(Authors $model, array $postData): bool
    {
        return $model->load($postData) && $model->save();
    }

    public function update(Authors $model, array $postData): bool
    {
        return $model->load($postData) && $model->save();
    }

    /**
     * @throws \Throwable
     * @throws StaleObjectException
     * @throws NotFoundHttpException
     */
    public function delete(int $id): void
    {
        $this->getById($id)->delete();
    }

    public function getFullNameList(): array
    {
        $allAuthors = Authors::find()->all();
        return ArrayHelper::map($allAuthors, 'id', function($model) {
            return $model->fullName;
        });
    }

    public function getTopAuthors(int $year, int $limit = 10): array
    {
        return (new Query())
            ->select(['a.id', 'a.first_name', 'a.last_name', 'COUNT(b.id) AS book_count'])
            ->from(['a' => Authors::tableName()])
            ->innerJoin(['bta' => BooksToAuthor::tableName()], 'a.id = bta.author_id')
            ->innerJoin(['b' => Books::tableName()], 'bta.book_id = b.id')
            ->where(['b.year' => $year])
            ->groupBy(['a.id'])
            ->orderBy(['book_count' => SORT_DESC])
            ->limit($limit)
            ->all();
    }

    /**
     * @throws Exception
     */
    public function createNotification(AuthorsNotification $model, int $authorId): bool
    {
        $notification = AuthorsNotification::find()
            ->where(['phone_number' => $model->phone_number])
            ->andWhere(['status' => AuthorsNotification::STATUS_NEW])
            ->andWhere(['author_id' => $authorId])
            ->one();
        if (!empty($notification)) {
            return false;
        }

        $model->author_id = $authorId;
        return $model->save();
    }
}
