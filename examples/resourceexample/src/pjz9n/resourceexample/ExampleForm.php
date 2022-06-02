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

use pocketmine\form\Form;
use pocketmine\player\Player;

class ExampleForm implements Form
{
    public function handleResponse(Player $player, $data): void
    {
        // TODO: Implement handleResponse() method.
    }

    public function jsonSerialize(): array
    {
        return [
            "type" => "form",
            "title" => "title",
            "content" => "please select!",
            "buttons" => [
                [
                    "text" => "Settings",
                    "image" => [
                        "type" => "path",
                        "data" => "example/form/icons/settings",
                    ],
                ],
            ],
        ];
    }
}
