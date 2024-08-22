<?php

declare(strict_types=1);

namespace fernanACM\NickUI\provider;

use pocketmine\player\Player;

use pocketmine\utils\SingletonTrait;

use fernanACM\NickUI\NickUI;
use fernanACM\NickUI\const\StorageConst;
use fernanACM\NickUI\provider\type\DatabaseProvider;
use fernanACM\NickUI\provider\type\YamlProvider;

final class ProviderManager{
    use SingletonTrait{
        setInstance as protected;
        reset as protected;
    }

    /** @var Provider|null $provider */
    protected ?Provider $provider = null;

    public function __construct(){
        self::setInstance($this);
    }

    /**
     * @return void
     */
    public function init(): void{
        $this->loadProvider();
        $this->provider->load();
    }

    /**
     * @return void
     */
    public function ending(): void{
        $this->provider->unload();
    } 

    /**
     * @return void
     */
    protected function loadProvider(): void{
        $config = NickUI::getInstance()->config;
        switch(strtolower(strval($config->getNested("Storage.provider")))){
            case StorageConst::YML:
            case StorageConst::YAML:
                $this->provider = new YamlProvider(NickUI::getInstance());
            break;

            case StorageConst::SQLITE:
            case StorageConst::MYSQL:
                $this->provider = new DatabaseProvider(NickUI::getInstance());
            break;

            default:
                $this->provider = new YamlProvider(NickUI::getInstance());
            break;
        }
    }

    /**
     * @param Player $player
     * @return boolean
     */
    public function exists(Player $player): bool{
        return $this->provider->exists($player);
    }

    /**
     * @param Player $player
     * @return void
     */
    public function createAccount(Player $player): void{
        $this->provider->createAccount($player);
    }

    /**
     * @param Player $player
     * @return string
     */
    public function getNickName(Player $player): string{
        return $this->provider->getNickName($player) ?? "";
    }

    /**
     * @param Player $player
     * @return string
     */
    public function getOldName(Player $player): string{
        return $this->provider->getOldName($player) ?? "";
    }

    /**
     * @param Player $player
     * @return array
     */
    public function getAllNickNames(Player $player): array{
        return $this->provider->getAllNickNames($player) ?? [];
    }

    /**
     * @param Player $player
     * @param string $nickName
     * @return void
     */
    public function setNickName(Player $player, string $nickName): void{
        $this->provider->setNickName($player, $nickName);
    }

    /**
     * @return Provider
     */
    public function getProvider(): Provider{
        return $this->provider;
    }
}