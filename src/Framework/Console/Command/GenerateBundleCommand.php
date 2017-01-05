<?php
namespace TastPHP\Framework\Console\Command;

use Symfony\Component\Console\Command\Command;

class GenerateBundleCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('generate:Bundle')
            ->setDescription('Generates a Bundle')
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'Entity name (default:Demo)'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        if ($name) {
            $output->writeln('You have just selected: ' . $name);
            exit();
        }
        $helper = $this->getHelper('question');
        $question = new Question('Please enter the name of the bundle:', 'AcmeDemoBundle');
        $question->setValidator(function ($answer) {
            if ('Bundle' !== substr($answer, -6)) {
                throw new \RuntimeException(
                    'The name of the bundle should be suffixed with \'Bundle\''
                );
            }
            return $answer;
        });
        $question->setMaxAttempts(2);

        $name = $helper->ask($input, $output, $question);
        $output->writeln('You have just selected: ' . $name);

    }


    protected function getQuestionHelper()
    {
        $helper = $this->getHelper('question');
        return $helper;
    }
}