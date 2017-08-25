<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii2mod\editable\EditableColumn;

/* @var $this yii\web\View */
/* @var $searchModel */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('systemparam', 'Manage Params');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="system-param-model-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            Yii::$app->get('systemparams')->modelMap['systemParam']['class']::attributesMap()['fieldParamKey'],
            Yii::$app->get('systemparams')->modelMap['systemParam']['class']::attributesMap()['fieldDescription'],
            [
                'class' => EditableColumn::className(),
                'attribute' => Yii::$app->get('systemparams')->modelMap['systemParam']['class']::attributesMap()['fieldParamValue'],
                'url' => ['update'],
            ],
        ],
    ]); ?>
</div>
