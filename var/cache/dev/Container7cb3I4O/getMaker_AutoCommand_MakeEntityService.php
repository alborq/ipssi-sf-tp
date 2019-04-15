<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the private 'maker.auto_command.make_entity' shared service.

$a = ($this->privates['maker.file_manager'] ?? $this->load('getMaker_FileManagerService.php'));
$b = ($this->privates['maker.generator'] ?? $this->load('getMaker_GeneratorService.php'));

$this->privates['maker.auto_command.make_entity'] = $instance = new \Symfony\Bundle\MakerBundle\Command\MakerCommand(new \Symfony\Bundle\MakerBundle\Maker\MakeEntity($a, ($this->privates['maker.doctrine_helper'] ?? $this->load('getMaker_DoctrineHelperService.php')), '/srv', $b), $a, $b);

$instance->setName('make:entity');

return $instance;
