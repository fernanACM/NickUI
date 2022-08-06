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

class HelpSubCommand extends BaseSubCommand{
    
    protected function prepare(): void{
        $this->setPermission("nickui.acm");
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void{
        if(!$sender instanceof Player){
              $sender->sendMessage("Use this command in-game");
              return;
        }
        if($sender->hasPermission("nickui.acm")){
         	   $sender->sendMessage("§l§e » NickUI - Command list «");
         	   $sender->sendMessage("§7 - /nickui - Open Menu");
         	   $sender->sendMessage("§7 - /nickui set <nick> - Create a nickname with commands");
         	   $sender->sendMessage("§7 - /nickui reset - Reset your name to normal");
         	   $sender->sendMessage("§7 - /nickui random - Get a random name");
         	   $sender->sendMessage("§7 - /nickui help - Command list");
               $sender->sendMessage("§l§e » NickUI - Permissions «");
               $sender->sendMessage("§7 - nickui.acm - Access all other commands except nick random");
               $sender->sendMessage("§7 - nickui.random - Being able to use random nick");
               PluginUtils::PlaySound($sender, "random.pop2");
        }else{
            $sender->sendMessage(Loader::getInstance()->getMessage($sender, "Messages.no-permission"));
            PluginUtils::PlaySound($sender, "mob.villager.no");
        }
    }
}
