# ResourcePackTools

[![release](https://img.shields.io/github/release/PJZ9n/ResourcePackTools.svg)](https://github.com/PJZ9n/ResourcePackTools/releases)
[![license](https://img.shields.io/badge/License-GPL--v3-green)](https://github.com/PJZ9n/ResourcePackTools/blob/master/LICENSE)

|Virion Library|API Plugin|
|---|---|
|[![](https://poggit.pmmp.io/ci.shield/PJZ9n/ResourcePackTools/ResourcePackTools)](https://poggit.pmmp.io/ci/PJZ9n/ResourcePackTools/ResourcePackTools)|[![](https://poggit.pmmp.io/ci.shield/PJZ9n/ResourcePackTools/ResourcePackToolsPlugin)](https://poggit.pmmp.io/ci/PJZ9n/ResourcePackTools/ResourcePackToolsPlugin)|

- [日本語](#日本語)
- [English](#English)

## 日本語

## 概要
プラグインから簡単にリソースパックを扱うためのツール(ライブラリ)

## 使用方法
- Virionライブラリとして使用する
- APIプラグインとして使用する

## コード例

### リソースパックの生成
```php
$pack = new SimpleResourcePack($this, new Version(1, 0, 0));
$pack->setPackIcon("info.png");
$pack->addFile("test/server.png", "server.png");
$pack->generate($this->getDataFolder() . "test1.zip");
```

### リソースパックの登録
```php
ResourcePack::register($this->getDataFolder() . "test1.zip");
```

## English

## Overview
Tool (library) for easy use of resource packs from plugins

## How to use
- Use as Virion library
- Use as API Plugin

## Code examples

### Generate ResourcePack
```php
$pack = new SimpleResourcePack($this, new Version(1, 0, 0));
$pack->setPackIcon("info.png");
$pack->addFile("test/server.png", "server.png");
$pack->generate($this->getDataFolder() . "test1.zip");
```

### Register ResourcePack
```php
ResourcePack::register($this->getDataFolder() . "test1.zip");
```
