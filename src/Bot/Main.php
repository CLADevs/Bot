<?php

declare(strict_types=1);

namespace Bot;

use pocketmine\plugin\PluginBase;
use pocketmine\entity\Entity;
use pocketmine\utils\TextFormat as C;
use pocketmine\command\{
	CommandSender, Command
};

class Main extends PluginBase{

	private static $instance;

	public function onEnable(): void{
		self::$instance = $this;
		$this->saveDefaultConfig();
		$this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
		Entity::registerEntity(Bot::class, true);
	}

	public static function get(): self{
		return self::$instance;
	}

	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
		if(!$sender->isOp()){
			$sender->sendMessage(C::RED . "You must be opped to spawn bot.");
			return false;
		}
		if(count($args) < 1){
			$sender->sendMessage("Usage: /bot <name>");
			return false;
		}

		$name = implode(" ", $args);
		$nbt = Entity::createBaseNBT($sender, null, 2, 2);
		$nbt->setTag($sender->namedtag->getTag("Skin"));
		$npc = new Bot($sender->getLevel(), $nbt);
		$npc->setNameTag($name);
		$npc->setNameTagAlwaysVisible(true);
		$npc->spawnToAll();
		$sender->sendMessage(C::GREEN . "Spawned " . $name);
		return true;
	}
}