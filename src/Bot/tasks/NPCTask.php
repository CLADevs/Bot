<?php

declare(strict_types=1);

namespace Bot\tasks;

use pocketmine\scheduler\Task;

use Bot\{
	Bot, Main
};

class NPCTask extends Task{

	/** @var string $type */
	private $type;

	/** @var Bot $entity */
	private $entity;

	public function __construct(string $type, Bot $entity){
		$this->type = $type;
		$this->entity = $entity;
	}

	public function onRun(int $tick): void{
		$type = $this->getType();
		$entity = $this->getEntity();
		$names = Main::get()->getConfig()->get("nametags");
		$actions = ["sneak", "unsneak", "jump"];
		$randaction = $actions[array_rand($actions)];

		switch($type){
			case "start":
			$this->StartTask($randaction, 20);
			break;
			case "sneak":
			$this->Sneak();
			break;
			case "unsneak":
			$this->Sneak();
			break;
			case "jump":
			$entity->jump();
			break;
		}

		$entity->setNameTag($names[array_rand($names)]);
		$entity->spawnToAll();
		if($this->type !== "start") $this->StartTask("start", 20);
	}

	public function getEntity(): Bot{
		return $this->entity;
	}

	public function getType(): string{
		return $this->type;
	}

	public function Sneak(): void{
		if($this->getEntity()->isSneaking()){
			$this->getEntity()->setSneaking(false);
		}else{
			$this->getEntity()->setSneaking();
		}
	}

	public function StartTask(string $type, int $tick){
		Main::get()->getScheduler()->scheduleDelayedTask(new NPCTask($type, $this->getEntity()), $tick);
	}
}