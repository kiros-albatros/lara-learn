<?php

namespace App\Helpers;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Inertia\Inertia;

class Navigation
{
    /**
     * @param string $configPath Путь в конфиге navigation
     *
     * @return array
     */
    public static function getLinks(string $configPath = 'default'): array
    {
        $links = config('navigation.' . $configPath, []);

        $currentParams = request()->route()->parameters();

        // fix для логики навигации
        if (! Arr::get($currentParams, 'product_type')) {
            $currentParams['product_type'] = 'tire'; //TODO: получить первый тип продукта
        }

        // Проставляем URL для пунктов, где они явно не заданы, но можно получить из роутов
        // Это также используется, чтобы сохранять текущие параметры
        $fixUrls = function (array &$links) use ($currentParams, &$fixUrls) {
            foreach ($links as &$link) {
                if (! isset($link['url']) and isset($link['route'])) {
                    // Отсекаю GET-параметры, т.к. не нашел, как находить их в исходном роуте,
                    // чтобы не подставлять вообще
                    $link['url'] = self::fixSlashes(
                        strtok(
                            route($link['route'], $currentParams, false),
                            '?'
                        )
                    );
                }
                if (isset($link['children']) and is_array($link['children'])) {
                    $fixUrls($link['children']);
                }
            }
        };
        $fixUrls($links);

        // Проставляем активность каждого пункта
        $currentUrl = self::fixSlashes(request()->path());
        $setActive = function (array &$links) use ($currentUrl, &$setActive) {
            $anyLinkIsActive = false;
            foreach ($links as &$link) {
                // Проставляем активность вложенных ссылок, и получаем
                $hasActiveChildren = (isset($link['children']) and is_array($link['children']) and $setActive(
                    $link['children']
                ));
                // Проставляем активными те ссылки, с которых начинается текущий URL
                $hasActiveUrls = collect([$link['url'], ...data_get($link, 'extraActiveUrls', [])])
                    // Для главной страницы должно быть точное соответствие, иначе она будет всегда активной
                    ->contains(
                        fn($linkUrl) => ($linkUrl === '/') ? ($currentUrl === '/') : str_starts_with(
                            $currentUrl,
                            $linkUrl
                        )
                    );
                $link['active'] = ($hasActiveChildren or $hasActiveUrls);
                // Если активен хотя бы один из элементов, пробрасываем информацию об этом наверх
                $anyLinkIsActive = ($anyLinkIsActive or $link['active']);
            }

            return $anyLinkIsActive;
        };
        $setActive($links);

        return $links;
    }

    /**
     * Исправляет пути, генерируемые Laravel routes, добавляя слеш в конце, где нужно
     *
     * @param string $url
     *
     * @return string
     */
    protected static function fixSlashes(string $url): string
    {
        return ($url === '/') ? $url : ('/' . trim($url, '/') . '/');
    }

    /**
     * Добавить переключатель по модели
     *
     * @param string $paramName Параметр роута, который будем заменять при переключении
     * @param array $values Значения параметра роута
     * @param string $placeholder Текст, который выводится, когда ничего не выбрано
     *
     * @return void
     */
    public static function addSwitcher(
        string $paramName,
        array $values,
        string $placeholder = 'Выберите из списка'
    ): void {
        // Текущий активный роут
        $currentRoute = request()->route();

        // Активное значение
        $routeParams = $currentRoute->parameters();
        $activeValue = data_get($routeParams, $paramName);

        $varName = Str::camel($paramName);
        Inertia::share($varName, $activeValue);

        // Ссылки переключения
        $switcherLinks = array_values(collect($values)->map(fn($title, $value) => [
            'url' => self::fixSlashes(app('url')->toRoute($currentRoute, [$paramName => $value], false)),
            'label' => $title,
            'active' => ($value === $activeValue),
            'name' => $value,
        ])->toArray());

        Inertia::share('switcherLinks', $switcherLinks);
        Inertia::share('switcherLinksPlaceholder', $placeholder);
    }
}
