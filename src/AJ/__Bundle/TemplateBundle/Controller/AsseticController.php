<?php

//namespace AJ\Bundle\TemplateBundle\Controller\AsseticController;
namespace AJ\Bundle\TemplateBundle\Controller;

use Symfony\Bundle\AsseticBundle\Controller\AsseticController as BaseAsseticController;


class AsseticController extends BaseAsseticController
{
    
    public function render($name, $pos = null)
    {
        if (!$this->enableProfiler && null !== $this->profiler) {
            $this->profiler->disable();
        }

        if (!$this->am->has($name)) {
            throw new NotFoundHttpException(sprintf('The "%s" asset could not be found.', $name));
        }

        $asset = $this->am->get($name);
        if (null !== $pos && !$asset = $this->findAssetLeaf($asset, $pos)) {
            throw new NotFoundHttpException(sprintf('The "%s" asset does not include a leaf at position %d.', $name, $pos));
        }

        $bustCache = preg_match('/\.(scss|sass|less)$/', $asset->getSourcePath());

        $response = $this->createResponse();
        $response->setExpires(new \DateTime());

        if ($bustCache) {
            $lastModified = time();
            $date = new \DateTime();
            $date->setTimestamp($lastModified);
            $response->setLastModified($date);
        }
        else
        {
            // last-modified
            if (null !== $lastModified = $asset->getLastModified()) {
                $date = new \DateTime();
                $date->setTimestamp($lastModified);
                $response->setLastModified($date);
            }
        }

        // etag
        if ($this->am->hasFormula($name)) {
            $formula = $this->am->getFormula($name);
            $formula['last_modified'] = $lastModified;
            $response->setETag(md5(serialize($formula)));
        }

        if ($response->isNotModified($this->request)) {
            return $response;
        }

        if ($bustCache) {
            $response->setContent($asset->dump());
        }
        else {
            $response->setContent($this->cachifyAsset($asset)->dump());
        }

        return $response;
    }

    private function findAssetLeaf(\Traversable $asset, $pos)
    {
        $i = 0;
        foreach ($asset as $leaf) {
            if ($pos == $i++) {
                return $leaf;
            }
        }
    }
    
}
