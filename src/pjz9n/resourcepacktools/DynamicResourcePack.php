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

namespace pjz9n\resourcepacktools;

use pocketmine\resourcepacks\ZippedResourcePack;
use pocketmine\Server;
use ReflectionClass;
use ReflectionException;

class DynamicResourcePack
{
    /** @var string */
    private $resourcePackPath;

    /**
     * @param string $resourcePackPath
     */
    public function __construct(string $resourcePackPath)
    {
        $this->resourcePackPath = $resourcePackPath;
    }

    /**
     * @return string
     */
    public function getResourcePackPath(): string
    {
        return $this->resourcePackPath;
    }

    /**
     * @throws ReflectionException
     */
    public function registerResourcePack(): void
    {
        $resourcePackManager = Server::getInstance()->getResourcePackManager();
        $newResourcePack = new ZippedResourcePack($this->resourcePackPath);
        $resourcePackManagerReflection = new ReflectionClass(get_class($resourcePackManager));
        //ResourcePackManager::$resourcePacks
        $resourcePacksProperty = $resourcePackManagerReflection->getProperty("resourcePacks");
        $resourcePacksProperty->setAccessible(true);
        $resourcePacksValue = $resourcePacksProperty->getValue($resourcePackManager);
        $resourcePacksValue[] = $newResourcePack;
        $resourcePacksProperty->setValue($resourcePackManager, $resourcePacksValue);
        //ResourcePackManager::$uuidList
        $uuidListProperty = $resourcePackManagerReflection->getProperty("uuidList");
        $uuidListProperty->setAccessible(true);
        $uuidListValue = $uuidListProperty->getValue($resourcePackManager);
        $uuidListValue[$newResourcePack->getPackId()] = $newResourcePack;
        $uuidListProperty->setValue($resourcePackManager, $uuidListValue);
    }
}
