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

use LogicException;
use pocketmine\resourcepacks\ZippedResourcePack;
use pocketmine\Server;
use pocketmine\resourcepacks\ResourcePack as PMResourcePack;
use ReflectionClass;
use ReflectionException;

abstract class ResourcePack
{
    //TODO: optimize this functions...
    /**
     * @param string $resourcePackPath
     */
    public static function register(string $resourcePackPath): void
    {
        $resourcePackManager = Server::getInstance()->getResourcePackManager();
        $newResourcePack = new ZippedResourcePack($resourcePackPath);
        try {
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
            $uuidListValue[strtolower($newResourcePack->getPackId())] = $newResourcePack;
            $uuidListProperty->setValue($resourcePackManager, $uuidListValue);
        } catch (ReflectionException $reflectionException) {
            throw new LogicException("Caught ReflectionException.");
        }
    }

    /**
     * @param PMResourcePack $resourcePack
     */
    public static function unRegister(PMResourcePack $resourcePack): void
    {
        $resourcePackManager = Server::getInstance()->getResourcePackManager();
        try {
            $resourcePackManagerReflection = new ReflectionClass(get_class($resourcePackManager));
            //ResourcePackManager::$resourcePacks
            $resourcePacksProperty = $resourcePackManagerReflection->getProperty("resourcePacks");
            $resourcePacksProperty->setAccessible(true);
            $resourcePacksValue = $resourcePacksProperty->getValue($resourcePackManager);
            $unregisterKeys = [];
            foreach ($resourcePacksValue as $key => $item) {
                if ($item === $resourcePack) {
                    $unregisterKeys[] = $key;
                }
            }
            foreach ($unregisterKeys as $unregisterKey) {
                unset($resourcePacksValue[$unregisterKey]);
            }
            $resourcePacksProperty->setValue($resourcePackManager, $resourcePacksValue);
            //ResourcePackManager::$uuidList
            $uuidListProperty = $resourcePackManagerReflection->getProperty("uuidList");
            $uuidListProperty->setAccessible(true);
            $uuidListValue = $uuidListProperty->getValue($resourcePackManager);
            $unregisterKeys = [];
            foreach ($uuidListValue as $key => $item) {
                if ($item === $resourcePack) {
                    $unregisterKeys[] = $key;
                }
            }
            foreach ($unregisterKeys as $unregisterKey) {
                unset($uuidListValue[$unregisterKey]);
            }
            $uuidListProperty->setValue($resourcePackManager, $uuidListValue);
        } catch (ReflectionException $reflectionException) {
            throw new LogicException("Caught ReflectionException.");
        }
    }

    /**
     * @return PMResourcePack[] index => PMResourcePack
     */
    public static function getPackList(): array
    {
        $resourcePackManager = Server::getInstance()->getResourcePackManager();
        try {
            $resourcePackManagerReflection = new ReflectionClass(get_class($resourcePackManager));
            //ResourcePackManager::$resourcePacks
            $resourcePacksProperty = $resourcePackManagerReflection->getProperty("resourcePacks");
            $resourcePacksProperty->setAccessible(true);
            $resourcePacksValue = $resourcePacksProperty->getValue($resourcePackManager);
            return $resourcePacksValue;
        } catch (ReflectionException $reflectionException) {
            throw new LogicException("Caught ReflectionException.");
        }
    }

    /**
     * @return PMResourcePack[] uuid => PMResourcePack
     */
    public static function getUuidList(): array
    {
        $resourcePackManager = Server::getInstance()->getResourcePackManager();
        try {
            $resourcePackManagerReflection = new ReflectionClass(get_class($resourcePackManager));
            //ResourcePackManager::$uuidList
            $uuidListProperty = $resourcePackManagerReflection->getProperty("uuidList");
            $uuidListProperty->setAccessible(true);
            $uuidListValue = $uuidListProperty->getValue($resourcePackManager);
            return $uuidListValue;
        } catch (ReflectionException $reflectionException) {
            throw new LogicException("Caught ReflectionException.");
        }
    }

    /**
     * @param int $index
     *
     * @return PMResourcePack|null
     */
    public static function getPackByIndex(int $index): ?PMResourcePack
    {
        return self::getPackList()[$index] ?? null;
    }

    /**
     * @param string $uuid
     *
     * @return PMResourcePack|null
     */
    public static function getPackByUuid(string $uuid): ?PMResourcePack
    {
        return self::getUuidList()[$uuid] ?? null;
    }
}
