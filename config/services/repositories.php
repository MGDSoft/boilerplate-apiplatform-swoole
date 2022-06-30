<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    /** @var string[] $data */
    $data = glob(__DIR__.'/../../src/*/Domain/Repository/[^W]*RepositoryInterface.php');

    foreach ($data as $file) {
        $matches = [];
        preg_match('/\/src\/(.*?)RepositoryInterface\.php/', $file, $matches);
        $nameSpace= $matches[1];
        $nameSpace = 'App\\' . str_replace('/', '\\', $nameSpace);

        $interfaceService = $nameSpace . 'RepositoryInterface';
        $doctrineService = str_replace('Domain\\', 'Infrastructure\\Doctrine\\', $nameSpace). 'RepositoryDoctrine';

        if (!interface_exists($interfaceService)) {
            throw new CompileError("Interface not exist '$doctrineService'");
        }

        if (!class_exists($doctrineService)) {
            throw new CompileError("Class not exist '$doctrineService'");
        }

        /* @phpstan-ignore-next-line */
        if (!isset(class_implements($doctrineService)[$interfaceService])) {
            throw new CompileError("Class '$doctrineService' is not an instance of '$doctrineService'");
        }

        $services
            ->set($interfaceService)
            ->class($doctrineService);
    }
};
