<?php
    
#      _       ____   __  __ 
#     / \     / ___| |  \/  |
#    / _ \   | |     | |\/| |
#   / ___ \  | |___  | |  | |
#  /_/   \_\  \____| |_|  |_|
# The creator of this plugin was fernanACM.
# https://github.com/fernanACM
       
namespace fernanACM\NickUI;

use pocketmine\Server;
use pocketmine\player\Player;

use pocketmine\event\Listener;

use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;

use fernanACM\NickUI\Loader;

class Event implements Listener{
    
    public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        $name = $player->getName();
        if(!Loader::getInstance()->nick->exists($player->getName())){
			    return true;
		    }else{
		      Loader::getInstance()->nick->remove($player->getName());
		      return true;
		    }
    }
    
    public function onQuit(PlayerQuitEvent $event){
        $player = $event->getPlayer();
        if(!Loader::getInstance()->nick->exists($player->getName())){
			     return true;
		    }else{
		      Loader::getInstance()->nick->remove($player->getName());
		      return true;
		    }
	  }
    
    public function onLogin(PlayerLoginEvent $event){
        $player = $event->getPlayer();
        if(!Loader::getInstance()->nick->exists($player->getName())){
			     return true;
		    }else{
		       Loader::getInstance()->nick->remove($player->getName());
		        return true;
		    }
    }
}
