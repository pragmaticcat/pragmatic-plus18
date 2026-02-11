<?php

namespace pragmatic\plus18;

use craft\base\Plugin;
use craft\events\RegisterUrlRulesEvent;
use craft\web\UrlManager;
use yii\base\Event;

class PragmaticPlus18 extends Plugin
{
    public bool $hasCpSection = true;
    public string $templateRoot = 'src/templates';

    public function init(): void
    {
        parent::init();

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['pragmatic-plus18'] = 'pragmatic-plus18/default/index';
                $event->rules['pragmatic-plus18/general'] = 'pragmatic-plus18/default/general';
                $event->rules['pragmatic-plus18/options'] = 'pragmatic-plus18/default/options';
            }
        );
    }

    public function getCpNavItem(): array
    {
        $item = parent::getCpNavItem();
        $item['label'] = 'Pragmatic';
        $item['subnav'] = [
            'plus18' => [
                'label' => '+18',
                'url' => 'pragmatic-plus18/general',
            ],
        ];

        return $item;
    }
}
