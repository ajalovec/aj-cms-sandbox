<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function init()
    {
        // Please read http://symfony.com/doc/2.0/book/installation.html#configuration-and-setup
        umask(0002);

        ini_set('date.timezone', 'Europe/Ljubljana');
        ini_set('xdebug.max_nesting_level', 1000);
        parent::init();

    }

    protected function getKernelParameters()
    {
        $params = parent::getKernelParameters();
        $params['base_path'] = dirname($_SERVER['SCRIPT_NAME']);
        
        return $params;
    }

    public function registerBundles()
    {


        /*
         * Bundle core
         */
        $bundles = array(
            // SYMFONY STANDARD EDITIONEurope/Ljubljana
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),

            // DOCTRINE
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            
            // SONATA CORE & HELPER BUNDLES
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(),
            new Sonata\EasyExtendsBundle\SonataEasyExtendsBundle(),
            new Sonata\IntlBundle\SonataIntlBundle(),
            new Sonata\FormatterBundle\SonataFormatterBundle(),
            new Sonata\CacheBundle\SonataCacheBundle(),
            
            /*
             * Bundle framework
             */
            new Sonata\UserBundle\SonataUserBundle('FOSUserBundle'),
            new FOS\UserBundle\FOSUserBundle(),
            new Application\Sonata\UserBundle\ApplicationSonataUserBundle(),
            
            new Sonata\BlockBundle\SonataBlockBundle(),
            new Sonata\SeoBundle\SonataSeoBundle(),
            
            new Sonata\AdminBundle\SonataAdminBundle(),
            new Application\Sonata\AdminBundle\ApplicationSonataAdminBundle(),
            new Sonata\NotificationBundle\SonataNotificationBundle(),
            new Application\Sonata\NotificationBundle\ApplicationSonataNotificationBundle(),
            
            // PAGE
            new Sonata\PageBundle\SonataPageBundle(),
            new Application\Sonata\PageBundle\ApplicationSonataPageBundle(),

            
            // Enable this if you want to audit backend action
            //new SimpleThings\EntityAudit\SimpleThingsEntityAuditBundle(),

            // CMF Integration
            new Symfony\Cmf\Bundle\RoutingBundle\CmfRoutingBundle(),


            //new AJ\Template\AssetsBundle\AJTemplateAssetsBundle(),
            new AJ\Template\ComponentBundle\AJTemplateComponentBundle(),
            //new AJ\Template\LayoutBundle\AJTemplateLayoutBundle(),
            new AJ\Template\BootstrapBundle\AJTemplateBootstrapBundle(),
        );
        
        $moduleBundles = array(
            // NOVICE
            //new Sonata\NewsBundle\SonataNewsBundle(),
            //new Application\Sonata\NewsBundle\ApplicationSonataNewsBundle(),

            // MEDIA
            new Sonata\MediaBundle\SonataMediaBundle(),
            new Application\Sonata\MediaBundle\ApplicationSonataMediaBundle(), 
            
            // CONTENT
            new Acme\ContentBundle\AcmeContentBundle(),
            //new Acme\TestBundle\AcmeTestBundle(),
            //new Acme\PageBundle\AcmePageBundle(),
            //new Acme\TreeBundle\AcmeTreeBundle(),
            //new Acme\TinyMceBundle\AcmeTinyMceBundle(),
            //new Acme\NewsletterBundle\AcmeNewsletterBundle(),
            //new Acme\FormBundle\AcmeFormBundle(),
            //new Acme\FancyBundle\AcmeFancyBundle(),
            //new Acme\EmailBundle\AcmeEmailBundle(),
            //new Acme\ContactBundle\AcmeContactBundle(),
            //new Acme\ElFinderBundle\AcmeElFinderBundle(),
            //new Acme\UserBundle\AcmeUserBundle(),
            //new Acme\AssortmentBundle\AcmeAssortmentBundle(),
        );

        /*
         * Bundle frontend
         */
        $frontendBundles = array(
            // new Liip\ImagineBundle\LiipImagineBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Knp\Bundle\MarkdownBundle\KnpMarkdownBundle(),
            new Sonata\MarkItUpBundle\SonataMarkItUpBundle(),
            new Ivory\CKEditorBundle\IvoryCKEditorBundle(),

            new Sonata\jQueryBundle\SonatajQueryBundle(),
        );

        /*
         * Bundle application
         */
        $applicationBundles = array(
            // CUSTOM BUNDLES
            new JMI\SiteBundle\JMISiteBundle(),
            
        );
        
        $bundles = array_merge(
            $bundles
          , $applicationBundles
          , $frontendBundles
          , $moduleBundles
        );



        if (in_array($this->getEnvironment(), array('prod'))) {
            $bundles[] = new SimpleThings\EntityAudit\SimpleThingsEntityAuditBundle();
        }
        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Bazinga\Bundle\FakerBundle\BazingaFakerBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
            $bundles[] = new Hautelook\AliceBundle\HautelookAliceBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
