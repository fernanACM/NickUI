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

class ResetSubCommand extends BaseSubCommand{
    
    protected function prepare(): void{
        $this->setPermission("nickui.acm");
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void{
        if(!$sender instanceof Player){
              $sender->sendMessage("Use this command in-game");
              return;
        }
        if($sender->hasPermission("nickui.acm")){
         	if(Loader::getInstance()->nick->exists($sender->getName())){
               $sender->setNameTag(Loader::getInstance()->nick->getNested($sender->getName() . ".normal-name"));
			   $sender->setDisplayName(Loader::getInstance()->nick->getNested($sender->getName() . ".normal-name"));
			   Loader::getInstance()->nick->remove($sender->getName());
               $prefix = Loader::getInstance()->getMessage($sender, "Prefix");
               $sender->sendMessage($prefix . Loader::getInstance()->getMessage($sender, "Messages.Nick.nick-reset"));
               PluginUtils::PlaySound($sender, "random.drink", 1, 1.6);
            }else{
                $prefix = Loader::getInstance()->getMessage($sender, "Prefix");
                $sender->sendMessage($prefix . Loader::getInstance()->getMessage($sender, "Messages.Nick.reset-failed"));
                PluginUtils::PlaySound($sender, "mob.villager.no");
            }
        }else{
            $sender->sendMessage(Loader::getInstance()->getMessage($sender, "Messages.no-permission"));
            PluginUtils::PlaySound($sender, "mob.villager.no");
        }
    }
}
