<?php

namespace Acme\TinyMceBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AcmeTinyMceBundle extends Bundle
{
	public function getParent()
    {
        return 'StfalconTinymceBundle';
    }
}
