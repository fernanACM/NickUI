<?php

namespace fernanACM\NickUI;

use pocketmine\Server;
use pocketmine\player\Player;

use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerJoinEvent;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use pocketmine\utils\Config;

use Vecnavium\FormsUI\SimpleForm;
use Vecnavium\FormsUI\CustomForm;

use fernanACM\NickUI\Utils\PluginUtils;
use fernanACM\NickUI\commands\NickCommand;
/**use fernanACM\NickUI\commands\HideCommand;*/

class Loader extends PluginBase implements Listener {

	private $nick;
    private $config;

	public function onEnable(): void{
		$this->saveDefaultConfig();
		$this->nick = new Config($this->getDataFolder() . "nicks.yml", Config::YAML);
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getCommandMap()->register("nick", new NickCommand($this));
        /**$this->getServer()->getCommandMap()->register("hide", new HideCommand($this));*/
	}

	public function onJoin(PlayerJoinEvent $event){
        $event->setJoinMessage("");
        $player = $event->getPlayer();
        $name = $player->getName();
		$player->setDisplayName($name);
		$player->setNameTag($name);
        $this->nick->remove($name);
		$this->nick->save();
	}

	public function onQuit(PlayerQuitEvent $event){
        $event->setQuitMessage("");
        $player = $event->getPlayer();
		$name = $player->getName();
		$player = $event->getPlayer();
		if(!$this->nick->exists($name)){
			return true;
		}
		if($this->nick->exists($name)){
		   $this->nick->remove($name);
		   $this->nick->save();
		    return true;
		}
	}
    
    public function NickForm(Player $player){
		$form = new SimpleForm(function (Player $player, $data){
				if ($data !== null) {
				switch($data){
					case 0;
                        $this->NickPlayer($player);
					    PluginUtils::PlaySound($player, "random.pop", 1, 1);
					break;
                        
                    case 1:
                        $this->randomNick($player);
                        PluginUtils::PlaySound($player, "random.pop", 1, 1);
                    break;
                        
					case 2;
					    if(!$this->nick->exists($player->getName())){
						    $player->sendMessage($this->config->get("Prefix") . $this->config->get("Nick-Existen"));
						    PluginUtils::PlaySound($player, "mob.villager.no", 1, 1);
						    return true;
					    }
					    if($this->nick->exists($player->getName())){
						   $player->setNameTag($this->nick->getNested($player->getName() . ".normal-name"));
						   $player->setDisplayName($this->nick->getNested($player->getName() . ".normal-name"));
						   $this->nick->remove($player->getName());
						   $this->nick->save();
						   $player->sendMessage($this->config->get("Prefix") . $this->config->get("Nick-Normal"));
						   PluginUtils::PlaySound($player, "random.pop", 1, 1);
						   return true;
					    }
					break;

					/**case 3:
					    $this->HideForm($player);
					    PluginUtils::PlaySound($player, "random.pop", 1, 1);
					break; */

					case 3:
					    PluginUtils::PlaySound($player, "random.pop2", 1, 3);
					break;
				    }
				}
			});
			$form->setTitle("§d§lNickUI");
			if($this->nick->exists($player->getName())){
			$form->setContent($this->config->get("Nick-Content") . $this->nick->getNested($player->getName() . ".custom-name"));
			}
			if(!$this->nick->exists($player->getName())){
			$form->setContent($this->config->get("Content-Normal"));
			}
            $form->addButton($this->config->get("Button-Nick"),0,"textures/ui/book_edit_default");
            $form->addButton($this->config->get("Button-Random"),0,"textures/ui/book_metatag_default");
  			$form->addButton($this->config->get("Button-Reset"),0,"textures/ui/book_trash_default");
			/**$form->addButton($this->config->get("Button-HideNick"),0,"textures/ui/invisibility_effect"); */
			$form->addButton($this->config->get("Button-Exit"),0,"textures/ui/cancel");
			$player->sendForm($form);
	}
	
	public function NickPlayer(Player $player){
		$form = new CustomForm(function (Player $player, $data){
				if($data !== null){
                    $confignick = $this->config;
                    $confignick = $confignick->getAll();
                    if(!in_array($data[0], $confignick["Not-allow-custom-nicks"])){
					    $this->nick->setNested($player->getName() . ".custom-name", $data[0]);
					    $this->nick->setNested($player->getName() . ".normal-name", $player->getName());                      
					    $this->nick->save();
					    $this->nick->reload();
					    $player->setDisplayName($data[0]);
					    $player->setNameTag($data[0]);
                        $message2 = $this->config->get("Nick-New");
                        $player->sendMessage($this->config->get("Prefix") . str_replace("{NICK}", $data[0], $message2));
					    PluginUtils::PlaySound($player, "liquid.lavapop", 1, 4);
                    } else {
                        $player->sendMessage($this->config->get("Prefix") . $this->config->get("Not-allowed-nick"));
                        PluginUtils::PlaySound($player, "mob.villager.no", 1, 1);
                    }
				}
		});
		$form->setTitle("§l§dNick - Change");
		$form->addInput($this->config->get("Input"));
		$player->sendForm($form);
	}
    
    public function randomNick(Player $player){
        $zahl = mt_rand(0, count($this->config->get("Random-nicks")) -1 );
        $this->nick->setNested($player->getName() . ".custom-name", $this->config->get("Random-nicks")[$zahl]);
		$this->nick->setNested($player->getName() . ".normal-name", $player->getName());
        $player->setDisplayName($this->config->get("Random-nicks")[$zahl]);
        $player->setNameTag($this->config->get("Random-nicks")[$zahl]);
        $message = $this->config->get("Nick-New");
        $player->sendMessage($this->config->get("Prefix") . str_replace("{NICK}", $this->config->get("Random-nicks")[$zahl], $message));
    }

	/**public function HideForm(Player $player){
        $form = new SimpleForm(function (Player $player, $data){
            if($data !== null){             
            switch($data){
                case 0:
			        $player->setNameTag("");
			        $player->sendMessage($this->config->get("Prefix") . $this->config->get("Hide-Enable"));
			        PluginUtils::PlaySound($player, "liquid.lavapop", 1, 5);
                break;

                case 1:
			        $player->setNameTag($this->nick->getNested($player->getName() . ".custom-name"));
			        $player->sendMessage($this->config->get("Prefix") . $this->config->get("Hide-Disable"));
			        PluginUtils::PlaySound($player, "liquid.lavapop", 1, 8);
                break;

                case 2:
                    PluginUtils::PlaySound($player, "random.pop2", 1, 3);
                break;
                }	
            }
        });
        $form->setTitle("§b§lHide Nick");
        $form->addButton($this->config->get("Button-Enable"),0,"textures/ui/realms_green_check");
        $form->addButton($this->config->get("Button-Disable"),0,"textures/ui/cancel");
        $form->addButton($this->config->get("Button-Exit"),0,"textures/ui/refresh_light");
        $player->sendForm($form);
    }*/
}
