<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;
use App\Models\Permission;
use Illuminate\Support\Str;

class SyncRoutePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:sync-routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all named routes to the permissions table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $routes = Route::getRoutes();
        $count = 0;

        foreach ($routes as $route) {
            $routeName = $route->getName();
            
            // Only process named routes and ignore standard Laravel routes like ignition, auth, etc.
            if ($routeName && !Str::startsWith($routeName, ['ignition', 'sanctum', 'api'])) {
                
                // Determine module based on route prefix/name
                $module = explode('.', $routeName)[0];
                $module = Str::title(str_replace('_', ' ', $module));

                // Generate a readable name from the route name
                $name = str_replace('.', ' ', $routeName);
                $name = Str::title(str_replace('_', ' ', $name));

                // Description (could be customized later)
                $description = "Allow access to $routeName";

                Permission::updateOrCreate(
                    ['slug' => $routeName], // We use route name as the slug
                    [
                        'name' => $name,
                        'module' => $module,
                        'description' => $description
                    ]
                );

                $count++;
            }
        }

        $this->info("Successfully synced $count route permissions.");
    }
}
