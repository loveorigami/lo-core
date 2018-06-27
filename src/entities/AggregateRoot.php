<?php
namespace lo\core\entities;


interface AggregateRoot
{
    public function getId();
    public function releaseEvents();
}
