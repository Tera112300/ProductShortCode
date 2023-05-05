<?php
namespace Plugin\ProductShortCode\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\Environment;
use Plugin\ProductShortCode\Repository\ConfigRepository;
use Twig\Loader\ArrayLoader;
use Twig\TwigFilter;
use Eccube\Util\StringUtil;
use Eccube\Common\EccubeConfig;

use Symfony\Component\Asset\Packages;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class EccubeExtension extends AbstractExtension
{
    protected $eccubeConfig;
    protected $ConfigRepository;

    private $packages;
    private $generator;
    public function __construct(
    EccubeConfig $eccubeConfig,
    ConfigRepository $ConfigRepository,
    Packages $packages,
    UrlGeneratorInterface $generator
    ){
        $this->eccubeConfig = $eccubeConfig;
        $this->ConfigRepository = $ConfigRepository;
        $this->packages = $packages;
        $this->generator = $generator;
    }
    public function getFunctions()
    {
        return [
            new TwigFunction('getProductShortCode',[$this,'getProductShortCode']),
        ];
    }

  
    public function getProductShortCode(int $id = 0)
    {
        $Config = $this->ConfigRepository->get($id);
        if($Config && !empty($Config->getContent())){
            $loader = new ArrayLoader(['template' => $Config->getContent()]);
            $twig = new Environment($loader);
            
            $twig->addExtension(new EccubeExtension($this->eccubeConfig,$this->ConfigRepository,$this->packages,$this->generator));
         


            $twig->addFunction(new TwigFunction('asset', function (string $path, string $packageName = null) {
                return $this->getAssetUrl($path,$packageName);
            }));

            $twig->addFunction(new TwigFunction('url', function (string $name, array $parameters = [], bool $schemeRelative = false) {
                return $this->getUrl($name,$parameters,$schemeRelative);
            }));



            foreach($this->getFilters() as $filter){
                $twig->addFilter($filter);
            }
        
            return $twig->render('template', [
            'ProductShortCodes' => $Config->getOptions(),
            ]);
        }
        return false;
    }


    public function getAssetUrl(string $path, string $packageName = null): string
    {
        return $this->packages->getUrl($path, $packageName);
    }

    public function getUrl(string $name, array $parameters = [], bool $schemeRelative = false): string
    {
        return $this->generator->generate($name, $parameters, $schemeRelative ? UrlGeneratorInterface::NETWORK_PATH : UrlGeneratorInterface::ABSOLUTE_URL);
    }

    
    public function getFilters()
    {
        return [
            new TwigFilter('no_image_product', [$this,'getNoImageProduct']),
            new TwigFilter('date_format', [$this,'getDateFormatFilter']),
            new TwigFilter('price', [$this,'getPriceFilter']),
            new TwigFilter('ellipsis', [$this,'getEllipsis']),
            new TwigFilter('time_ago', [$this,'getTimeAgo'])
        ];
    }

    public function getNoImageProduct($image)
    {
        return empty($image) ? 'no_image_product.png' : $image;
    }


    public function getDateFormatFilter($date, $value = '', $format = 'Y/m/d')
    {
        if (is_null($date)) {
            return $value;
        } else {
            return $date->format($format);
        }
    }


    public function getPriceFilter($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $locale = $this->eccubeConfig['locale'];
        $currency = $this->eccubeConfig['currency'];
        $formatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);

        return $formatter->formatCurrency($number, $currency);
    }

    public function getEllipsis($value, $length = 100, $end = '...')
    {
        return StringUtil::ellipsis($value, $length, $end);
    }

    public function getTimeAgo($date)
    {
        return StringUtil::timeAgo($date);
    }
}