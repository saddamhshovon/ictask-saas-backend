<?php

namespace App\Exceptions;

use Exception;

class InventoryExistsException extends Exception
{
    protected $message = 'You already have an inventory.';
}
