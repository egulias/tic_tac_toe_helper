<?php
/**
 * TicTacToeHelper.php
 *
 * Ariel Ferrandini <arielferrandini@gmail.com>
 * 27/09/14
 */ 

namespace Aferrandini\Console\Helper;


use Aferrandini\Console\Exception\MoveAlreadyDoneException;
use Aferrandini\Console\Exception\PlayerNotFoundException;
use Aferrandini\Console\Exception\PlayerRepeatGameException;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\Console\Output\OutputInterface;

class TicTacToeHelper
{
    private $output;

    private $size;

    private $player1_char = 'X';
    private $player2_char = 'O';

    private $last_player = null;

    private $game;
    private $overwrite = true;
    private $lastMessageLines = array();
    private $lastMessagesLength = 0;

    public function __construct(OutputInterface $output, $size = 3, $overwrite = true)
    {
        $this->overwrite = $overwrite;
        $this->output = $output;
        $this->size = $size;

        $this->initGame();
    }

    public function initGame()
    {
        $this->game = array();
        $this->last_player = null;

        for ($x=0; $x<$this->size; $x++) {
            $row = array();

            for ($y=0; $y<$this->size; $y++) {
                $row[] = null;
            }

            $this->game[] = $row;
        }

        $this->display();
    }

    public function display()
    {
        $message = '';

        $message .= $this->getLineSeparator();
        foreach ($this->game as $row) {
            $message .= $this->getRow($row);
        }

        if ($this->last_player) {
            $message .= "Last game by player {$this->last_player}\n";
        } else {
            $message .= "\n";
        }

        $message .= "<info>{$this->player1_char}</info> Player 1\n"
            ."<comment>{$this->player2_char}</comment> Player 2"
        ;

        $this->overwrite($message);
    }

    private function getRow($row)
    {
        $message = '';
        foreach ($row as $player) {
            $message .= '| ' . $this->getPlayerChar($player) . ' ';
        }
        $message .= "|\n";
        $message .= $this->getLineSeparator();

        return $message;
    }

    private function getLineSeparator()
    {
        return str_repeat('-', ($this->size*4)+1) . "\n";
    }

    public function updateGame($x, $y, $player)
    {
        if (!in_array($player, array(1,2))) {
            throw new PlayerNotFoundException($player);
        }

        if ($this->last_player === $player) {
            throw new PlayerRepeatGameException($player);
        }

        if (!is_null($this->game[$x][$y])) {
            throw new MoveAlreadyDoneException($x, $y, $this->game[$x][$y]);
        }

        $this->last_player = $player;
        $this->game[$x][$y] = $player;

        $this->display();
    }

    /**
     * Overwrites a previous message to the output.
     *
     * @param string $message The message
     */
    private function overwrite($message)
    {
        $lines = explode("\n", $message);

        // append whitespace to match the line's length
        if (null !== $this->lastMessagesLength) {
            foreach ($lines as $i => $line) {
                if ($this->lastMessagesLength > Helper::strlenWithoutDecoration($this->output->getFormatter(), $line)) {
                    $lines[$i] = str_pad($line, $this->lastMessagesLength, "\x20", STR_PAD_RIGHT);
                }
            }
        }

        if ($this->overwrite) {
            // move back to the beginning of the screen bar before redrawing it
            $this->output->write("\x0D");
            $this->output->write(sprintf("\x1B[%dA", count($this->lastMessageLines)-1));
        } else {
            // move to new line
            $this->output->writeln('');
        }

        $lines[] = "";
        $this->output->write(implode("\n", $lines));

        $this->lastMessageLines = $lines;
        $this->lastMessagesLength = 0;
        foreach ($lines as $line) {
            $len = Helper::strlenWithoutDecoration($this->output->getFormatter(), $line);
            if ($len > $this->lastMessagesLength) {
                $this->lastMessagesLength = $len;
            }
        }
    }

    private function getPlayerChar($player)
    {
        switch ($player) {
            case 1:
                return "<info>{$this->player1_char}</info>";

            case 2:
                return "<comment>{$this->player2_char}</comment>";

            default:
                return ' ';
        }
    }
}
