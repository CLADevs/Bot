<?php

declare(strict_types=1);

namespace Bot;

use pocketmine\event\Listener;
use pocketmine\event\entity\EntitySpawnEvent;

use Bot\tasks\NPCTask;

class EventListener implements Listener{

	public function onEntitySpawn(EntitySpawnEvent $e): void{
		$entity = $e->getEntity();

		if($entity instanceof Bot){
			 Main::get()->getScheduler()->scheduleDelayedTask(new NPCTask("start", $entity), 20);
		}
	}
}