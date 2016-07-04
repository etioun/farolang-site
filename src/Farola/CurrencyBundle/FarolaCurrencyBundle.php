<?php

namespace Farola\CurrencyBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FarolaCurrencyBundle extends Bundle
{
	public function getParent()
    {
        return 'LexikCurrencyBundle';
    }
}
