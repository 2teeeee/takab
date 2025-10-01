<?php
namespace App\Enums;

enum StatusCarts: int
{
    case Active = 0;
    case Completed = 1;
    case Canceled = 2;
}
