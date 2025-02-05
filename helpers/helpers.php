<?php

function url(): bool|string
{
    return strtok(string: $_SERVER['REQUEST_URI'], token: '?');
}

function view(string $view)
{
   //ToDo 
}