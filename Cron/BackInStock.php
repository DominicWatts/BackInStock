<?php


namespace Xigen\BackInStock\Cron;

/**
 * BackInStock cron class
 */
class BackInStock
{
    protected $logger;

    /**
     * Constructor
     *
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(\Psr\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Execute the cron
     *
     * @return void
     */
    public function execute()
    {
        $this->logger->addInfo("Cronjob BackInStock is executed.");
    }
}
