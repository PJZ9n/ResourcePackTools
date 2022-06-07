<?php

/**
 * Copyright (c) 2020 PJZ9n.
 *
 * This file is part of ResourcePackTools.
 *
 * ResourcePackTools is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * ResourcePackTools is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with ResourcePackTools. If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace pjz9n\resourcepacktools\generator;

use pjz9n\resourcepacktools\manifest\Header;
use pjz9n\resourcepacktools\manifest\Manifest;
use pjz9n\resourcepacktools\manifest\Module;
use pjz9n\resourcepacktools\manifest\Version;
use pocketmine\plugin\PluginBase;
use Ramsey\Uuid\Uuid;

class SimpleResourcePack extends ResourcePackGenerator
{
    /**
     * @param PluginBase $plugin
     * @param Version $version
     */
    public function __construct(PluginBase $plugin, Version $version)
    {
        //TODO: Can header and module versions be different?
        $manifest = new Manifest(
            new Header(
                "Auto generated by ResourcePackTools.",
                $plugin->getName() . " ResourcePack.",
                Uuid::fromBytes(md5($plugin->getName() . "header", true))->toString(),
                $version
            ),
            [
                new Module(
                    "Auto generated by ResourcePackTools.",
                    Module::TYPE_RESOURCES,
                    Uuid::fromBytes(md5($plugin->getName() . "module", true))->toString(),
                    $version
                ),
            ]
        );
        parent::__construct($plugin, $manifest);
    }
}
