<?php
/**
 * Created by PhpStorm.
 * User: lorin
 * Date: 11/29/16
 * Time: 6:27 PM
 */

namespace LorinLee\LaradockCli\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Process\Process;

class UpCommand extends Command
{
    /**
     * Configure the command options
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('up')
            ->setDescription('Starts containers')
            ->addArgument('', InputArgument::IS_ARRAY, 'Containers', null)
            ->addOption('default', 'd', InputOption::VALUE_OPTIONAL, 'Use default containers: nginx redis mysql')
        ;
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $containers = $input->getArgument('');
        if (count($containers) === 0) {
            if ($input->getOption('default')) {
                $containers = $this->defaultContainers();
            } else {
                $helper = $this->getHelper('question');
                $question = new ConfirmationQuestion('No containers selected, starting all the containers?', false);

                if (! $helper->ask($input, $output, $question)) {
                    return ;
                }
            }
        }

        $up_parameter = implode(' ', $containers);

        $output->writeln('<info>Starting '. $up_parameter. '</info>');

        $process = new Process('cd laradock && docker-compose up -d '. $up_parameter);
        $process->run(function ($type, $line) use ($output) {
            $output->write($line);
        });

        $output->writeln('<comment>Done.</comment>');
    }

    /**
     * Get containers
     *
     * @return array containers
     */
    protected function defaultContainers()
    {
        return [
            'nginx',
            'redis',
            'mysql',
        ];
    }
}