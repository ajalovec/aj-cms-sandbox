<?php
namespace Acme\FacebookBundle\Twig\Extension;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Util\Debug;
/**
 * Facebook extension
 */
class FacebookExtension extends \Twig_Extension
{
	protected $em;
	
	public function getFunctions()
    {
        return array(
            'facebook_feed_render' => new \Twig_Function_Method($this, 'facebookFeedRender'),
        );
    }
	
	public function facebookFeedRender($feedType)
	{
		$facebookFeedRepo = $this->em->getRepository('AcmeFacebookBundle:FacebookFeed');
		$feed = $facebookFeedRepo->findOneByFeedType($feedType);
		$feedArr = array();
		if($feed){			
			$feedArr['method'] = 'feed';
			$feedArr['redirect_uri'] = 'YOUR URL HERE';
			$feedArr['link'] = $feed->getLink();
			$feedArr['picture'] = $feed->getPicture();
			$feedArr['name'] = $feed->getName();
			$feedArr['caption'] = $feed->getCaption();
			$feedArr['description'] = $feed->getDescription();
			
			echo json_encode($feedArr);
		}
		
		return null;
	}
	
	public function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
	}
	
    public function getName()
    {
        return 'acme_extension';
    }
}
