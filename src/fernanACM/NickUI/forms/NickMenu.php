<?php
    
#      _       ____   __  __ 
#     / \     / ___| |  \/  |
#    / _ \   | |     | |\/| |
#   / ___ \  | |___  | |  | |
#  /_/   \_\  \____| |_|  |_|
# The creator of this plugin was fernanACM.
# https://github.com/fernanACM
        
namespace fernanACM\NickUI\forms;

use pocketmine\Server;
use pocketmine\player\Player;

use Vecnavium\FormsUI\SimpleForm;

use fernanACM\NickUI\Loader;
use fernanACM\NickUI\utils\PluginUtils;

class NickMenu{
    
    public function NickMenu(Player $player){
        $form = new SimpleForm(function(Player $player, $data){
            if($data !== null){
                switch($data){
                    case 0:
                        Loader::getInstance()->setNick->Nick($player);
                        PluginUtils::PlaySound($player, "random.pop", 1, 1.6);
                    break;
                    
                    case 1:
                       if($player->hasPermission("nickui.random")){
                           Loader::getInstance()->setNick->RandomNick($player);
                           PluginUtils::PlaySound($player, "random.pop", 1, 1.6);
                       }else{
                           $prefix = Loader::getInstance()->getMessage($player, "Prefix");
                           $player->sendMessage($prefix . Loader::getInstance()->getMessage($player, "Messages.no-permission"));
                           PluginUtils::PlaySound($player, "mob.villager.no", 1, 1);
                       }
                    break;
                    
                    case 2:
                       if(Loader::getInstance()->nick->exists($player->getName())){
                           $player->setNameTag(Loader::getInstance()->nick->getNested($player->getName() . ".normal-name"));
						   $player->setDisplayName(Loader::getInstance()->nick->getNested($player->getName() . ".normal-name"));
				     	   Loader::getInstance()->nick->remove($player->getName());
                           $prefix = Loader::getInstance()->getMessage($player, "Prefix");
                           $player->sendMessage($prefix . Loader::getInstance()->getMessage($player, "Messages.Nick.nick-reset"));
                           PluginUtils::PlaySound($player, "random.drink", 1, 1.6);
                       }else{
                          $prefix = Loader::getInstance()->getMessage($player, "Prefix");
                          $player->sendMessage($prefix . Loader::getInstance()->getMessage($player, "Messages.Nick.reset-failed"));
                          PluginUtils::PlaySound($player, "mob.villager.no", 1, 1);
                       }
                    break;
                    
                    case 3:
                       PluginUtils::PlaySound($player, "random.pop2", 1, 2.4);
                    break;        
                }
            }
        });
        $form->setTitle(Loader::getInstance()->getMessage($player, "Menu.title"));
        if(Loader::getInstance()->nick->exists($player->getName())){
            $form->setContent(str_replace(["{NICK}"], [Loader::getInstance()->nick->getNested($player->getName() . ".custom-name")], Loader::getInstance()->getMessage($player, "Menu.nick-content")));
        }
        if(!Loader::getInstance()->nick->exists($player->getName())){
            $form->setContent(Loader::getInstance()->getMessage($player, "Menu.normal-content"));
        }
        $form->addButton(Loader::getInstance()->getMessage($player, "Menu.button-nick"),0,"textures/ui/book_edit_default");
        $form->addButton(Loader::getInstance()->getMessage($player, "Menu.button-random-nick"),0,"textures/ui/book_metatag_default");
        $form->addButton(Loader::getInstance()->getMessage($player, "Menu.button-nick-reset"),0,"textures/ui/book_trash_default");
        $form->addButton(Loader::getInstance()->getMessage($player, "Menu.button-exit"),0,"textures/ui/cancel");
        $player->sendForm($form);
    }                          
}
