<?php

namespace Acme\PageBundle\Routing;

use Symfony\Cmf\Component\Routing\ContentRepositoryInterface;
//use Doctrine\ODM\PHPCR\DocumentManager;

/**
 * Implement ContentRepositoryInterface for phpcr-odm
 *
 * @author Uwe Jäger
 */
class ContentRepository implements ContentRepositoryInterface
{
    /** @var DocumentManager */
    protected $documentManager;

    /**
     * @param DocumentManager $documentManager
     */
    //public function __construct(DocumentManager $documentManager)
    //{
     //   $this->documentManager = null/*$documentManager*/;
    //}

    /**
     * Return a content object by it's id or null if there is none.
     *
     * @param $id mixed id of the content object
     * @return mixed
     */
    public function findById($id)
    {
        return null;
    }
	public function getContentId($content)
    {
        return null;
    }

}
