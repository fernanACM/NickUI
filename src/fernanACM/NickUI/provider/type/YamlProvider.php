<?php

declare(strict_types=1);

namespace fernanACM\NickUI\provider\type;

use pocketmine\player\Player;

use pocketmine\utils\Config;

use fernanACM\NickUI\NickUI;
use fernanACM\NickUI\const\DataConst;
use fernanACM\NickUI\provider\Provider;

final class YamlProvider extends Provider{

    /** @var NickUI $plugin */
    protected NickUI $plugin;
    /** @var Config $data */
    protected Config $data;

    /**
     * @param NickUI $plugin
     * @return void
     */
    public function __construct(NickUI $plugin){
        $this->plugin = $plugin;
    }

    /**
     * @return void
     */
    public function load(): void{
        $this->data = new Config($this->plugin->getDataFolder(). "nicks.yml");
    }

    /**
     * @return void
     */
    public function unload(): void{
        // NOTHING ...
    }

    /**
     * @param Player $player
     * @return boolean
     */
    public function exists(Player $player): bool{
        return $this->data->exists($player->getDisplayName());
    }

    /**
     * @param Player $player
     * @return void
     */
    public function createAccount(Player $player): void{
        if($this->exists($player)) return;
        $this->data->setNested($player->getDisplayName(), [
            DataConst::DISPLAY_NAME => $player->getDisplayName(),
            DataConst::NICKNAME => null,
            DataConst::NICKNAMES => []
        ]);
        $this->data->save();
    }
    
    /**
     * @param Player $player
     * @return string|null
     */
    public function getNickName(Player $player): ?string{
        return $this->exists($player) ? strval($this->data->getNested($player->getDisplayName().".".DataConst::NICKNAME)) : null;
    }

    /**
     * @param Player $player
     * @return string|null
     */
    public function getOldName(Player $player): ?string{
        return $this->exists($player) ? strval($this->data->getNested($player->getDisplayName().".".DataConst::DISPLAY_NAME)) : null;
    }

    /**
     * @param Player $player
     * @return array|null
     */
    public function getAllNickNames(Player $player): ?array{
        return $this->exists($player) ? (array)$this->data->getNested($player->getDisplayName().".".DataConst::NICKNAMES) : null;
    }

    /**
     * @param Player $player
     * @param string $nickName
     * @return void
     */
    public function setNickName(Player $player, string $nickName): void{
        $this->createAccount($player);
        $nicks = $this->getAllNickNames($player) ?? [];
        if(!in_array($nickName, $nicks)){
            $nicks[] = $nickName;
        }
        $this->data->setNested($player->getDisplayName(), [
            DataConst::NICKNAME => $nickName,
            DataConst::NICKNAMES => $nicks
        ]);
        $this->data->save();
    }

    /**
     * @return Config
     */
    public function getConfig(): Config{
        return $this->data;
    }
}