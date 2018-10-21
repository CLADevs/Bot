<?php

declare(strict_types=1);

namespace Bot;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\EntitySpawnEvent;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\entity\Entity;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener{

	/** @var self $instance */
	private static $instance;

	public function onEnable() : void{
		self::$instance = $this;
		$this->saveDefaultConfig();
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		Entity::registerEntity(BotEntity::class, true);
	}

	public static function getInstance() : self{
		return self::$instance;
	}

	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool{
		if(!$sender instanceof Player){
			$sender->sendMessage(TextFormat::RED . "You must be a player to use this command");
			return false;
		}
		if(!$sender->isOp()){
			$sender->sendMessage(TextFormat::RED . "You must be opped to spawn bot.");
			return false;
		}
		if(count($args) < 1){
			$sender->sendMessage(TextFormat::GRAY . "Usage: /bot <name>");
			return false;
		}
		$name = implode(" ", $args);
		$nbt = Entity::createBaseNBT($sender, null, 2, 2);
		$nbt->setTag($sender->namedtag->getTag("Skin"));
		$npc = new BotEntity($sender->getLevel(), $nbt);
		$npc->setNameTag($name);
		$npc->setNameTagAlwaysVisible(true);
		$npc->spawnToAll();
		$sender->sendMessage(TextFormat::GREEN . "Spawned " . $name);
		return true;
	}

	public function onEntitySpawn(EntitySpawnEvent $event) : void{
		$entity = $event->getEntity();
		if($entity instanceof BotEntity) $this->getScheduler()->scheduleDelayedTask(new NPCTask("start", $entity), 20);
	}
}
