<?php

namespace fernanACM\NickUI\commands;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginOwned;

use fernanACM\NickUI\Loader;
use fernanACM\NickUI\Utils\PluginUtils;

class NickCommand extends Command implements PluginOwned{
    
    private $plugin;

    public function __construct(Loader $plugin){
        $this->plugin = $plugin;
        
        parent::__construct("nick", "§r§fOpen NickUI by §bfernanACM", "§cUse: /nick", ["nickui"]);
        $this->setPermission("nick.acm");
        $this->setAliases(["nickui"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        if(count($args) == 0){
            if($sender instanceof Player) {
                $this->plugin->NickForm($sender);
                PluginUtils::PlaySound($sender, "random.burp", 1, 3);
            } else {
                $sender->sendMessage("Use this command in-game");
            }
        }
        return true;
    }
    
    public function getPlugin(): Plugin{
        return $this->plugin;
    }

    public function getOwningPlugin(): Loader{
        return $this->plugin;
    }
}
