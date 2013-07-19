<?php
namespace Acme\ElFinderBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AcmeElFinderBundle extends Bundle
{
    public function getParent()
    {
        return 'FMElfinderBundle';
    }
}
