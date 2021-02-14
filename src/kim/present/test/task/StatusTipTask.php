<?php
declare(strict_types=1);

namespace kim\present\test\task;

use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\Process;

final class StatusTipTask extends Task{
    public function onRun() : void{
        $server = Server::getInstance();

        $threadCount = Process::getThreadCount();
        $totalMemory = number_format(round((Process::getAdvancedMemoryUsage()[1] / 1024) / 1024, 2), 2);

        $worldCount = 0;
        $chunkCount = 0;
        $entityCount = 0;
        foreach($server->getWorldManager()->getWorlds() as $world){
            $worldCount += 1;
            $chunkCount += count($world->getChunks());
            $entityCount += count($world->getEntities());
        }

        $tip = implode("\n", [
            "TPS: {$server->getTicksPerSecond()} ({$server->getTickUsage()}%)",
            "Threads: {$threadCount}, Memory: {$totalMemory} MB",
            "World({$worldCount}) Chunk: {$chunkCount}, Entity: {$entityCount}"
        ]);

        foreach($server->getOnlinePlayers() as $player){
            $player->sendTip($tip);
        }
    }
}