<?php

declare(strict_types=1);

namespace app\helpers;

use yii\helpers\Html;

class AuthorHelper
{
    public static function getLink(array $authors): string
    {
        $links = array_map(function ($author) {
            return Html::a(
                Html::encode($author->first_name . ' ' . $author->last_name),
                ['authors/view', 'id' => $author->id]
            );
        }, $authors);

        return implode(', ', $links);
    }
}
