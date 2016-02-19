<?php

/*
 * This file is part of the Jirro package.
 *
 * (c) Rendy Eko Prastiyo <rendyekoprastiyo@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jirro\Component\Bundle;

class BundleContainer
{
    protected $bundles = [];

    public function register($bundleName, $namespace)
    {
        $bundleInstance = new $namespace($this->container);

        $this->bundles[$bundleName] = $bundleInstance;
    }

    public function get($bundleName)
    {
        if (array_key_exists($bundleName, $this->bundles)) {
            return $this->bundles[$bundleName];
        }

        return null;
    }

    public function getAll()
    {
        return $this->bundles;
    }
}
