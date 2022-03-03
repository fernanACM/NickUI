<?php

namespace fernanACM\NickUI;

use pocketmine\Server;
use pocketmine\player\Player;

use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerJoinEvent;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use pocketmine\utils\Config;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use fernanACM\NickUI\Lib\FormsUI\SimpleForm;
use fernanACM\NickUI\Lib\FormsUI\CustomForm;
use fernanACM\NickUI\Utils\PluginUtils;

class Loader extends PluginBase implements Listener {

	private $nick;

	public function onEnable(): void{
		$this->saveDefaultConfig();
		$this->nick = new Config($this->getDataFolder() . "config.yml", Config::YAML);
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onJoin(PlayerJoinEvent $event){
        $event->setJoinMessage("");
		$player = $event->getPlayer();
		$name = $player->getName();
		$player->setDisplayName($name);
		$player->setNameTagVisible(true);
		$player->setNameTag($name);
	}

	public function onQuit(PlayerQuitEvent $event){
		$event->setQuitMessage("");
		$player = $event->getPlayer();
		if(!$this->nick->exists($player->getName())){
			return true;
		}
		if($this->nick->exists($player->getName())){
		   $this->nick->remove($player->getName());
		   $this->nick->save();
		    return true;
		}
	}

    public function onCommand(CommandSender $sender, Command $command, String $label, Array $args): Bool{
    	switch ($command->getName()){
    		case "nick":
    		    if($sender instanceof Player) {
                    	   $this->NickForm($sender);
                    	   PluginUtils::PlaySound($sender, "random.burp", 1, 2);
                    } else {
                           $sender->sendMessage("Use this command in-game");
                            return true;
                    }
            break;
            case "hide":
                if($sender instanceof Player) {
                           $this->HideForm($sender);
                           PluginUtils::PlaySound($sender, "random.burp", 1, 3);
                    } else {
                           $sender->sendMessage("Use this command in-game");
                            return true;
                    }
            break;
    	}
    	return true;
    }
    
    public function NickForm(Player $player){
		$form = new SimpleForm(function (Player $player, $data){
				if ($data !== null) {
				switch($data){
					case 0;
					$this->NickPlayer($player);
					PluginUtils::PlaySound($player, "random.pop", 1, 1);
					break;
					case 1;
					if(!$this->nick->exists($player->getName())){
						$player->sendMessage($this->nick->get("Prefix") . $this->nick->get("Nick-Existen"));
						PluginUtils::PlaySound($player, "random.pop", 1, 1);
						return true;
					}
					if($this->nick->exists($player->getName())){
						$player->setNameTag($this->nick->getNested($player->getName() . ".normal-name"));
						$player->setDisplayName($this->nick->getNested($player->getName() . ".normal-name"));
						$this->nick->remove($player->getName());
						$this->nick->save();
						$player->sendMessage($this->nick->get("Prefix") . $this->nick->get("Nick-Normal"));
						PluginUtils::PlaySound($player, "random.pop", 1, 1);
						return true;
					}
					break;

					case 2:
					    $this->HideForm($player);
					    PluginUtils::PlaySound($player, "random.pop", 1, 1);
					breaK;

					case 3:
					    PluginUtils::PlaySound($player, "random.pop2", 1, 3);
					break;
				    }
				}
			});
			$form->setTitle("§3§lNickUI");
			if($this->nick->exists($player->getName())){
			$form->setContent($this->nick->get("Nick-Content") . $this->nick->getNested($player->getName() . ".custom-name"));
			}
			if(!$this->nick->exists($player->getName())){
			$form->setContent($this->nick->get("Content-Normal"));
			}
			$form->addButton($this->nick->get("Button-Nick"));
			$form->addButton($this->nick->get("Button-Reset"));
			$form->addButton($this->nick->get("Button-HideNick"));
			$form->addButton($this->nick->get("Button-Exit"));
			$player->sendForm($form);
	}
	
	public function NickPlayer(Player $player){
		$form = new CustomForm(function (Player $player, $data){
				if($data !== null){
					$this->nick->setNested($player->getName() . ".custom-name", $data[0]);
					$this->nick->setNested($player->getName() . ".normal-name", $player->getName());
					$this->nick->save();
					$this->nick->reload();
					$player->setDisplayName($data[0]);
					$player->setNameTag($data[0]);
					$player->sendMessage($this->nick->get("Prefix") . $this->nick->get("Nick-New") . $data[0]);
					PluginUtils::PlaySound($player, "liquid.lavapop", 1, 4);
					return true;
				}
		});
		$form->setTitle("§l§3Nick - Change");
		$form->addInput($this->nick->get("Input"));
		$player->sendForm($form);
	}

	public function HideForm(Player $player){
        $form = new SimpleForm(function (Player $player, $data){
            if($data !== null){             
            switch($data){
                case 0:
			        $player->setNameTagVisible(false);
			        $player->sendMessage($this->nick->get("Prefix") . $this->nick->get("Hide-Enable"));
			        PluginUtils::PlaySound($player, "liquid.lavapop", 1, 5);
                break;

                case 1:
			        $player->setNameTagVisible(true);
			        $player->sendMessage($this->nick->get("Prefix") . $this->nick->get("Hide-Disable"));
			        PluginUtils::PlaySound($player, "liquid.lavapop", 1, 4);
                break;

                case 2:
                    PluginUtils::PlaySound($player, "random.pop2", 1, 3);
                break;
                }	
            }
        });
        $form->setTitle("§b§lVanish");
        $form->addButton($this->nick->get("Button-Enable"));
        $form->addButton($this->nick->get("Button-Disable"));
        $form->addButton($this->nick->get("Button-Exit"));
        $player->sendForm($form);
    }
}
