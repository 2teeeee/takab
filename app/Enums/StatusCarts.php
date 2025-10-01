<?php
namespace App\Enums;

enum StatusCarts: int
{
    case active = 0;
    case completed = 1;
    case canceled = 2;
}
