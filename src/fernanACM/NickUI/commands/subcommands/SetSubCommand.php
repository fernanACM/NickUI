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
use CortexPE\Commando\args\TextArgument;

use fernanACM\NickUI\Loader;
use fernanACM\NickUI\Utils\PluginUtils;

class SetSubCommand extends BaseSubCommand{
    
	protected function prepare(): void{
		$this->setPermission("nickui.acm");
        $this->registerArgument(0, new TextArgument("nickname"));
	}

	public function onRun(CommandSender $sender, string $aliasUsed, array $args): void{
        if(!$sender instanceof Player){
            $sender->sendMessage("Use this command in-game");
            return;
        }
        if(!$sender->hasPermission("nickui.acm")){
            $prefix = Loader::getInstance()->getMessage($sender, "Prefix");
            $sender->sendMessage($prefix . Loader::getInstance()->getMessage($sender, "Messages.no-permissions"));
            PluginUtils::PlaySound($sender, "mob.villager.no", 1, 1);
            return;
        }
        if(!isset($args["nickname"])){
            $prefix = Loader::getInstance()->getMessage($sender, "Prefix");
            $sender->sendMessage($prefix . Loader::getInstance()->getMessage($sender, "Messages.error-command"));
            PluginUtils::PlaySound($sender, "mob.villager.no", 1, 1);
            return;
        }
        if(strlen($args["nickname"]) > Loader::getInstance()->config->getNested("Settings.characters", 10)){
            $prefix = Loader::getInstance()->getMessage($sender, "Prefix");
            $sender->sendMessage($prefix . Loader::getInstance()->getMessage($sender, "Messages.error-characters"));
            PluginUtils::PlaySound($sender, "mob.villager.no", 1, 1);
            return;
        }
        
        if(in_array($args["nickname"], Loader::getInstance()->config->getNested("Settings.not-allow-custom-nicks"))){
            $prefix = Loader::getInstance()->getMessage($sender, "Prefix");
            $sender->sendMessage($prefix . Loader::getInstance()->getMessage($sender, "Messages.Nick.not-allowed-nick"));
            PluginUtils::PlaySound($sender, "mob.villager.no", 1, 1);
            return;
        }
        $prefix = Loader::getInstance()->getMessage($sender, "Prefix");
        $sender->setDisplayName($args["nickname"]);
        $sender->setNameTag($args["nickname"]);
        Loader::getInstance()->nick->setNested($sender->getName() . ".custom-name", $args["nickname"]);
        Loader::getInstance()->nick->setNested($sender->getName() . ".normal-name", $sender->getName());
        Loader::getInstance()->nick->save();
        $sender->sendMessage($prefix . str_replace(["{NICK}"], [$args["nickname"]], Loader::getInstance()->getMessage($sender, "Messages.Nick.nick-success")));
        PluginUtils::PlaySound($sender, "random.levelup", 1, 2.6);
    }
}
