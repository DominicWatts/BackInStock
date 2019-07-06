<?php


namespace Xigen\BackInStock\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Data helper class
 */
class Data extends AbstractHelper
{
    const CUSTOMER_HAS_NOTIFIED = 1;
    const CUSTOMER_NOT_NOTIFIED = 0;
    const CUSTOMER_FAIL_NOTIFIED = 2;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepositoryInterface;

    /**
     * @var \Xigen\BackInStock\Model\InterestFactory
     */
    protected $interestFactory;

    /**
     * @var \Magento\CatalogInventory\Api\StockStateInterface
     */
    protected $stockItem;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepositoryInterface,
        \Xigen\BackInStock\Model\InterestFactory $interestFactory,
        \Magento\CatalogInventory\Api\StockStateInterface $stockItem
    ) {
        $this->logger = $logger;
        $this->productRepositoryInterface = $productRepositoryInterface;
        $this->interestFactory = $interestFactory;
        $this->stockItem = $stockItem;
        parent::__construct($context);
    }

    /**
     * Get product by Id
     * @param int $productId
     * @param bool $editMode
     * @param int $storeId
     * @param bool $forceReload
     * @return \Magento\Catalog\Model\Data\Product
     */
    public function getProductById($productId, $editMode = false, $storeId = null, $forceReload = false)
    {
        try {
            return $this->productRepositoryInterface->getById($productId, $editMode, $storeId, $forceReload);
        } catch (\Exception $e) {
            $this->logger->critical($e);
            return false;
        }
    }

    /**
     * Run notify interests process
     * @return void
     */
    public function notifyInterests()
    {
        $collection = $this->interestFactory
            ->create()
            ->getCollection()
            ->addFieldToFilter('has_notified', ['neq' => self::CUSTOMER_HAS_NOTIFIED]);

        $checksCollection->setPageSize(100);
        $pages = $checksCollection->getLastPageNumber();
        $currentPage = 1;
        do {
            $checksCollection->setCurPage($currentPage);
            $checksCollection->load();
            foreach ($checksCollection as $check) {
                $product = $this->getProductById($check->getProductId());
                
                $send = false;
                $save = false;

                if ($product && $product->getId()) {
                    $stock = $this->stockItem->getStockQty($product->getId(), $product->getStore()->getWebsiteId());
                    if ($stock && (int) $stock->getQty() > 0) {
                        try {
                            $this->sendTransactionalEmail([
                                'firstname' => $check->getName(),
                                'email' => $check->getEmail(),
                                'productname' => $product->getName(),
                                'productlink' => $product->getProductUrl(),
                            ]);
                            $check->setHasNotified(self::CUSTOMER_HAS_NOTIFIED);
                            $save = true;
                        } catch (Exception $e) {
                            $this->logger->critical($e);
                            $check->setHasNotified(self::CUSTOMER_FAIL_NOTIFIED);
                            $save = true;
                        }
                    }
                } else {
                    $check->setHasNotified(self::CUSTOMER_FAIL_NOTIFIED);
                    $save = true;
                }
                
                if ($save) {
                    try {
                        $check->save();
                    } catch (Exception $e) {
                        $this->logger->critical($e);
                    }
                }
            }
            $currentPage++;
            //clear collection and free memory
            $checksCollection->clear();
        } while ($currentPage <= $pages);
    }

    public function sendTransactionalEmail($vars = [])
    {
        var_dump($vars);
    }
}
