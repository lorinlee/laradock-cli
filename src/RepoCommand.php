<?php
/**
 * Created by PhpStorm.
 * User: lorin
 * Date: 17-2-13
 * Time: 12:58 PM
 */

namespace LorinLee\LaradockCli\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RepoCommand extends Command
{

    /**
     * @var RepoConfigManager;
     */
    private $repoConfigManager;

    /**
     * {@inheritdoc}
     */
    public function __construct($name = null) {
        $this->repoConfigManager = new RepoConfigManager();
        parent::__construct($name);
    }

    /**
     * Configure the command option
     *
     * @return void
     */
    public function configure()
    {
        $this->setName('repo')
            ->setDescription('Set LaraDock git repo, you can use your own repo.')
            ->addArgument('name',
                InputArgument::OPTIONAL,
                'LaraDock git repo config name',
                $this->repoConfigManager->getDefaultConfigName())
            ->addArgument('repo',
                InputArgument::OPTIONAL,
                'LaraDock git repo src',
                $this->repoConfigManager->getDefaultConfigRepo());
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     * @throws \Symfony\Component\Console\Exception\RuntimeException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $repo = $input->getArgument('repo');
        $this->repoConfigManager->setConfig($name, $repo);

        $output->writeln('<info>Done. Set ' . $name . ': ' . $repo . '</info>');
    }
}
