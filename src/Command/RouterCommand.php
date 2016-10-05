<?php

/*
 * This file is part of the Mamba microframework.
 *
 * (c) Mauro Cassani <assistenza@easy-grafica.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mamba\Command;

use App\Application;
use Knp\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RouterCommand extends Command
{
    /**
     * @var Application
     */
    private $app;

    /**
     * RouterCommand constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        parent::__construct('app:router');

        $this->app = $app;
    }

    protected function configure()
    {
        $this
            ->setName('app:router')
            ->setDescription('Display info on routes.')
            ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $routings = $this->app['config']['routings'];
        ksort($routings);

        $counter = 0;
        $rows = [];

        foreach (@$routings as $r => $details) {
            ++$counter;
            $c = explode('::', $details['action']);
            $rows[] = [
                $r,
                $details['method'],
                $details['url'],
                $c[0],
                $c[1],
            ];
        }

        $table = new Table($output);
        $table
            ->setHeaders(['Name', 'Verb(s)', 'Pattern', 'Controller', 'Method'])
            ->setRows($rows);

        $table->render();
    }
}