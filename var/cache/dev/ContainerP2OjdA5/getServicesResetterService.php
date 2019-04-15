<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the public 'services_resetter' shared service.

include_once $this->targetDirs[3].'/vendor/symfony/http-kernel/DependencyInjection/ServicesResetter.php';

return $this->services['services_resetter'] = new \Symfony\Component\HttpKernel\DependencyInjection\ServicesResetter(new RewindableGenerator(function () {
    if (isset($this->privates['http_exception_listener'])) {
        yield 'http_exception_listener' => ($this->privates['http_exception_listener'] ?? null);
    }
    if (isset($this->services['session'])) {
        yield 'session' => ($this->services['session'] ?? null);
    }
    if (isset($this->privates['form.choice_list_factory.cached'])) {
        yield 'form.choice_list_factory.cached' => ($this->privates['form.choice_list_factory.cached'] ?? null);
    }
    if (isset($this->services['profiler'])) {
        yield 'profiler' => ($this->services['profiler'] ?? null);
    }
    if (isset($this->services['validator'])) {
        yield 'debug.validator' => ($this->services['validator'] ?? null);
    }
    if (isset($this->privates['debug.stopwatch'])) {
        yield 'debug.stopwatch' => ($this->privates['debug.stopwatch'] ?? null);
    }
    if (isset($this->privates['form.type.entity'])) {
        yield 'form.type.entity' => ($this->privates['form.type.entity'] ?? null);
    }
    if (isset($this->services['security.token_storage'])) {
        yield 'security.token_storage' => ($this->services['security.token_storage'] ?? null);
    }
}, function () {
    return 0 + (int) (isset($this->privates['http_exception_listener'])) + (int) (isset($this->services['session'])) + (int) (isset($this->privates['form.choice_list_factory.cached'])) + (int) (isset($this->services['profiler'])) + (int) (isset($this->services['validator'])) + (int) (isset($this->privates['debug.stopwatch'])) + (int) (isset($this->privates['form.type.entity'])) + (int) (isset($this->services['security.token_storage']));
}), ['http_exception_listener' => 'reset', 'session' => 'save', 'form.choice_list_factory.cached' => 'reset', 'profiler' => 'reset', 'debug.validator' => 'reset', 'debug.stopwatch' => 'reset', 'form.type.entity' => 'reset', 'security.token_storage' => 'setToken']);
