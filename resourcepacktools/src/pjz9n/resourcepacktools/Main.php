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

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\lang\BaseLang;
use pocketmine\plugin\PluginBase;
use pocketmine\resourcepacks\ResourcePackException;
use pocketmine\utils\TextFormat;
use pocketmine\resourcepacks\ResourcePack as PMResourcePack;

class Main extends PluginBase
{
    /** @var BaseLang */
    private $lang;

    public function onEnable(): void
    {
        $this->saveDefaultConfig();
        $configLang = (string)$this->getConfig()->get("lang", "def");
        $lang = $configLang === "def" ? $this->getServer()->getLanguage()->getLang() : $configLang;
        $localePath = $this->getFile() . "resources/locale/";
        $this->lang = new BaseLang($lang, $localePath, "eng");
        $this->getLogger()->info($this->lang->translateString("language.selected", [$this->lang->getName()]));
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        switch ($command) {//$command->__toString()
            case "resourcepack":
                if (!isset($args[0])) return false;
                $type = $args[0];
                switch ($type) {
                    case "register":
                    case "r":
                        if (!isset($args[1])) return false;
                        $fileName = $args[1];
                        try {
                            ResourcePack::register($fileName);
                        } catch (ResourcePackException $resourcePackException) {
                            $sender->sendMessage(TextFormat::RED . $resourcePackException->getMessage());//TODO: message
                            return true;
                        }
                        $sender->sendMessage(
                            TextFormat::GREEN . $this->lang->translateString("resourcepack.register.success", [$fileName])
                        );
                        return true;
                    case "unregisterbyindex":
                    case "urbi":
                        if (!isset($args[1])) return false;
                        $index = $args[1];
                        if (!is_numeric($index)) {//TODO: more validation
                            $sender->sendMessage(
                                TextFormat::RED . $this->lang->translateString("command.validate.onlynumeric")
                            );
                            return true;
                        }
                        $index = (int)$index;
                        $pack = ResourcePack::getPackByIndex($index);
                        if (!($pack instanceof PMResourcePack)) {
                            $sender->sendMessage(
                                TextFormat::RED . $this->lang->translateString("resourcepack.notfound")
                            );
                            return true;
                        }
                        ResourcePack::unRegister($pack);
                        $sender->sendMessage(
                            TextFormat::GREEN . $this->lang->translateString("resourcepack.unregister.success", ["index={$index}"])
                        );
                        return true;
                    case "unregisterbyuuid":
                    case "urbu":
                        if (!isset($args[1])) return false;
                        $uuid = $args[1];
                        $pack = ResourcePack::getPackByUuid($uuid);
                        if (!($pack instanceof PMResourcePack)) {
                            $sender->sendMessage(
                                TextFormat::RED . $this->lang->translateString("resourcepack.notfound")
                            );
                            return true;
                        }
                        ResourcePack::unRegister($pack);
                        $sender->sendMessage(
                            TextFormat::GREEN . $this->lang->translateString("resourcepack.unregister.success", ["uuid={$uuid}"])
                        );
                        return true;
                    case "list":
                    case "l":
                        if (!isset($args[1])) return false;
                        $mode = $args[1];
                        switch ($mode) {
                            case "pack":
                            case "p":
                                //pack mode
                                $list = [];
                                foreach (ResourcePack::getPackList() as $index => $pack) {
                                    $list[] = (string)$index . " => " . $pack->getPath();
                                }
                                $sender->sendMessage("index => path");
                                if (count($list) < 1) {
                                    $sender->sendMessage(
                                        TextFormat::RED . $this->lang->translateString("resourcepack.notfound")
                                    );
                                } else {
                                    $sender->sendMessage(implode("\n", $list));
                                }
                                return true;
                            case "uuid":
                            case "u":
                                //uuid mode
                                $list = [];
                                foreach (ResourcePack::getUuidList() as $uuid => $pack) {
                                    $list[] = (string)$uuid . " => " . $pack->getPath();
                                }
                                $sender->sendMessage("uuid => path");
                                if (count($list) < 1) {
                                    $sender->sendMessage(
                                        TextFormat::RED . $this->lang->translateString("resourcepack.notfound")
                                    );
                                } else {
                                    $sender->sendMessage(implode("\n", $list));
                                }
                                return true;
                        }
                        return false;
                }
                return false;
        }
        return false;
    }
}
