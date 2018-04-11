<?php
/**
 * Created by PhpStorm.
 * User: glenn
 * Date: 2018/4/9
 * Time: 下午 3:55
 */
namespace AstralWeb\NLP\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Install extends Command
{
    protected function configure()
    {
        $this->setName('google-cloud:install');
        $this->setDescription('Install the Google Cloud SDK');

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }

}