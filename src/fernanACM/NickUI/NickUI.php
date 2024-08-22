<?php

declare(strict_types=1);

namespace fernanACM\NickUI;

use pocketmine\plugin\PluginBase;

use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\utils\SingletonTrait;

use fernanACM\NickUI\manager\NickManager;

use fernanACM\NickUI\provider\ProviderManager;

class NickUI extends PluginBase{
    use SingletonTrait{
        setInstance as protected;
        reset as protected;
    }

    /** @var Config $config */
    public Config $config;

    /**
     * @return void
     */
    public function onLoad(): void{
        self::setInstance($this);
        $this->loadFiles();
    }

    /**
     * @return void
     */
    public function onEnable(): void{
        $this->getProviderManager()->init();
    }

    /**
     * @return void
     */
    public function onDisable(): void{
        $this->getProviderManager()->ending();
    }

    /**
     * @return void
     */
    protected function loadFiles(): void{
        $this->saveResource("config.yml");
        $this->config = new Config($this->getDataFolder(). "config.yml");
    }

    /**
     * @return ProviderManager
     */
    public function getProviderManager(): ProviderManager{
        return ProviderManager::getInstance();
    }

    /**
     * @return NickManager
     */
    public function getNickManager(): NickManager{
        return NickManager::getInstance();
    }

    /**
     * @return string
     */
    public static function getPrefix(): string{
        return TextFormat::colorize(self::$instance->config->get("Prefix"));
    }
}