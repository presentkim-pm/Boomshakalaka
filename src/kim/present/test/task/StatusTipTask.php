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
        $tileCount = 0;
        foreach($server->getWorldManager()->getWorlds() as $world){
            ++$worldCount;
            foreach($world->getChunks() as $chunk){
                ++$chunkCount;

                $entityCount += count($chunk->getEntities());
                $tileCount += count($chunk->getTiles());
            }
            $chunkCount += count($world->getChunks());
        }

        $server->broadcastTip(
            "Server: {$server->getName()}_v{$server->getApiVersion()} (PHP " . phpversion() . ")\n" .
            "TPS: {$server->getTicksPerSecond()} ({$server->getTickUsage()}%)\n" .
            "Threads: {$threadCount}, Memory: {$totalMemory} MB\n" .
            "World({$worldCount}) Chunk: {$chunkCount}, Entity: {$entityCount}, Tile: {$tileCount}"
        );
    }
}