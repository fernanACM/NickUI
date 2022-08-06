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

use pocketmine\plugin\PluginBase;

use pocketmine\utils\Config;
# Libs
use Vecnavium\FormsUI\FormsUI;
use muqsit\simplepackethandler\SimplePacketHandler;
use CortexPE\Commando\PacketHooker;
use CortexPE\Commando\BaseCommand;
# My files
use fernanACM\NickUI\forms\NickMenu;
use fernanACM\NickUI\forms\subforms\NickForm;

use fernanACM\NickUI\commands\NickCommand;

use fernanACM\NickUI\utils\PluginUtils;

use fernanACM\NickUI\Event;

class Loader extends PluginBase{
    
    public Config $config;
    public Config $nick;
    public Config $messages;
    
    public static $instance;
    
    public function onEnable(): void{
        self::$instance = $this;
        $this->loadForms();
        $this->loadFiles();
        $this->loadCommands();
        $this->loadEvents();
        foreach ([
        	"FormsUI" => FormsUI::class,
                "Commando" => BaseCommand::class,
                "SimplePacketHandler" => SimplePacketHandler::class
            ] as $virion => $class
        ) {
            if (!class_exists($class)) {
                $this->getLogger()->error($virion . " virion not found. Please download NickUI from Poggit-CI or use DEVirion (not recommended).");
                $this->getServer()->getPluginManager()->disablePlugin($this);
                return;
            }
        }
    }
    
    public function loadForms(){
        $this->menu = new NickMenu($this);
        $this->setNick = new NickForm($this);
    }
    
    public function loadFiles(){
        $this->saveResource("config.yml");
        $this->saveResource("messages.yml");
        
        $this->config = new Config($this->getDataFolder() . "config.yml");
        $this->messages = new Config($this->getDataFolder() . "messages.yml");
        $this->nick = new Config($this->getDataFolder() . "nicks.yml");
    }
    
    public function loadEvents(){
        $this->getServer()->getPluginManager()->registerEvents(new Event($this), $this);
    }
    
    public function loadCommands(){
        $this->getServer()->getCommandMap()->register("nickui", new NickCommand($this, "nickui", "§r§fOpen NickUI by §bfernanACM", ["nick"]));
    }
    
    public function getMessage(Player $player, string $key){
        return PluginUtils::codeUtil($player, $this->messages->getNested($key, $key));
    }
    
    public static function getInstance(): Loader{
        return self::$instance;
    }
}
