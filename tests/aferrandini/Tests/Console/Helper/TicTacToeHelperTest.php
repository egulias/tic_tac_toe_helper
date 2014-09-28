<?php
/**
 * TicTacToeHelperTest.php
 *
 * Ariel Ferrandini <arielferrandini@gmail.com>
 * 27/09/14
 */
namespace Aferrandini\Tests\Console\Helper;

use Aferrandini\Console\Helper\TicTacToeHelper;
use Symfony\Component\Console\Output\StreamOutput;

class TicTacToeHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testDisplay()
    {
        $ticTacToe = new TicTacToeHelper($output = $this->getOutputStream(false));
        $ticTacToe->display();

        rewind($output->getStream());

        $this->assertEquals(
            $this->generateOutput('').
            $this->generateOutput('-------------').
            $this->generateOutput('|   |   |   |').
            $this->generateOutput('-------------').
            $this->generateOutput('|   |   |   |').
            $this->generateOutput('-------------').
            $this->generateOutput('|   |   |   |').
            $this->generateOutput('-------------').
            $this->generateOutput('').
            $this->generateOutput('X Player 1').
            $this->generateOutput('O Player 2'),
            stream_get_contents($output->getStream())
        );
    }

    public function testDisplayOtherSize()
    {
        $ticTacToe = new TicTacToeHelper($output = $this->getOutputStream(false), 5);
        $ticTacToe->display();

        rewind($output->getStream());

        $this->assertEquals(
            $this->generateOutput('').
            $this->generateOutput('---------------------').
            $this->generateOutput('|   |   |   |   |   |').
            $this->generateOutput('---------------------').
            $this->generateOutput('|   |   |   |   |   |').
            $this->generateOutput('---------------------').
            $this->generateOutput('|   |   |   |   |   |').
            $this->generateOutput('---------------------').
            $this->generateOutput('|   |   |   |   |   |').
            $this->generateOutput('---------------------').
            $this->generateOutput('|   |   |   |   |   |').
            $this->generateOutput('---------------------').
            $this->generateOutput('').
            $this->generateOutput('X Player 1').
            $this->generateOutput('O Player 2'),
            stream_get_contents($output->getStream())
        );
    }

    public function testUpdateGame()
    {
        $ticTacToe = new TicTacToeHelper($output = $this->getOutputStream(false));
        $ticTacToe->updateGame(1, 1, 1);
        $ticTacToe->updateGame(0, 0, 2);

        rewind($output->getStream());

        //file_put_contents('/tmp/ttt.log', stream_get_contents($output->getStream()));
        $this->assertEquals(
            $this->generateOutput('').
            $this->generateOutput('-------------').
            $this->generateOutput('|   |   |   |').
            $this->generateOutput('-------------').
            $this->generateOutput('|   | X |   |').
            $this->generateOutput('-------------').
            $this->generateOutput('|   |   |   |').
            $this->generateOutput('-------------').
            $this->generateOutput('Last game by player 1').
            $this->generateOutput('X Player 1').
            $this->generateOutput('O Player 2').
            $this->generateOutput('').
            $this->generateOutput('-------------        ').
            $this->generateOutput('| O |   |   |').
            $this->generateOutput('-------------        ').
            $this->generateOutput('|   | X |   |').
            $this->generateOutput('-------------        ').
            $this->generateOutput('|   |   |   |        ').
            $this->generateOutput('-------------        ').
            $this->generateOutput('Last game by player 2').
            $this->generateOutput('X Player 1').
            $this->generateOutput('O Player 2'),
            stream_get_contents($output->getStream())
        );
    }

    /**
     * @expectedException   Aferrandini\Console\Exception\PlayerNotFoundException
     */
    public function testPlayerNotFoundException()
    {
        $ticTacToe = new TicTacToeHelper($output = $this->getOutputStream(false));
        $ticTacToe->updateGame(1, 1, 3);
    }

    /**
     * @expectedException   Aferrandini\Console\Exception\PlayerRepeatGameException
     */
    public function testLastPlayerGameException()
    {
        $ticTacToe = new TicTacToeHelper($output = $this->getOutputStream(false));
        $ticTacToe->updateGame(1, 1, 1);
        $ticTacToe->updateGame(0, 0, 1);
    }

    /**
     * @expectedException   Aferrandini\Console\Exception\MoveAlreadyDoneException
     */
    public function testMoveAlreadyPlayedException()
    {
        $ticTacToe = new TicTacToeHelper($output = $this->getOutputStream(false));
        $ticTacToe->updateGame(1, 1, 1);
        $ticTacToe->updateGame(1, 1, 2);
    }

    protected function getOutputStream($decorated = true, $verbosity = StreamOutput::VERBOSITY_NORMAL)
    {
        return new StreamOutput(fopen('php://memory', 'r+', false), $verbosity, $decorated);
    }

    protected function generateOutput($expected)
    {
        $count = substr_count($expected, "\n");

        return $expected . ($count ? "" : "\n");
    }
}
 