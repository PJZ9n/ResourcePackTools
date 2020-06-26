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

namespace pjz9n\resourcepacktools\manifest;

use JsonSerializable;

class Manifest implements JsonSerializable
{
    /** @var int */
    public const FORMAT_VERSION = 1;

    /** @var Header */
    private $header;

    /** @var Module[] */
    private $modules;

    /**
     * @param Header $header
     * @param Module[] $modules
     */
    public function __construct(Header $header, array $modules)
    {
        $this->header = $header;
        $this->modules = $modules;
    }

    /**
     * @return Header
     */
    public function getHeader(): Header
    {
        return $this->header;
    }

    /**
     * @return Module[]
     */
    public function getModules(): array
    {
        return $this->modules;
    }

    /**
     * @param Module $module
     */
    public function addModule(Module $module): void
    {
        $this->modules[] = $module;
    }

    /**
     * @param Module $target
     */
    public function removeModule(Module $target): void
    {
        foreach ($this->modules as &$module) {
            if ($module === $target) {
                unset($module);
                //$this->modules = array_values($this->modules);//TODO
            }
        }
    }

    /**
     * @param int $id array index
     */
    public function removeModuleById(int $id): void
    {
        unset($this->modules[$id]);
    }

    public function jsonSerialize(): array
    {
        $manifest = [
            "format_version" => self::FORMAT_VERSION,
            "header" => [
                "description" => $this->header->getDescription(),
                "name" => $this->header->getName(),
                "uuid" => $this->header->getUuid(),
                "version" => $this->header->getVersion()->toArray(),
            ],
            "modules" => [],
        ];
        foreach ($this->modules as $module) {
            $manifest["modules"][] = [
                "description" => $module->getDescription(),
                "type" => $module->getType(),
                "uuid" => $module->getUuid(),
                "version" => $module->getVersion()->toArray(),
            ];
        }
        return $manifest;
    }
}
