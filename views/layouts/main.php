<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use app\models\Movie;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
<body class="d-flex flex-column" style="min-height: 100vh;">
<?php $this->beginBody() ?>
    <header>
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light">
                <a class="navbar-brand" href="#">
                    <img src="<?= Yii::$app->storage->getFileUri('logo.png') ?>" alt="">
                </a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/">Главная</a>
                        </li>
                        <?php foreach (Movie::CATEGORY_LABELS as $category => $element): ?>
                            <li class="nav-item">
                                <?= Html::a($element['plural'], ['/movie/list', 'category' => $category], ['class' => 'nav-link']) ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <?= Html::beginForm(['/movie/search'], 'post', ['class' => 'form-inline my-2 my-lg-0']) ?>
                        <div class="input-group">
                            <input id="header-search-name" class="form-control" name="headerSearchName" type="search" placeholder="Что ищем?" aria-label="Search">
                            <div class="input-group-append">
                                <select class="custom-select" name="headerSearchCategory">
                                    <option value="0" selected>Все</option>
                                    <?php foreach (Movie::CATEGORY_LABELS as $category => $element): ?>
                                        <option value="<?= $category ?>"><?= $element['plural'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <button class="btn btn-outline-primary my-2 my-sm-0 ml-sm-3" type="submit">Поиск</button>
                    <?= Html::endForm() ?>
                </div>
            </nav>
        </div>
    </header>

    <main class="pb-3">
        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
    </main>

    <footer class="bg-dark text-light mt-auto">
        <div class="container">
            <div class="py-3">&copy; Movie Catalog <?= date('Y') ?></div>
        </div>
    </footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
