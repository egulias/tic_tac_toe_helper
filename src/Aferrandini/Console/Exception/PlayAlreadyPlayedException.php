<?php
/**
 * PlayAlreadyPlayedException.php
 *
 * Ariel Ferrandini <arielferrandini@gmail.com>
 * 27/09/14
 */ 

namespace Aferrandini\Console\Exception;


use Exception;

class PlayAlreadyPlayedException extends \RuntimeException
{
    public function __construct ($x, $y, $player)
    {
        parent::__construct(sprintf('Play [%s, %s] already played by player %s.', $x, $y, $player));
    }

}
 