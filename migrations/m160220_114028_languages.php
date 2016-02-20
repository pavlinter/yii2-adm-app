<?php

use yii\db\Migration;

/**
 * Class m160220_114028_languages
 */
class m160220_114028_languages extends Migration
{
    /**
     * This method contains the logic to be executed when applying this migration.
     * Child classes may override this method to provide actual migration logic.
     * @return boolean return a false value to indicate the migration fails
     * and should not proceed further. All other return values mean the migration succeeds.
     */
    public function safeUp()
    {
        $this->update('{{%language}}', [
            'image' => '/files/languages/en.jpg',
        ], 'code="en"');

        $this->update('{{%language}}', [
            'image' => '/files/languages/ru.jpg',
        ], 'code="ru"');

        $this->insert('{{%language}}', [
            'code' => 'lv',
            'name' => 'Latvian',
            'weight' => 150,
            'updated_at' => time(),
            'image' => '/files/languages/lv.jpg',
            'active' => 1,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ab',
            'name' => 'Abkhaz',
            'weight' => 200,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'aa',
            'name' => 'Afar',
            'weight' => 250,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'af',
            'name' => 'Afrikaans',
            'weight' => 300,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ak',
            'name' => 'Akan',
            'weight' => 350,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'sq',
            'name' => 'Albanian',
            'weight' => 400,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'am',
            'name' => 'Amharic',
            'weight' => 450,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ar',
            'name' => 'Arabic',
            'weight' => 500,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'an',
            'name' => 'Aragonese',
            'weight' => 550,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'hy',
            'name' => 'Armenian',
            'weight' => 600,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'as',
            'name' => 'Assamese',
            'weight' => 650,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'av',
            'name' => 'Avaric',
            'weight' => 700,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ae',
            'name' => 'Avestan',
            'weight' => 750,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ay',
            'name' => 'Aymara',
            'weight' => 800,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'az',
            'name' => 'Azerbaijani',
            'weight' => 850,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'bm',
            'name' => 'Bambara',
            'weight' => 900,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ba',
            'name' => 'Bashkir',
            'weight' => 950,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'eu',
            'name' => 'Basque',
            'weight' => 1000,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'be',
            'name' => 'Belarusian',
            'weight' => 1050,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'bn',
            'name' => 'Bengali',
            'weight' => 1100,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'bh',
            'name' => 'Bihari',
            'weight' => 1150,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'bi',
            'name' => 'Bislama',
            'weight' => 1200,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'bs',
            'name' => 'Bosnian',
            'weight' => 1250,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'br',
            'name' => 'Breton',
            'weight' => 1300,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'bg',
            'name' => 'Bulgarian',
            'weight' => 1350,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'my',
            'name' => 'Burmese',
            'weight' => 1400,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ca',
            'name' => 'Catalan',
            'weight' => 1450,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ch',
            'name' => 'Chamorro',
            'weight' => 1500,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ce',
            'name' => 'Chechen',
            'weight' => 1550,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ny',
            'name' => 'Chichewa',
            'weight' => 1600,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'zh',
            'name' => 'Chinese',
            'weight' => 1650,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'cv',
            'name' => 'Chuvash',
            'weight' => 1700,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'kw',
            'name' => 'Cornish',
            'weight' => 1750,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'co',
            'name' => 'Corsican',
            'weight' => 1800,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'cr',
            'name' => 'Cree',
            'weight' => 1850,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'hr',
            'name' => 'Croatian',
            'weight' => 1900,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'cs',
            'name' => 'Czech',
            'weight' => 1950,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'da',
            'name' => 'Danish',
            'weight' => 2000,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'dv',
            'name' => 'Divehi',
            'weight' => 2050,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'nl',
            'name' => 'Dutch',
            'weight' => 2100,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'dz',
            'name' => 'Dzongkha',
            'weight' => 2150,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'eo',
            'name' => 'Esperanto',
            'weight' => 2200,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'et',
            'name' => 'Estonian',
            'weight' => 2250,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ee',
            'name' => 'Ewe',
            'weight' => 2300,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'fo',
            'name' => 'Faroese',
            'weight' => 2350,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'fj',
            'name' => 'Fijian',
            'weight' => 2400,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'fi',
            'name' => 'Finnish',
            'weight' => 2450,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'fr',
            'name' => 'French',
            'weight' => 2500,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ff',
            'name' => 'Fula',
            'weight' => 2550,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'gl',
            'name' => 'Galician',
            'weight' => 2600,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ka',
            'name' => 'Georgian',
            'weight' => 2650,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'de',
            'name' => 'German',
            'weight' => 2700,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'el',
            'name' => 'Greek',
            'weight' => 2750,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'gn',
            'name' => 'Guaraní',
            'weight' => 2800,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'gu',
            'name' => 'Gujarati',
            'weight' => 2850,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ht',
            'name' => 'Haitian',
            'weight' => 2900,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ha',
            'name' => 'Hausa',
            'weight' => 2950,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'he',
            'name' => 'Hebrew',
            'weight' => 3000,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'hz',
            'name' => 'Herero',
            'weight' => 3050,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'hi',
            'name' => 'Hindi',
            'weight' => 3100,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ho',
            'name' => 'Hiri Motu',
            'weight' => 3150,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'hu',
            'name' => 'Hungarian',
            'weight' => 3200,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ia',
            'name' => 'Interlingua',
            'weight' => 3250,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'id',
            'name' => 'Indonesian',
            'weight' => 3300,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ie',
            'name' => 'Interlingue',
            'weight' => 3350,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ga',
            'name' => 'Irish',
            'weight' => 3400,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ig',
            'name' => 'Igbo',
            'weight' => 3450,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ik',
            'name' => 'Inupiaq',
            'weight' => 3500,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'io',
            'name' => 'Ido',
            'weight' => 3550,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'is',
            'name' => 'Icelandic',
            'weight' => 3600,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'it',
            'name' => 'Italian',
            'weight' => 3650,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'iu',
            'name' => 'Inuktitut',
            'weight' => 3700,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ja',
            'name' => 'Japanese',
            'weight' => 3750,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'jv',
            'name' => 'Javanese',
            'weight' => 3800,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'kl',
            'name' => 'Kalaallisut',
            'weight' => 3850,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'kn',
            'name' => 'Kannada',
            'weight' => 3900,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'kr',
            'name' => 'Kanuri',
            'weight' => 3950,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ks',
            'name' => 'Kashmiri',
            'weight' => 4000,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'kk',
            'name' => 'Kazakh',
            'weight' => 4050,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'km',
            'name' => 'Khmer',
            'weight' => 4100,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ki',
            'name' => 'Kikuyu',
            'weight' => 4150,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'rw',
            'name' => 'Kinyarwanda',
            'weight' => 4200,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ky',
            'name' => 'Kyrgyz',
            'weight' => 4250,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'kv',
            'name' => 'Komi',
            'weight' => 4300,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'kg',
            'name' => 'Kongo',
            'weight' => 4350,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ko',
            'name' => 'Korean',
            'weight' => 4400,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ku',
            'name' => 'Kurdish',
            'weight' => 4450,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'kj',
            'name' => 'Kwanyama',
            'weight' => 4500,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'la',
            'name' => 'Latin',
            'weight' => 4550,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'lb',
            'name' => 'Luxembourgish',
            'weight' => 4600,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'lg',
            'name' => 'Ganda',
            'weight' => 4650,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'li',
            'name' => 'Limburgish',
            'weight' => 4700,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ln',
            'name' => 'Lingala',
            'weight' => 4750,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'lo',
            'name' => 'Lao',
            'weight' => 4800,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'lt',
            'name' => 'Lithuanian',
            'weight' => 4850,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'lu',
            'name' => 'Luba-Katanga',
            'weight' => 4900,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'gv',
            'name' => 'Manx',
            'weight' => 4950,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'mk',
            'name' => 'Macedonian',
            'weight' => 5000,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'mg',
            'name' => 'Malagasy',
            'weight' => 5050,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ms',
            'name' => 'Malay',
            'weight' => 5100,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ml',
            'name' => 'Malayalam',
            'weight' => 5150,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'mt',
            'name' => 'Maltese',
            'weight' => 5200,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'mi',
            'name' => 'Māori',
            'weight' => 5250,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'mr',
            'name' => 'Marathi (Marāṭhī)',
            'weight' => 5300,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'mh',
            'name' => 'Marshallese',
            'weight' => 5350,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'mn',
            'name' => 'Mongolian',
            'weight' => 5400,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'na',
            'name' => 'Nauruan',
            'weight' => 5450,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'nv',
            'name' => 'Navajo',
            'weight' => 5500,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'nd',
            'name' => 'Northern Ndebele',
            'weight' => 5550,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ne',
            'name' => 'Nepali',
            'weight' => 5600,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ng',
            'name' => 'Ndonga',
            'weight' => 5650,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'nb',
            'name' => 'Norwegian Bokmål',
            'weight' => 5700,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'nn',
            'name' => 'Norwegian Nynorsk',
            'weight' => 5750,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'no',
            'name' => 'Norwegian',
            'weight' => 5800,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ii',
            'name' => 'Nuosu',
            'weight' => 5850,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'nr',
            'name' => 'Southern Ndebele',
            'weight' => 5900,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'oc',
            'name' => 'Occitan',
            'weight' => 5950,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'oj',
            'name' => 'Ojibwe',
            'weight' => 6000,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'cu',
            'name' => 'Old Church Slavonic',
            'weight' => 6050,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'om',
            'name' => 'Oromo',
            'weight' => 6100,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'or',
            'name' => 'Oriya',
            'weight' => 6150,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'os',
            'name' => 'Ossetian',
            'weight' => 6200,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'pa',
            'name' => 'Panjabi',
            'weight' => 6250,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'pi',
            'name' => 'Pāli',
            'weight' => 6300,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'fa',
            'name' => 'Persian',
            'weight' => 6350,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'pl',
            'name' => 'Polish',
            'weight' => 6400,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ps',
            'name' => 'Pashto',
            'weight' => 6450,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'pt',
            'name' => 'Portuguese',
            'weight' => 6500,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'qu',
            'name' => 'Quechua',
            'weight' => 6550,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'rm',
            'name' => 'Romansh',
            'weight' => 6600,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'rn',
            'name' => 'Kirundi',
            'weight' => 6650,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ro',
            'name' => 'Romanian',
            'weight' => 6700,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'sa',
            'name' => 'Sanskrit (Saṁskṛta)',
            'weight' => 6750,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'sc',
            'name' => 'Sardinian',
            'weight' => 6800,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'sd',
            'name' => 'Sindhi',
            'weight' => 6850,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'se',
            'name' => 'Northern Sami',
            'weight' => 6900,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'sm',
            'name' => 'Samoan',
            'weight' => 6950,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'sg',
            'name' => 'Sango',
            'weight' => 7000,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'sr',
            'name' => 'Serbian',
            'weight' => 7050,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'gd',
            'name' => 'Scottish Gaelic',
            'weight' => 7100,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'sn',
            'name' => 'Shona',
            'weight' => 7150,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'si',
            'name' => 'Sinhala',
            'weight' => 7200,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'sk',
            'name' => 'Slovak',
            'weight' => 7250,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'sl',
            'name' => 'Slovene',
            'weight' => 7300,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'so',
            'name' => 'Somali',
            'weight' => 7350,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'st',
            'name' => 'Southern Sotho',
            'weight' => 7400,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'es',
            'name' => 'Spanish',
            'weight' => 7450,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'su',
            'name' => 'Sundanese',
            'weight' => 7500,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'sw',
            'name' => 'Swahili',
            'weight' => 7550,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ss',
            'name' => 'Swati',
            'weight' => 7600,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'sv',
            'name' => 'Swedish',
            'weight' => 7650,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ta',
            'name' => 'Tamil',
            'weight' => 7700,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'te',
            'name' => 'Telugu',
            'weight' => 7750,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'tg',
            'name' => 'Tajik',
            'weight' => 7800,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'th',
            'name' => 'Thai',
            'weight' => 7850,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ti',
            'name' => 'Tigrinya',
            'weight' => 7900,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'bo',
            'name' => 'Tibetan Standard',
            'weight' => 7950,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'tk',
            'name' => 'Turkmen',
            'weight' => 8000,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'tl',
            'name' => 'Tagalog',
            'weight' => 8050,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'tn',
            'name' => 'Tswana',
            'weight' => 8100,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'to',
            'name' => 'Tonga',
            'weight' => 8150,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'tr',
            'name' => 'Turkish',
            'weight' => 8200,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ts',
            'name' => 'Tsonga',
            'weight' => 8250,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'tt',
            'name' => 'Tatar',
            'weight' => 8300,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'tw',
            'name' => 'Twi',
            'weight' => 8350,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ty',
            'name' => 'Tahitian',
            'weight' => 8400,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ug',
            'name' => 'Uyghur',
            'weight' => 8450,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'uk',
            'name' => 'Ukrainian',
            'weight' => 8500,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'ur',
            'name' => 'Urdu',
            'weight' => 8550,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'uz',
            'name' => 'Uzbek',
            'weight' => 8600,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 've',
            'name' => 'Venda',
            'weight' => 8650,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'vi',
            'name' => 'Vietnamese',
            'weight' => 8700,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'vo',
            'name' => 'Volapük',
            'weight' => 8750,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'wa',
            'name' => 'Walloon',
            'weight' => 8800,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'cy',
            'name' => 'Welsh',
            'weight' => 8850,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'wo',
            'name' => 'Wolof',
            'weight' => 8900,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'fy',
            'name' => 'Western Frisian',
            'weight' => 8950,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'xh',
            'name' => 'Xhosa',
            'weight' => 9000,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'yi',
            'name' => 'Yiddish',
            'weight' => 9050,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'yo',
            'name' => 'Yoruba',
            'weight' => 9100,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'za',
            'name' => 'Zhuang',
            'weight' => 9150,
            'updated_at' => time(),
            'active' => 0,
        ]);

        $this->insert('{{%language}}', [
            'code' => 'zu',
            'name' => 'Zulu',
            'weight' => 9200,
            'updated_at' => time(),
            'active' => 0,
        ]);
    }

    /**
     * This method contains the logic to be executed when removing this migration.
     * This method differs from [[down()]] in that the DB logic implemented here will
     * be enclosed within a DB transaction.
     * Child classes may implement this method instead of [[up()]] if the DB logic
     * needs to be within a transaction.
     * @return boolean return a false value to indicate the migration fails
     * and should not proceed further. All other return values mean the migration succeeds.
     */
    public function safeDown()
    {
        $this->delete('{{%language}}', 'code NOT IN("ru", "en")');
    }

}
