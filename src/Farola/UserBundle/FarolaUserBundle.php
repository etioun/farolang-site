<?php

namespace Farola\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class FarolaUserBundle extends Bundle
{
	public function getParent()
    {
        return 'FOSUserBundle';
    }
}
