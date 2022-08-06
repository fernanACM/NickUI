<?php
    
#      _       ____   __  __ 
#     / \     / ___| |  \/  |
#    / _ \   | |     | |\/| |
#   / ___ \  | |___  | |  | |
#  /_/   \_\  \____| |_|  |_|
# The creator of this plugin was fernanACM.
# https://github.com/fernanACM
    
namespace fernanACM\NickUI\forms\subforms;

use pocketmine\Server;
use pocketmine\player\Player;

use Vecnavium\FormsUI\CustomForm;

use fernanACM\NickUI\Loader;
use fernanACM\NickUi\utils\PluginUtils;

class NickForm{
            
    public function Nick(Player $player){
        $form = new CustomForm(function(Player $player, $data){
            if($data !== null){
                if(strlen($data[0]) < Loader::getInstance()->config->getNested("Settings.characters", 10)){
                    if(!in_array($data[0], Loader::getInstance()->config->getNested("Settings.not-allow-custom-nicks"))){
                        Loader::getInstance()->nick->setNested($player->getName() . ".custom-name", $data[0]);
                        Loader::getInstance()->nick->setNested($player->getName() . ".normal-name", $player->getName());
                        Loader::getInstance()->nick->save();
                        $player->setDisplayName($data[0]);
			            $player->setNameTag($data[0]);
                        $prefix = Loader::getInstance()->getMessage($player, "Prefix");
                        $player->sendMessage($prefix . str_replace(["{NICK}"], [$data[0]], Loader::getInstance()->getMessage($player, "Messages.Nick.nick-success")));
                        PluginUtils::PlaySound($player, "liquid.lavapop", 1, 4);
                    }else{
                        $prefix = Loader::getInstance()->getMessage($player, "Prefix");
                        $player->sendMessage($prefix . Loader::getInstance()->getMessage($player, "Messages.Nick.not-allowed-nick"));
                        PluginUtils::PlaySound($player, "mob.villager.no", 1, 1);
                    }   
                }else{
                    $prefix = Loader::getInstance()->getMessage($player, "Prefix");
                    $player->sendMessage($prefix . Loader::getInstance()->getMessage($player, "Messages.error-characters"));
                    PluginUtils::PlaySound($player, "mob.villager.no", 1, 1);
                }
            }
        });
        $form->setTitle(Loader::getInstance()->getMessage($player, "Nick-menu.title"));
        $form->addInput(Loader::getInstance()->getMessage($player, "Nick-menu.content"), Loader::getInstance()->getMessage($player, "Nick-menu.input"));
        $player->sendForm($form);
    }
    
    public function RandomNick(Player $player){
        $random = mt_rand(0, count(Loader::getInstance()->config->getNested("Settings.random-nicks")) -1 );
        Loader::getInstance()->nick->setNested($player->getName() . ".custom-name", Loader::getInstance()->config->getNested("Settings.random-nicks")[$random]);
        Loader::getInstance()->nick->setNested($player->getName() . ".normal-name", $player->getName());
        $player->setDisplayName(Loader::getInstance()->config->getNested("Settings.random-nicks")[$random]);
        $player->setNameTag(Loader::getInstance()->config->getNested("Settings.random-nicks")[$random]);
        $prefix = Loader::getInstance()->getMessage($player, "Prefix");
        $player->sendMessage($prefix . str_replace(["{NICK}"], [Loader::getInstance()->config->getNested("Settings.random-nicks")[$random]], Loader::getInstance()->getMessage($player, "Messages.Nick.nick-success")));
    }
}
