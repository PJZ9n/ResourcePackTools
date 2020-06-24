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

use pjz9n\resourcepacktools\manifest\Manifest;
use pocketmine\plugin\Plugin;
use ZipArchive;

class ResourcePackGenerator
{
    /** @var Plugin */
    private $plugin;

    /** @var Manifest */
    private $manifest;

    /** @var string|null PluginResourceRelativePath */
    private $packIcon;

    /** @var string[] ResourcePackPath => PluginResourceRelativePath */
    private $files;

    /**
     * @param Plugin $plugin
     * @param Manifest $manifest
     */
    public function __construct(Plugin $plugin, Manifest $manifest)
    {
        $this->plugin = $plugin;
        $this->manifest = $manifest;
    }

    /**
     * @return Manifest
     */
    public function getManifest(): Manifest
    {
        return $this->manifest;
    }

    /**
     * @return string|null
     */
    public function getPackIcon(): ?string
    {
        return $this->packIcon;
    }

    /**
     * @param string|null $resourcePath PluginResourceRelativePath
     */
    public function setPackIcon(?string $resourcePath): void
    {
        $this->packIcon = $resourcePath;
    }

    /**
     * Add the file.
     *
     * NOTE: This function just adds the file path.
     * Therefore, the stream of the file is linked at the generation timing.
     *
     * @param string $resourcePath PluginResourceRelativePath
     * @param string $packPath ResourcePackPath
     */
    public function addFile(string $resourcePath, string $packPath): void
    {
        $this->files[$packPath] = $resourcePath;
    }

    /**
     * Generate ResourcePack.
     *
     * @param string $generateFilePath ResourcePack generate filepath.
     * @param bool $overwrite Overwrite if resource pack already exists.
     */
    public function generate(string $generateFilePath, bool $overwrite = true): void
    {
        $zip = new ZipArchive();
        if ($overwrite || !file_exists($generateFilePath)) {
            $flags = ZipArchive::CREATE;
            if ($overwrite) $flags |= ZipArchive::OVERWRITE;
            if ($zip->open($generateFilePath, $flags) !== true) {
                throw new GenerateException("Failed to open zip.");
            }
        } else {
            throw new GenerateException("File already exists: " . $generateFilePath);
        }
        //add manifest.json
        $this->addFileFromString($zip, "manifest.json", json_encode($this->manifest, JSON_PRETTY_PRINT));
        //add pack_icon.png
        if ($this->packIcon !== null) {
            $this->addFileFromPluginResource($zip, $this->packIcon, "pack_icon.png");
        }
        //add files
        foreach ($this->files as $resourcePackPath => $pluginResourcePath) {
            $this->addFileFromPluginResource($zip, $pluginResourcePath, $resourcePackPath);
        }
        if (!$zip->close()) throw new GenerateException("Failed to close the zip.");
    }

    /**
     * @return Plugin
     */
    protected function getPlugin(): Plugin
    {
        return $this->plugin;
    }

    /**
     * Add file to zip from string.
     *
     * @param ZipArchive $zip
     * @param string $resourcePackPath
     * @param string $contents
     */
    private function addFileFromString(ZipArchive $zip, string $resourcePackPath, string $contents): void
    {
        if (!$zip->addFromString($resourcePackPath, $contents)) {
            throw new GenerateException("Failed to file addition to zip.");
        }
    }

    /**
     * Add file to zip from PluginResource.
     *
     * @param ZipArchive $zip
     * @param string $pluginResourcePath
     * @param string $resourcePackPath
     */
    private function addFileFromPluginResource(ZipArchive $zip, string $pluginResourcePath, string $resourcePackPath): void
    {
        if (($resource = $this->plugin->getResource($pluginResourcePath)) === null) {
            throw new GenerateException("Failed to load resource: " . $pluginResourcePath);
        }
        $contents = stream_get_contents($resource);
        if ($contents === false) throw new GenerateException("Failed to get the content from the stream.");
        //TODO: Do not overwrite automatically.
        $this->addFileFromString($zip, $resourcePackPath, $contents);
        fclose($resource);
    }
}
