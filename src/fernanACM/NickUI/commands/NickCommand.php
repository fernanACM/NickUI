<?php
    
#      _       ____   __  __ 
#     / \     / ___| |  \/  |
#    / _ \   | |     | |\/| |
#   / ___ \  | |___  | |  | |
#  /_/   \_\  \____| |_|  |_|
# The creator of this plugin was fernanACM.
# https://github.com/fernanACM
    
namespace fernanACM\NickUI\commands;

use pocketmine\Server;
use pocketmine\player\Player;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use CortexPE\Commando\BaseCommand;

use fernanACM\NickUI\commands\subcommands\SetSubCommand;
use fernanACM\NickUI\commands\subcommands\RandomSubCommand;
use fernanACM\NickUI\commands\subcommands\ResetSubCommand;
use fernanACM\NickUI\commands\subcommands\HelpSubCommand;

use fernanACM\NickUI\Loader;
use fernanACM\NickUI\Utils\PluginUtils;

class NickCommand extends BaseCommand{
    
    protected function prepare(): void{
        $this->setPermission("nickui.acm");
        $this->registerSubCommand(new SetSubCommand("set", "Change your nick with one command."));
        $this->registerSubCommand(new RandomSubCommand("random", "Change your name to a random nick with one command."));
        $this->registerSubCommand(new ResetSubCommand("reset", "Return your nick to normal with one command."));
        $this->registerSubCommand(new HelpSubCommand("help", "Plugin command list."));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void{
        if(!$sender instanceof Player){
              $sender->sendMessage("Use this command in-game");
              return;
        }
        if($sender->hasPermission("nickui.acm")){
         	   Loader::getInstance()->menu->NickMenu($sender);
               PluginUtils::PlaySound($sender, "random.pop2", 1, 4.5);
        }else{
            $sender->sendMessage(Loader::getInstance()->getMessage($sender, "Messages.no-permission"));
            PluginUtils::PlaySound($sender, "mob.villager.no");
        }
    }
}
