<?php

namespace Farola\CurrencyBundle\Adapter;

use Doctrine\ORM\EntityManager;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Lexik\Bundle\CurrencyBundle\Adapter\AdapterFactory as Base;

/**
 * This class is used to create DoctrineCurrencyAdapter
 *
 * @author Yoann Aparici <y.aparici@lexik.fr>
 * @author Cédric Girard <c.girard@lexik.fr>
 */
class AdapterFactory extends Base
{
   /**
     * __construct
     *
     * @param EntityManager $em
     */
    public function __construct(Registry $doctrine, $defaultCurrency, $availableCurrencies, $currencyClass)
    {
        if (sizeof($availableCurrencies) == 1)
        {
            $data = file_get_contents(__DIR__.'/../Resources/config/managed_currencies.json');
            $data = json_decode($data, true);

            $availableCurrencies = $data;
        }

        parent::__construct( $doctrine, $defaultCurrency, $availableCurrencies, $currencyClass);
    }
}