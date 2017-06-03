<?php
/**
 * Created by PhpStorm.
 * User: lorin
 * Date: 11/29/16
 * Time: 6:33 PM
 */

namespace LorinLee\LaradockCli\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class InitCommand extends Command
{

    /**
     * @var RepoConfigManager;
     */
    private $repoConfigManager;

    /**
     * {@inheritdoc}
     */
    public function __construct($name = null)
    {
        $this->repoConfigManager = new RepoConfigManager();
        parent::__construct($name);
    }

    /**
     * Configure the command options.
     *
     * @return void
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this->setName('init')
            ->setDescription('Creates Laradock for current Laravel project')
            ->addArgument('repo',
                InputArgument::OPTIONAL,
                'Configured repo',
                null);;
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Process\Exception\LogicException
     * @throws \Symfony\Component\Console\Exception\RuntimeException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (file_exists('laradock') && is_dir('laradock')) {
            throw new RuntimeException('laradock exists');
        }

        $repoName = $input->getArgument('repo');
        $repo = $this->laradockRepo($repoName);

        $initCommand = 'git clone';
        if ($this->repoConfigManager->get('config_submodule') === 'yes' && file_exists('.git')) {
            $initCommand = 'git submodule add';
        }

        $process = new Process($initCommand . ' ' . $repo);

        if ('\\' !== DIRECTORY_SEPARATOR && file_exists('/dev/tty') && is_readable('/dev/tty')) {
            $process->setTty(true);
        }

        $output->writeln('<info>Init Laradock, Source: ' . $repo . '</info>');

        $process->run(function ($line) use ($output) {
            $output->writeln($line);
        });

        $output->writeln('<comment>Initializing default config.</comment>');
        $process = new Process('cd laradock && cp env-example .env');
        $process->run();

        $output->writeln('<comment>Done.</comment>');
    }

    /**
     * Get the laradock repo
     *
     * @param string $repoName
     *
     * @return string Laradock repo
     * @throws \Symfony\Component\Console\Exception\RuntimeException
     */
    protected function laradockRepo($repoName)
    {
        if ($repoName === NULL) {
            return $this->repoConfigManager->getDefaultConfigRepo();
        }
        return $this->repoConfigManager->get($repoName);
    }

}
