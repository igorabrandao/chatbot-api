<?php
namespace api\modules\v1;
use yii\filters\Cors;

/**
 * module module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'api\modules\v1\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        // custom initialization code goes here
    }
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);
        $behaviors['corsFilter'] = [
            'class' => Cors::className(),
            'cors' => [
                'Origin' => [
                    'http://loja.webfarma.online',
                    'https://loja.webfarma.online',
                    'http://app.webfarma.online',
                    'https://app.webfarma.online',
                    'http://painel.webfarma.online',
                    'https://painel.webfarma.online',
                    'http://webfarma.online',
                    'https://webfarma.online',
                    'http://localhost:4200',
                    'http://localhost:4201',
                    'http://localhost:4202',
                    'http://localhost:8100',
                    'http://localhost:8101',
                    'http://192.168.1.122:8100',
                    'http://localhost',
                    'ionic://localhost'],
                'Access-Control-Request-Headers' => ['Authorization', 'Content-Type'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'HEAD', 'OPTIONS', 'DELETE', 'PUT'],
                'Access-Control-Allow-Credentials' => ['true'],
                'Access-Control-Expose-Headers' => [
                    'X-Pagination-Current-Page',
                    'X-Pagination-Page-Count',
                    'X-Pagination-Per-Page',
                    'X-Pagination-Total-Count',
                    'Link'
                ]
            ]
        ];
        return $behaviors;
    }
}
