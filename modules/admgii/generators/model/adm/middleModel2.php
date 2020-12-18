<?php
/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator \app\modules\admgii\generators\model\Generator */
/* @var $tableName string full table name */
/* @var $className string class name */
/* @var $tableSchema yii\db\TableSchema */
/* @var $labels string[] list of attribute labels (name => label) */
/* @var $rules string[] list of validation rules */
/* @var $relations array list of relations (name => relation declaration) */
/* @var $modelLangClass \yii\db\ActiveRecord */
/* @var $appadmClass string */
echo "<?php\n";
?>

namespace <?= $generator->nsMiddle ?>;

use Yii;

/**
 *
 */
class <?= $className ?> extends <?= '\\' . ltrim($appadmClass, '\\') . "\n" ?>
{
<?php foreach ($relations as $name => $relation){
        if (!in_array($name, ['Translation', 'Translations'])) {
            continue;
        }
    ?>

    /**
    * @return \yii\db\ActiveQuery
    */
    public function get<?= $name; ?>()
    {
        <?= $relation[0] . "\n" ?>
    }
<?php } ?>

}
