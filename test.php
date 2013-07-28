<?php

function getArray()
{
    return array(
        "new Application\Sonata\UserBundle\ApplicationSonataUserBundle()",
        "new Application\Sonata\AdminBundle\ApplicationSonataAdminBundle()",
        "new Application\Sonata\PageBundle\ApplicationSonataPageBundle()",
        "new Application\Sonata\NewsBundle\ApplicationSonataNewsBundle()",
        "new Application\Sonata\MediaBundle\ApplicationSonataMediaBundle()",
        "new Application\Sonata\NotificationBundle\ApplicationSonataNotificationBundle()",
    );
}


$bundles = array(
    "new Symfony\Bundle\FrameworkBundle\FrameworkBundle()",
    "new Symfony\Bundle\SecurityBundle\SecurityBundle()",
    "new Symfony\Bundle\TwigBundle\TwigBundle()",
    "new Symfony\Bundle\MonologBundle\MonologBundle()",
    "new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle()",
    "new Symfony\Bundle\AsseticBundle\AsseticBundle()",
    "new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle()",
    "new JMS\AopBundle\JMSAopBundle()",
    "new JMS\SecurityExtraBundle\JMSSecurityExtraBundle()",
);

$a = array_merge($bundles, getArray());
$b = "sonata.page.sdsa.sadas";
$c = ltrim($b, "sonata.");
var_dump($c);