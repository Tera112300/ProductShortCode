<?php

namespace Plugin\ProductShortCode\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Product;
use Plugin\ProductShortCode\Entity\Config;

if (!class_exists('\Plugin\ProductShortCode\Entity\Option', false)) {
    /**
     * Option
     *
     * @ORM\Table(name="plg_product_short_code_option")
     * @ORM\Entity(repositoryClass="Plugin\ProductShortCode\Repository\OptionRepository")
     */
    class Option
    {
        /**
         * @var int
         *
         * @ORM\Column(name="id", type="integer", options={"unsigned":true})
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        private $id;

        /**
         * @var \Eccube\Entity\Product
         *
         * @ORM\ManyToOne(targetEntity="Eccube\Entity\Product")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
         * })
         */
        private $Product;


        /**
         * @var Plugin\ProductShortCode\Entity\Config
         *
         * @ORM\ManyToOne(targetEntity="Plugin\ProductShortCode\Entity\Config")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="config_id", referencedColumnName="id")
         * })
         */
        private $Config;

        /**
         * @var string
         *
         * @ORM\Column(name="sort_no",type="integer",nullable=true,options={"default":0})
         */
        private $sort_no;

        /**
         * @return int
         */
        public function getId()
        {
            return $this->id;
        }

        public function getProduct()
        {
            return $this->Product;
        }

        public function getConfig()
        {
            return $this->Config;
        }

        public function getSortno()
        {
            return $this->sort_no;
        }
        

        public function setProduct(Product $Product)
        {
            $this->Product = $Product;
            return $this;
        }

        public function setConfig(Config $Config)
        {
            $this->Config = $Config;
            return $this;
        }


        public function setSortno($sort_no)
        {
            $this->sort_no = $sort_no;
            return $this;
        }
    }
}
