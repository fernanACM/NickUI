<?php

declare(strict_types=1);

namespace fernanACM\NickUI\manager;

use pocketmine\player\Player;

use pocketmine\utils\SingletonTrait;

use fernanACM\NickUI\provider\ProviderManager as Provider;

final class NickManager{
    use SingletonTrait{
        setInstance as protected;
        reset as protected;
    }

    public function __construct(){
        self::setInstance($this);
    }

    /**
     * You apply a NickName to the player
     * 
     * @param Player $player
     * @param string $nickName
     * @return void
     */
    public function set(Player $player, string $nickName): void{
        if(!$this->filter($nickName)){
            // ERROR... :C
            return;
        }
    }

    /**
     * Gets the NickName of the player
     * 
     * @param Player $player
     * @return string
     */
    public function get(Player $player): string{
        return Provider::getInstance()->getNickName($player);
    }

    /**
     * You get the player's old name
     * 
     * @param Player $player
     * @return string
     */
    public function oldName(Player $player): string{
        return Provider::getInstance()->getOldName($player);
    }

    /**
     * Get all the player's NickNames
     * 
     * @param Player $player
     * @return array
     */
    public function nickNames(Player $player): array{
        return Provider::getInstance()->getAllNickNames($player);
    }

    /**
     * @param string $nickName
     * @return boolean
     */
    public function filter(string $nickName): bool{
        // COMING SOON...
        return true;
    }
}