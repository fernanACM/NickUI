<?php
    
#      _       ____   __  __ 
#     / \     / ___| |  \/  |
#    / _ \   | |     | |\/| |
#   / ___ \  | |___  | |  | |
#  /_/   \_\  \____| |_|  |_|
# The creator of this plugin was fernanACM.
# https://github.com/fernanACM
    
namespace fernanACM\NickUI\commands\subcommands;

use pocketmine\Server;
use pocketmine\player\Player;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use CortexPE\Commando\BaseSubCommand;

use fernanACM\NickUI\Loader;
use fernanACM\NickUI\Utils\PluginUtils;

class RandomSubCommand extends BaseSubCommand{
    
    protected function prepare(): void{
        $this->setPermission("nickui.random");
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void{
        if(!$sender instanceof Player){
              $sender->sendMessage("Use this command in-game");
              return;
        }
        if($sender->hasPermission("nickui.random")){
         	   Loader::getInstance()->setNick->RandomNick($sender);
               PluginUtils::PlaySound($sender, "random.pop2", 1, 4.5);
        }else{
            $sender->sendMessage(Loader::getInstance()->getMessage($sender, "Messages.no-permission"));
            PluginUtils::PlaySound($sender, "mob.villager.no");
        }
    }
}
