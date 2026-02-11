<?php

namespace pragmatic\plus18\controllers;

use craft\web\Controller;
use yii\web\Response;

class DefaultController extends Controller
{
    protected int|bool|array $allowAnonymous = false;

    public function actionIndex(): Response
    {
        return $this->redirect('pragmatic-plus18/general');
    }

    public function actionGeneral(): Response
    {
        return $this->renderTemplate('pragmatic-plus18/general');
    }

    public function actionOptions(): Response
    {
        return $this->renderTemplate('pragmatic-plus18/options');
    }
}
