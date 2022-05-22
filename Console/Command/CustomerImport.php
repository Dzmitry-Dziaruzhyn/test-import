<?php

declare(strict_types=1);

namespace Test\Import\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Test\Import\Model\Import;

class CustomerImport extends Command
{
    /**#@+
     * Console command params
     */
    const PROFILE_NAME = 'profile-name';
    const SOURCE = 'source';
    /**#@-*/

    /**
     * @var Import
     */
    private $import;

    /**
     * @param Import $import
     * @param string|null $name
     */
    public function __construct(Import $import, string $name = null)
    {
        $this->import = $import;

        parent::__construct($name);
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setName('customer:import');
        $this->setDescription('Import customers command');
        $this->addArgument(
            self::PROFILE_NAME,
            InputArgument::REQUIRED
        )->addArgument(
            self::SOURCE,
            InputArgument::REQUIRED
        );

        parent::configure();
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $profile = $input->getArgument(self::PROFILE_NAME);
        $source = $input->getArgument(self::SOURCE);

        if (!filesize($source)) {
            $output->writeln("<error>File not found</error>");
            return;
        }

        try {
            $errors = $this->import->execute($profile, $source);
            foreach ($errors as $error) {
                $output->writeln("<error>$error</error>");
            }
        } catch (\Exception $exception) {
            $output->writeln("<error>{$exception->getMessage()}</error>");
            return;
        }

        $errors
            ? $output->writeln('<error>Import has been finished with errors</error>')
            : $output->writeln('<info>Import has been finished successfully</info>');
    }
}