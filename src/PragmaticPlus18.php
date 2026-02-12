<?php

namespace pragmatic\plus18;

use Craft;
use craft\base\Model;
use craft\base\Plugin;
use craft\events\RegisterCpNavItemsEvent;
use craft\events\RegisterUrlRulesEvent;
use craft\web\UrlManager;
use craft\web\View;
use craft\web\twig\variables\Cp;
use pragmatic\plus18\models\Settings;
use yii\base\Event;

class PragmaticPlus18 extends Plugin
{
    public bool $hasCpSection = true;
    public string $templateRoot = 'src/templates';

    public static PragmaticPlus18 $plugin;

    public function init(): void
    {
        parent::init();
        self::$plugin = $this;

        Craft::$app->i18n->translations['pragmatic-plus18'] = [
            'class' => \yii\i18n\PhpMessageSource::class,
            'basePath' => __DIR__ . '/translations',
            'forceTranslation' => true,
        ];

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['pragmatic-plus18'] = 'pragmatic-plus18/default/index';
                $event->rules['pragmatic-plus18/general'] = 'pragmatic-plus18/default/general';
                $event->rules['pragmatic-plus18/options'] = 'pragmatic-plus18/default/options';
            }
        );

        // Register nav item under shared "Tools" group
        Event::on(
            Cp::class,
            Cp::EVENT_REGISTER_CP_NAV_ITEMS,
            function (RegisterCpNavItemsEvent $event) {
                $toolsLabel = Craft::t('pragmatic-plus18', 'Tools');
                $groupKey = null;
                foreach ($event->navItems as $key => $item) {
                    if (($item['label'] ?? '') === $toolsLabel && isset($item['subnav'])) {
                        $groupKey = $key;
                        break;
                    }
                }

                if ($groupKey === null) {
                    $newItem = [
                        'label' => $toolsLabel,
                        'url' => 'pragmatic-plus18',
                        'icon' => __DIR__ . '/icons/icon.svg',
                        'subnav' => [],
                    ];

                    $afterKey = null;
                    $insertAfter = ['users', 'assets', 'categories', 'entries'];
                    foreach ($insertAfter as $target) {
                        foreach ($event->navItems as $key => $item) {
                            if (($item['url'] ?? '') === $target) {
                                $afterKey = $key;
                                break 2;
                            }
                        }
                    }

                    if ($afterKey !== null) {
                        $pos = array_search($afterKey, array_keys($event->navItems), true) + 1;
                        $event->navItems = array_merge(
                            array_slice($event->navItems, 0, $pos, true),
                            ['pragmatic' => $newItem],
                            array_slice($event->navItems, $pos, null, true),
                        );
                        $groupKey = 'pragmatic';
                    } else {
                        $event->navItems['pragmatic'] = $newItem;
                        $groupKey = 'pragmatic';
                    }
                }

                $event->navItems[$groupKey]['subnav']['plus18'] = [
                    'label' => '+18',
                    'url' => 'pragmatic-plus18/general',
                ];
            }
        );

        Event::on(
            View::class,
            View::EVENT_END_BODY,
            function () {
                $request = Craft::$app->getRequest();
                if (!$request->getIsSiteRequest()) {
                    return;
                }

                /** @var Settings $settings */
                $settings = $this->getSettings();
                if (!$settings->enabled) {
                    return;
                }

                try {
                    echo Craft::$app->getView()->renderTemplate('pragmatic-plus18/frontend/_age-gate', [
                        'settings' => $settings,
                        'language' => Craft::$app->language,
                    ]);
                } catch (\Throwable $e) {
                    Craft::error($e->getMessage(), __METHOD__);
                }
            }
        );
    }

    protected function createSettingsModel(): ?Model
    {
        return new Settings();
    }

    public function getCpNavItem(): ?array
    {
        return null;
    }
}
