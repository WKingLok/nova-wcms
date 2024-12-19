<?php

namespace Packages\Basic;

use Packages\Basic\Nova\Administrator;
use Packages\Basic\Nova\AuditLog;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Laravel\Nova\Menu\MenuGroup;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use App\Nova\Dashboards\Main;
use Illuminate\Support\Arr;

class BasicNovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Register routes
         */
        $this->routes();

        /**
         * Register nova resources
         */
        Nova::resources([
            Administrator::class,
            AuditLog::class,
        ]);


        //add dark-mode support
        Nova::script('dark-mode', __DIR__ . '/../resources/js/dark-mode.js');

        //override nova footer display content
        Nova::footer(
            fn() =>
            '<p class="text-center">Powered by <a class="link-default" href="https://hk.linkedin.com/in/alson-chi">Alson Chi</a></p><p class="text-center"> © ' . date('Y') . ' All Rights Reserved.</p>'
        );

        //override nova menu
        Nova::mainMenu(function () {
            $config = config('wcms.menu', []);

            return [
                MenuSection::dashboard(Main::class)->icon('view-grid'),
                ...Arr::map($config, function (array $value) {
                    if (!data_get($value, 'items', false)) {
                        return $this->generateMenuItem($value);
                    }

                    $menu = null;
                    $items = Arr::map(data_get($value, 'items', []), fn($item) => $this->generateMenuItem($item));

                    //check menu is collapsable?
                    if (data_get($value, 'collapsable', false)) {
                        $menu = MenuSection::make(data_get($value, 'title'), $items)
                            ->collapsable();

                        //if has icon
                        if ($icon = data_get($value, 'icon')) {
                            $menu->icon($icon);
                        }
                    } else {
                        $menu = MenuGroup::make(data_get($value, 'title'), $items);
                    }


                    return $menu;
                }),
                ...$this->generateSystemItem(),
            ];
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes() {}

    /**
     * generate menu item with different types
     *
     * @return MenuItem
     */
    private function generateMenuItem(array $item): MenuItem
    {
        switch ($item['type']) {
            case 'externalLink':
                $_item = MenuItem::externalLink($item['label'], $item['link']);

                //if open new tag
                if (data_get($item, 'newTag', false)) {
                    $_item->openInNewTab();
                }

                return $_item;

            case 'dashboard':
                return MenuItem::dashboard($item['class']);

            case 'lens':
                return MenuItem::lens($item['class'], $item['lensClass']);

            case 'link':
                return MenuItem::link($item['label'], str_replace('/wcms', '', route($item['route'], [], false)));

            default: //resource
                return MenuItem::resource($item['class']);
        }
    }

    /**
     * generate menu item with different types
     *
     * @return MenuSection
     */
    private function generateSystemItem(): array
    {
        $items = [];
        $user = auth()->user();

        //when user is superadmin
        if ($user && $user->hasRole('SuperAdmin')) {
            array_push(
                $items,
                MenuItem::resource("Packages\Basic\Nova\AuditLog")
            );

            array_push(
                $items,
                MenuItem::externalLink("Health", url("wcms/health"))
            );
        }

        //add system log-viewer
        if (config('log-viewer.enabled', false)  && in_array(data_get($user, 'email'), config('log-viewer.whitelist'))) {
            array_push(
                $items,
                MenuItem::externalLink("Server Logs", url("wcms/logs"))
            );
        }


        if (!$items) {
            return [];
        }

        return [
            MenuSection::make("System", $items, "cog")->collapsable()
        ];
    }
}
