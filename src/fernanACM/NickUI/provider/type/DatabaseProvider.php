<?php

declare(strict_types=1);

namespace fernanACM\NickUI\provider\type;

use pocketmine\player\Player;

use poggit\libasynql\libasynql;
use poggit\libasynql\DataConnector;

use fernanACM\NickUI\NickUI;
use fernanACM\NickUI\provider\Provider;
use fernanACM\NickUI\const\DatabaseConst;
use fernanACM\NickUI\const\DataConst;

class DatabaseProvider extends Provider{

    /** @var NickUI $plugin */
    protected NickUI $plugin;

    /** @var DataConnector $database */
    protected DataConnector $database;

    public function __construct(NickUI $plugin){
        $this->plugin = $plugin;
    }

    /**
     * @return void
     */
    public function load(): void{
        $data = strtolower(strval($this->plugin->config->getNested("Storage.Database.type")));
        $this->database = libasynql::create($this->plugin, $data, [
            "sqlite" => "sqlite.sql",
			"mysql"  => "mysql.sql",
        ]);
        $this->database->executeGeneric(DatabaseConst::INIT);
    }

    /**
     * @return void
     */
    public function unload(): void{
        if(!is_null($this->database)) $this->database->close();
    }

    /**
     * @param Player $player
     * @return boolean
     */
    public function exists(Player $player): bool{
        $result = false;
        $this->database->executeSelect(DatabaseConst::CHECK_EXISTENCE, [
            DataConst::PLAYER_NAME => $player->getDisplayName()
        ], function(array $rows) use(&$result): void{
            $result = count($rows) > 0;
        });
        return $result;
    }

    /**
     * @param Player $player
     * @return void
     */
    public function createAccount(Player $player): void{
        $this->database->executeInsert(DatabaseConst::CREATE_ACCOUNT, [
            DataConst::PLAYER_NAME => $player->getDisplayName(),
            DataConst::DISPLAY_NAME => $player->getDisplayName(),
            DataConst::NICKNAME => "",
            DataConst::NICKNAMES => json_encode([])
        ]);
    }

    /**
     * @param Player $player
     * @return string|null
     */
    public function getNickName(Player $player): ?string{
        $nickname = null;
        $this->database->executeSelect(DatabaseConst::GET_NICKNAME, [
            DataConst::PLAYER_NAME => $player->getDisplayName()
        ], function(array $rows) use(&$nickname): void{
            if(count($rows) > 0){
                $nickname = strval($rows[0][DataConst::NICKNAME]);
            }
        });
        return $nickname;
    }

    /**
     * @param Player $player
     * @return string|null
     */
    public function getOldName(Player $player): ?string{
        $oldName = null;
        $this->database->executeSelect(DatabaseConst::GET_DISPLAY_NAME, [
            DataConst::PLAYER_NAME => $player->getDisplayName()
        ], function(array $rows) use(&$oldName): void{
            if(count($rows) > 0){
                $oldName = strval($rows[0][DataConst::DISPLAY_NAME]);
            }
        });
        return $oldName;
    }

    /**
     * @param Player $player
     * @return array|null
     */
    public function getAllNickNames(Player $player): ?array{
        $nicknames = null;
        $this->database->executeSelect(DatabaseConst::GET_ALL_NICKNAMES, [
            DataConst::PLAYER_NAME => $player->getDisplayName()
        ], function(array $rows) use(&$nicknames): void{
            if(count($rows) > 0){
                $nicknames = json_decode($rows[0][DataConst::NICKNAMES], true);
            }
        });
        return $nicknames;
    }
    
    /**
     * @param Player $player
     * @param string $nickName
     * @return void
     */
    public function setNickName(Player $player, string $nickName): void{
        $nicks = $this->getAllNickNames($player) ?? [];
        if(!in_array($nickName, $nicks)){
            $nicks[] = $nickName;
        }
        $this->database->executeChange(DatabaseConst::SET_NICKNAME, [
            DataConst::PLAYER_NAME => $player->getDisplayName(),
            DataConst::NICKNAME => $nickName
        ]);
        $this->database->executeChange(DatabaseConst::UPDATE_NICKNAMES, [
            DataConst::PLAYER_NAME => $player->getDisplayName(),
            DataConst::NICKNAMES => json_encode($nicks)
        ]);
    }
}