<?php


namespace Xigen\BackInStock\Cron;

/**
 * BackInStock cron class
 */
class BackInStock
{
    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    protected $logger;

    /**
     * @param \Xigen\BackInStock\Helper\Data $helper
     */
    protected $helper;

    /**
     * Constructor
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Xigen\BackInStock\Helper\Data $helper
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Xigen\BackInStock\Helper\Data $helper
    ) {
        $this->logger = $logger;
        $this->helper = $helper;
    }

    /**
     * Execute the cron
     * @return void
     */
    public function execute()
    {
        $this->logger->addInfo("Cronjob Back In Stock is executed.");
        $this->helper->notifyInterests();
        $this->logger->addInfo("Cronjob Back In Stock is finished.");
    }
}
