<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jirro\Component\Bundle\Container\ServiceProvider;

use League\Container\ServiceProvider;
use Jirro\Component\Bundle\BundleContainer;

class BundleServiceProvider extends ServiceProvider
{
    protected $provides = [
        'bundle_container',
    ];

    public function register()
    {
        $this->container['bundle_container'] = function () {
            $bundleContainer = new BundleContainer();

            $config = $this->container->get('config');
            foreach ($config['bundles'] as $bundleName => $namespace) {
                $bundleContainer->register($bundleName, $namespace);
            }


            $config['orm']['mappings']  = [];
            $config['route_collection'] = [];
            $route                      = $this->container->get('route');
            foreach ($bundleContainer->getAll() as $bundle) {
                // register bundle ORM mappings to global ORM mappings
                if (isset($bundle->getConfig()['orm']['mappings'])) {
                    $config['orm']['mappings'] = array_merge(
                        $config['orm']['mappings'],
                        $bundle->getConfig()['orm']['mappings']
                    );
                }

                // register bundle route collection to global route collection
                if (isset($bundle->getConfig()['route_collection'])) {
                    foreach ($bundle->getConfig()['route_collection'] as $item) {
                        $route->addRoute(strtoupper($item['method']), $item['path'], $item['target']);
                    }

                    $config['route_collection'] = array_merge(
                        $config['route_collection'],
                        $bundle->getConfig()['route_collection']
                    );
                }
            }

            $this->container['config'] = $config;

            return $bundleContainer;
        };
    }
}
