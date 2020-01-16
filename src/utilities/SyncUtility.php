<?php
    namespace weareferal\sync\utilities;

    use Craft;
    use craft\base\Utility;
    use craft\web\assets\dbbackup\DbBackupAsset;

    use weareferal\sync\assets\SyncAsset;
    use weareferal\sync\Sync;

    class SyncUtility extends Utility
    {
        public static function displayName(): string
        {
            return Craft::t('app', 'Sync');
        }

        public static function id(): string
        {
            return 'sync';
        }

        public static function iconPath()
        {
            Craft::info('craft-sync:' .Sync::getInstance()->getBasePath() . DIRECTORY_SEPARATOR . 'utility-icon.svg');
            return Sync::getInstance()->getBasePath() . DIRECTORY_SEPARATOR . 'utility-icon.svg';
        }

        public static function contentHtml(): string
        {
            $view = Craft::$app->getView();

            // Make use of the existing default database backup utility
            $view->registerAssetBundle(DbBackupAsset::class);
            $view->registerJs('new Craft.DbBackupUtility(\'create-database-backup\');');

            $view->registerAssetBundle(SyncAsset::class);
            $forms = [
                ['create-volumes-backup', true],
                ['push-database', false],
                ['push-volumes', false],
                ['pull-database', true],
                ['pull-volumes', true],
                ['restore-database-backup', false],
                ['restore-volumes-backup', false]
            ];
            foreach ($forms as $form) {
                $view->registerJs("new Craft.SyncUtility('" . $form[0] . "', " . $form[1] . ");");
            }

            $dbBackupOptions = Sync::getInstance()->sync->getDbBackupOptions();
            $volumeBackupOptions = Sync::getInstance()->sync->getVolumeBackupOptions();

            return $view->renderTemplate('sync/_components/utilities/sync', [
                "settingConfigured"=>Sync::getInstance()->getSettings()->isConfigured(),
                "dbBackupOptions"=>$dbBackupOptions,
                "volumes"=> Craft::$app->getVolumes()->getAllVolumes(),
                "volumeBackupOptions"=>$volumeBackupOptions
            ]);
        }
    }
?>