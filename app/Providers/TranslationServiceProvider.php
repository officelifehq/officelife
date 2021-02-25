<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class TranslationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        Cache::rememberForever('translations', function () {
            $translations = collect();

            foreach (['en'] as $locale) { // suported locales
                $translations[$locale] = [
                'php' => $this->phpTranslations($locale),
            ];
            }

            return $translations;
        });
    }

    private function phpTranslations(string $locale): Collection
    {
        $path = resource_path("lang/$locale");

        return collect(File::allFiles($path))->flatMap(function ($file) use ($locale) {
            $key = ($translation = $file->getBasename('.php'));

            return [$key => trans($translation, [], $locale)];
        });
    }
}
