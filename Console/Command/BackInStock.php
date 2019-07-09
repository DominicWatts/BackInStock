<?php


namespace Xigen\BackInStock\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * BackInStock command class
 */
class BackInStock extends Command
{
    const CHECK_ARGUMENT = 'check';

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Framework\App\State
     */
    protected $state;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $dateTime;

    /**
     * @var \Xigen\BackInStock\Helper\Data
     */
    protected $helper;

    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    protected $input;

    /**
     * @var \Symfony\Component\Console\Output\OutputInterface
     */
    protected $output;

    /**
     * Undocumented function
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\App\State $state
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\State $state,
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime,
        \Xigen\BackInStock\Helper\Data $helper
    ) {
        $this->logger = $logger;
        $this->state = $state;
        $this->dateTime = $dateTime;
        $this->helper = $helper;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $this->input = $input;
        $this->output = $output;
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_GLOBAL);

        $check = $input->getArgument(self::CHECK_ARGUMENT) ?: false;
        if ($check) {
            $this->output->writeln((string) __('%1 Start Notification Process', $this->dateTime->gmtDate()));
            $this->helper->notifyInterests();
            $this->output->writeln((string) __('%1 End Notification Process', $this->dateTime->gmtDate()));
        }
    }

    /**
     * {@inheritdoc}
     * xigen:backinstock:check [--] <check>
     */
    protected function configure()
    {
        $this->setName('xigen:backinstock:check');
        $this->setDescription('Notify customers products are back in stock');
        $this->setDefinition([
            new InputArgument(self::CHECK_ARGUMENT, InputArgument::REQUIRED, 'Check')
        ]);
        parent::configure();
    }
}
