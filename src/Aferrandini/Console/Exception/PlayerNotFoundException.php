<?php
/**
 * PlayerNotFoundException.php
 *
 * Ariel Ferrandini <arielferrandini@gmail.com>
 * 27/09/14
 */
namespace Aferrandini\Console\Exception;

class PlayerNotFoundException extends \RuntimeException
{
    public function __construct($player)
    {
        parent::__construct(sprintf('Player "%s" not found.', $player));
    }
}
