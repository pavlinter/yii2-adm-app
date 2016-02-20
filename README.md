Yii 2 Adm Application Template (Adm cms)
===================================================

composer create-project --prefer-dist --stability=dev pavlinter/yii2-adm-app projectName

 - yii migrate --migrationPath=@vendor/pavlinter/yii2-adm/adm/migrations
 - yii migrate --migrationPath=@vendor/pavlinter/yii2-adm-pages/admpages/migrations
 - yii migrate --migrationPath=@vendor/pavlinter/yii2-adm-params/admparams/migrations
 - yii migrate --migrationPath=@vendor/pavlinter/yii2-adm-email-config/admeconfig/migrations
 
 - yii migrate --migrationPath=@app/migrations