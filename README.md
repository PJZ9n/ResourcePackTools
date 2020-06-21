# ResourcePackTools

[![release](https://img.shields.io/github/release/PJZ9n/ResourcePackTools.svg)](https://github.com/PJZ9n/ResourcePackTools/releases)
[![license](https://img.shields.io/badge/License-GPL--v3-green)](https://github.com/PJZ9n/ResourcePackTools/blob/master/LICENSE)

|Virion|Plugin|
|---|---|
|[![](https://poggit.pmmp.io/ci.shield/PJZ9n/ResourcePackTools/ResourcePackTools)](https://poggit.pmmp.io/ci/PJZ9n/ResourcePackTools/ResourcePackTools)|[![](https://poggit.pmmp.io/ci.shield/PJZ9n/ResourcePackTools/ResourcePackToolsPlugin)](https://poggit.pmmp.io/ci/PJZ9n/ResourcePackTools/ResourcePackToolsPlugin)|

## Overview
Apply resource packs easily from plugins!

## How to use
- Inject into your plugin using Virion.
- Put plugins in the plugins folder.

## Examples
- DynamicResourcePack
```php
use pjz9n\resourcepacktools\DynamicResourcePack;
use pocketmine\plugin\Plugin;

/** @var Plugin $this */

$pack = new DynamicResourcePack($this->getDataFolder() . "pack.zip");
//Register the resource pack.
$pack->registerResourcePack();
```

- FileResourcePack
```php
use pjz9n\resourcepacktools\FileResourcePack;
use pjz9n\resourcepacktools\ResourcePackVersion;
use pocketmine\plugin\Plugin;

//PluginName: HogePlugin

/** @var Plugin $this */

$version = new ResourcePackVersion(0, 0, 1);
$pack = new FileResourcePack($this->getDataFolder() . "pack.zip", $this, $version);
//Add the files.
$pack->addFile("test.png");// resources/test.png, pack-path: hogeplugin/test.png
$pack->addFile("foo/image1.png");// resources/foo/image.png, pack-path: hogeplugin/foo/image1.png
$pack->addFile("foo/image2.png", "image2.png");// resources/foo/image.png, pack-path: hogeplugin/image2.png
//Set the pack icon.
$pack->setIcon("bar/icon.png");// resources/bar/icon.png, pack-path: pack_icon.png
//Register the resource pack.
$pack->registerResourcePack();

//Example: form-button image...
$formData = [
    "buttons" => [
        [
            "text" => "Button 1!",
            "image" => [
                "type" => "path",
                "data" => "hogeplugin/test",
            ],
        ],
        [
            "text" => "Button 2!",
            "image" => [
                "type" => "path",
                "data" => "hogeplugin/foo/image1",
            ],
        ],
    ],
];
```
