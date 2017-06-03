<?php
/**
 * Created by PhpStorm.
 * User: lorin
 * Date: 11/29/16
 * Time: 6:28 PM
 */

namespace LorinLee\LaradockCli\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Process\Process;

class StopCommand extends Command
{

    /**
     * Configure the command options
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('stop')
            ->setDescription('Stops selected containers')
            ->addArgument('containers',
                InputArgument::IS_ARRAY,
                'Containers',
                null);
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $stopCommand = 'cd laradock && docker-compose stop';

        $containers = $input->getArgument('containers');
        if (count($containers) === 0) {
            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion('No containers selected, stop all the containers?',
                false);

            if (!$helper->ask($input, $output, $question)) {
                return;
            }
        } else {
            $stopParameter = implode(' ', $containers);
            $stopCommand .= ' ' . $stopParameter;
        }

        $process = new Process($stopCommand);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            $process->setTty(true);
        }

        $output->writeln('<info>Stop Containers...</info>');

        $process->run(function ($type, $line) use ($output) {
            $output->write($line);
        });

        $output->writeln('<comment>Done.</comment>');
    }
}
