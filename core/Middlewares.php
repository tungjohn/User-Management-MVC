<?php
abstract class Middlewares
{
    public $db;
    abstract public function handle();
}