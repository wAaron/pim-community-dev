<?php

namespace Acme\Bundle\AppBundle\Model;

use Acme\Bundle\AppBundle\Entity\Color;
use Pim\Bundle\CatalogBundle\Model\AbstractProduct;

/**
 * Product, business code is in AbstractProduct, this class is overriden with the add of the adeo product extension
 */
class Product extends AbstractProduct
{

    /** @var  Color */
    protected $productExtension;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->productExtension = new Color();
    }

    /**
     * @return Color
     */
    public function getProductExtension()
    {
        return $this->productExtension;
    }

    /**
     * @param Color $productExtension
     */
    public function setProductExtension($productExtension)
    {
        $this->productExtension = $productExtension;
    }
}
