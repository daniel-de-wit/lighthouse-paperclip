<?php

declare(strict_types=1);

namespace DanielDeWit\LighthousePaperclip\Providers;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use Nuwave\Lighthouse\Events\RegisterDirectiveNamespaces;

class LighthousePaperclipServiceProvider extends ServiceProvider
{
    public function boot(Dispatcher $dispatcher): void
    {
        $dispatcher->listen(
            RegisterDirectiveNamespaces::class,
            function (RegisterDirectiveNamespaces $registerDirectiveNamespaces): string {
                return 'DanielDeWit\\LighthousePaperclip\\Directives';
            }
        );
    }
}
