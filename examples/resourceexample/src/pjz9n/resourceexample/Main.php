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

namespace pjz9n\resourceexample;

use pjz9n\resourcepacktools\generator\SimpleResourcePack;
use pjz9n\resourcepacktools\manifest\Version;
use pjz9n\resourcepacktools\ResourcePack;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class Main extends PluginBase
{
    public function onEnable(): void
    {
        $resourcePackPath = $this->getDataFolder() . "resourcepack.zip";
        //Generate the ResourcePack.
        $pack = new SimpleResourcePack($this, new Version(1, 0, 0));
        $pack->addFile("formicons/settings.png", "example/form/icons/settings.png");
        $pack->setPackIcon("packicon.png");//Optional
        $pack->generate($resourcePackPath);
        //Register the ResourcePack.
        ResourcePack::register($resourcePackPath);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        switch ($command->getName()) {
            case "resourceexample":
                if (!($sender instanceof Player)) {
                    $sender->sendMessage(TextFormat::RED . "Please execute by player.");
                    return true;
                }
                $sender->sendForm(new ExampleForm());
                return true;
        }
        return false;
    }
}
