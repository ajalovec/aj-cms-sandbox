<?php
function extractKey(& $array, $key)
{
    if(isset($array[$key])) {
        $var = $array[$key];
        unset($array[$key]);
    }
    return $var;
}
function splitNamespace($string, $limit = 1)
{
    $delimiter = ':';
    $explode = function($_delimiter, $_string, $_limit = null) {
        return (null === $_limit ? explode($_delimiter, $_string) : explode($_delimiter, $_string, ++$_limit));
    };

    if((int) $limit < 0) {
        return (array_map('strrev', $explode($delimiter, strrev($string), abs($limit))));
    }

    return $explode($delimiter, $string, $limit);
}


$bundles2 = array(
    "new Application\Sonata\UserBundle\ApplicationSonataUserBundle()",
    "new Application\Sonata\AdminBundle\ApplicationSonataAdminBundle()",
    "new Application\Sonata\PageBundle\ApplicationSonataPageBundle()",
    "new Application\Sonata\NewsBundle\ApplicationSonataNewsBundle()",
    "new Application\Sonata\MediaBundle\ApplicationSonataMediaBundle()",
    "new Application\Sonata\NotificationBundle\ApplicationSonataNotificationBundle()",
);


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

$var = extractKey($bundles, 4);

//var_dump(splitNamespace(':', "page:container:content"));
var_dump(splitNamespace("homepage:content:container:text1", -2));
//var_dump($bundles);