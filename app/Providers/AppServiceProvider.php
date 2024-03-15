<?php
/*
 * GorKa Team
 * Copyright (c) 2024  Vlad Horpynych <19dynamo27@gmail.com>, Pavel Karpushevskiy <pkarpushevskiy@gmail.com>
 */

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use League\CommonMark\GithubFlavoredMarkdownConverter;

/**
 * App Service Provider
 *
 * @package \App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() : void
    {
        /*
         * Set default string length for DB
         */
        // Schema::defaultStringLength(191);

        /*
         * Define custom macros
         */
        $this->defineCustomMacros();

        /*
         * Define custom Blade directives
         */
        $this->defineCustomBladeDirectives();

        /*
         * Forced HTTPS scheme
         */
//        if (env('APP_ENV') == 'production') {
//            URL::forceScheme('https');
//        }
    }

    /**
     * @return void
     */
    public function defineCustomMacros() : void
    {
        /*
         * Convert markdown in string to HTML
         */
        Str::macro('markdown', function ($content) {
            $converter = new GithubFlavoredMarkdownConverter([
                'html_input'         => 'strip',
                'allow_unsafe_links' => false,
            ]);

            return $converter->convert($content);
        });
    }

    /**
     * @return void
     */
    public function defineCustomBladeDirectives() : void
    {
        Blade::directive('markdown', function ($content) {
            return "<?php echo \Illuminate\Support\Str::markdown($content); ?>";
        });

        Blade::directive('lang', function ($expression) {
            // @see Illuminate\View\Compilers\Concerns\CompilesTranslations.php::compileLang()
            if (is_null($expression)) {
                return '<?php $__env->startTranslation(); ?>';
            } else if ($expression[1] === '[') {
                return "<?php \$__env->startTranslation{$expression}; ?>";
            }

            return "<?php echo nl2br(app('translator')->get({$expression})); ?>";
        });
    }
}
