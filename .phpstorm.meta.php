<?php
declare(strict_types=1);
namespace PHPSTORM_META
{
    override(
        \Psr\Container\ContainerInterface::get(0),
        // map of argument value -> return type
        map([
            "config" => 'array',
        ])
    );
    override(
        \Zend\Form\FormElementManager\FormElementManagerTrait::get(0),
        map([])
    );
}
