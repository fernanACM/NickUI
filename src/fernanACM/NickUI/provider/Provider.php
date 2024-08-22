<?php

declare(strict_types=1);

namespace fernanACM\NickUI\provider;

use pocketmine\player\Player;

abstract class Provider{

    /**
     * @param Player $player
     * @return string|null
     */
    abstract public function getNickName(Player $player): ?string;

    /**
     * @param Player $player
     * @return string|null
     */
    abstract public function getOldName(Player $player): ?string;

    /**
     * @param Player $player
     * @param string $nickName
     * @return void
     */
    abstract public function setNickName(Player $player, string $nickName): void;

    /**
     * @param Player $player
     * @return array|null
     */
    abstract public function getAllNickNames(Player $player): ?array;

    /**
     * @param Player $player
     * @return boolean
     */
    abstract public function exists(Player $player): bool;

    /**
     * @param Player $player
     * @return void
     */
    abstract public function createAccount(Player $player): void;

    /**
     * @return void
     */
    abstract public function load(): void;

    /**
     * @return void
     */
    abstract public function unload(): void;
}