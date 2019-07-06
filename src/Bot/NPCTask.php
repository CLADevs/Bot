<?php

declare(strict_types=1);

namespace Bot;

use pocketmine\scheduler\Task;

class NPCTask extends Task{

	/** @var string $type */
	protected $type;
	/** @var BotEntity $entity */
	protected $entity;

	public function __construct(string $type, BotEntity $entity){
		$this->type = $type;
		$this->entity = $entity;
	}

	public function onRun(int $tick) : void{
		$names = Main::getInstance()->getConfig()->get("nametags");
		$actions = ["sneak", "unsneak", "jump"];
		$randaction = $actions[array_rand($actions)];
		switch($this->getType()){
			case "start":
				$this->startTask($randaction, 20);
				break;
			case "sneak":
				$this->getEntity()->setSneaking(true);
				break;
			case "unsneak":
				$this->getEntity()->setSneaking(false);
				break;
			case "jump":
				$this->getEntity()->jump();
				break;
		}
		$this->getEntity()->setNameTag($names[array_rand($names)]);
		$this->getEntity()->spawnToAll();
		if($this->getType() !== "start") $this->startTask("start", 20);
	}

	protected function getEntity() : BotEntity{
		return $this->entity;
	}

	protected function getType() : string{
		return $this->type;
	}

	protected function startTask(string $type, int $tick){
		Main::getInstance()->getScheduler()->scheduleDelayedTask(new NPCTask($type, $this->getEntity()), $tick);
	}
}