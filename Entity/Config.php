<?php

namespace Plugin\ProductShortCode\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

if (!class_exists('\Plugin\ProductShortCode\Entity\Config', false)) {
    /**
     * Config
     *
     * @ORM\Table(name="plg_product_short_code_config")
     * @ORM\Entity(repositoryClass="Plugin\ProductShortCode\Repository\ConfigRepository")
     */
    class Config
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
         * @var string
         *
         * @ORM\Column(name="name", type="string", length=255)
         */
        private $name;

        /**
         * @var string
         *
         * @ORM\Column(name="content",type="text",nullable=true)
         */
        private $content;

        /**
         * @ORM\OneToMany(targetEntity="Plugin\ProductShortCode\Entity\Option", mappedBy="Config", cascade={"persist", "remove"})
         * @ORM\OrderBy({"sort_no" = "ASC"})
         */
        private $Options;

        public function __construct()
        {
            $this->Options = new ArrayCollection();
        }


        /**
         * @return int
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * @return string
         */
        public function getName()
        {
            return $this->name;
        }

        public function getContent()
        {
            return $this->content;
        }
        public function getOptions(): Collection
        {
            return $this->Options;
        }

        /**
         * @param string $name
         *
         * @return $this;
         */
        public function setName($name)
        {
            $this->name = $name;

            return $this;
        }

        public function setContent($content)
        {
            $this->content = $content;

            return $this;
        }

        public function addOption(Option $Option): self
        {
            if (!$this->Options->contains($Option)) {
                $this->Options[] = $Option;
                $Option->setProduct($this);
            }
            return $this;
        }

        public function removeOption(Option $Option): self
        {
            if ($this->Options->contains($Option)) {
                $this->Options->removeElement($Option);
                // set the owning side to null (unless already changed)
                if ($Option->getProduct() === $this) {
                    $Option->setProduct(null);
                }
            }
            return $this;
        }

        public function __toString()
        {
            return (string) $this->getName();
        }

    }
}
