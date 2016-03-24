<?php

namespace Adrenth\Redirect;

use App;
use Adrenth\Redirect\Classes\RedirectManager;
use Backend;
use Request;
use System\Classes\PluginBase;

/**
 * Class Plugin
 *
 * @package Adrenth\Redirect
 */
class Plugin extends PluginBase
{
    /**
     * {@inheritdoc}
     */
    public function pluginDetails()
    {
        return [
            'name' => 'adrenth.redirect::lang.plugin.name',
            'description' => 'adrenth.redirect::lang.plugin.description',
            'author' => 'A. Drenth <adrenth@gmail.com>',
            'icon' => 'icon-link',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        if (App::runningInBackend() || App::runningUnitTests()) {
            return;
        }

        // Check for running in console or backend before route matching
        $path = storage_path('app/redirects.csv');

        $manager = new RedirectManager($path);
        $rule = $manager->match(Request::getRequestUri());

        if ($rule) {
            $manager->redirectWithRule($rule);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function registerPermissions()
    {
        return [
            'adrenth.redirect.access_redirects' => [
                'label' => 'adrenth.redirect::lang.permission.access_redirects.label',
                'tab' => 'adrenth.redirect::lang.permission.access_redirects.tab',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function registerSettings()
    {
        return [
            'redirects' => [
                'label' => 'adrenth.redirect::lang.navigation.menu_label',
                'icon' => 'icon-link',
                'description' => 'adrenth.redirect::lang.navigation.menu_description',
                'url' => Backend::url('adrenth/redirect/redirects'),
                'order' => 500,
                'permissions' => [
                    'adrenth.redirect.access_redirects'
                ],
            ],
        ];
    }
}