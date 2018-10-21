<?php

declare(strict_types=1);

namespace Bot;

use pocketmine\scheduler\Task;

class NPCTask extends Task{

	/** @var string $type */
	private $type;

	/** @var BotEntity $entity */
	private $entity;

	public function __construct(string $type, BotEntity $entity){
		$this->type = $type;
		$this->entity = $entity;
	}

	public function onRun(int $tick) : void{
		$type = $this->getType();
		$entity = $this->getEntity();
		$names = Main::getInstance()->getConfig()->get("nametags");
		$actions = ["sneak", "unsneak", "jump"];
		$randaction = $actions[array_rand($actions)];
		switch($type){
			case "start":
				$this->startTask($randaction, 20);
				break;
			case "sneak":
				$this->sneak();
				break;
			case "unsneak":
				$this->sneak();
				break;
			case "jump":
				$entity->jump();
				break;
		}
		$entity->setNameTag($names[array_rand($names)]);
		$entity->spawnToAll();
		if($this->type !== "start") $this->startTask("start", 20);
	}

	private function getEntity() : BotEntity{
		return $this->entity;
	}

	private function getType() : string{
		return $this->type;
	}

	private function sneak() : void{
		if($this->getEntity()->isSneaking()){
			$this->getEntity()->setSneaking(false);
		}else{
			$this->getEntity()->setSneaking();
		}
	}

	private function startTask(string $type, int $tick){
		Main::getInstance()->getScheduler()->scheduleDelayedTask(new NPCTask($type, $this->getEntity()), $tick);
	}
}