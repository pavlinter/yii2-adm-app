<?php
/* @var $tableName string */
/* @var $table string */
/* @var $tableLang string */
/* @var $generator \app\modules\admgii\generators\table\Generator */
/* @var $tablePrefix string */
?>

CREATE TABLE IF NOT EXISTS `<?= $table ?>` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`active` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'checkbox',
`weight` int(11) DEFAULT NULL COMMENT 'weight',
`created_at` timestamp NULL DEFAULT NULL COMMENT 'created_at',
`updated_at` timestamp NULL DEFAULT NULL COMMENT 'updated_at',
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

<?php if ($generator->isLang) {?>

CREATE TABLE IF NOT EXISTS `<?= $tableLang ?>` (
`<?= $tableName ?>_id` int(11) NOT NULL,
`language_id` int(11) NOT NULL,
PRIMARY KEY (`<?= $tableName ?>_id`,`language_id`),
KEY `<?= $tableName ?>_id` (`<?= $tableName ?>_id`),
KEY `language_id` (`language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `<?= $tableLang ?>`
ADD CONSTRAINT `<?= $tableName ?>_lang_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `<?= $tablePrefix ?>language` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `<?= $tableName ?>_lang_ibfk_1` FOREIGN KEY (`<?= $tableName ?>_id`) REFERENCES `<?= $table ?>` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
<?php }?>
