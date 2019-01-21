<?php

namespace app\modules\appadm\controllers;

use app\core\adm\models\Message;
use app\core\adm\models\SourceMessage;
use Yii;
use pavlinter\adm\Adm;
use yii\db\Expression;
use yii\db\Query;
use yii\web\Controller;

/**
 * ExportController
 */
class ExportController extends Controller
{
    /**
    * @inheritdoc
    */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \pavlinter\adm\filters\AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['AdmRoot'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return mixed
     */
    public function actionExportTranslations()
    {

        $query = new Query();
        $query->from(SourceMessage::tableName());
        $notLikes = [
            /*'adm',
            'i18n-dot',
            'admpages',
            'modelAdm',
            'model/contact_msg',
            'model/news',
            'admnews',*/
        ];

        if ($notLikes) {
            $notLikesCondition = ['and'];
            foreach ($notLikes as $like) {
                $notLikesCondition[] = ['not like', 'category', new Expression("'" . $like . "%'")];
            }
            $query->andWhere($notLikesCondition);
        }

        $data = [];
        $string = "<?php\rreturn [\r";
        $reader = $query->createCommand()->query();
        while (($row = $reader->read())) {

            $data[$row['id']] = [
                'category' => $row['category'],
                'message' => $row['message'],
                'translations' => [],
            ];

            $string .= "\t[\r\t\t\"category\" => \"" . $row['category'] . "\",\r\t\t\"message\" => \"" . $row['message'] . "\",\r\t\t\"translations\" => [\r";


            $query2 = new Query();
            $query2->from(Message::tableName())
                ->where(['id' => $row['id']]);

            $translations = $query2->all();
            
            foreach ($translations as $translation) {
                if ($translation['translation'] !== '') {
                    $data[$row['id']]['translations'][] = [
                        'language_id' => $translation['language_id'],
                        'translation' => $translation['translation'],
                    ];
                    $string .= "\t\t\t[\r\t\t\t\t\"language_id\" => \"" . $translation['language_id'] ."\",\r\t\t\t\t\"translation\" => \"" . addslashes($translation['translation']) . "\",\r\t\t\t],\r";
                }

            }
            $string .= "\t\t],\r\t],\r";
        }
        $string .= "];";

        file_put_contents(Yii::getAlias('@app/migrations/translations.php'), $string);

        Yii::$app->getSession()->setFlash('success', Adm::t('source-message', 'Data successfully saved!'));
        return Adm::redirect(['/adm/source-message/index']);
    }
}
