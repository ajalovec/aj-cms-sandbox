<?php
namespace Acme\FormBundle\Admin;
 
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class FormAdmin extends Admin
{
	
	protected $formAction = null; 
	protected $formType = null; 
	protected $translationDomain = 'ApplicationSonataAdminBundle';
	
  protected function configureFormFields(FormMapper $formMapper)
  {
    $formMapper
      ->add('action')
      ->add('type')
	  ->add('localization') 
    ;
  }
 
	public function processForm($object,FormMapper $formMapper)
	{
		
	}
 
 
  protected function configureDatagridFilters(DatagridMapper $datagridMapper)
  {
    $datagridMapper
      ->add('action')
    ;
  }
 
  protected function configureListFields(ListMapper $listMapper)
  {
    $listMapper
      //->add('actionTranslator', null, array('label' => $this->trans('Type of notice'), 'template' => 'AcmeFormBundle:List:list_show_link.html.twig'))
      ->addIdentifier('actionTranslator', null,array('label' => $this->trans('Type of notice'), 'template' => 'AcmeFormBundle:List:list_show_link.html.twig'))
      ->add('typeTranslator',null, array('label' => $this->trans('Rodzaj nieruchomości')))
	  ->add('email') 
    ;
  }
  
    protected function configureShowField(ShowMapper $show)
    {
	    $show
	      ->add('action',null, array('label' => $this->trans('Type of notice')))
	      ->add('type',null, array('label' => $this->trans('Type of property')))
		  ->add('area',null, array('label' => $this->trans('Area'))) 
		  ->add('localization',null, array('label' => $this->trans('Localization')));
		  
		switch ($this->formType) {
			case 'hause':
				$show
					->add('buildingType',null, array('label' => $this->trans('Building type')))
					->add('roomsCount',null, array('label' => $this->trans('Rooms count'))) 
					->add('priceMax',null, array('label' => $this->trans('Price max')));	
				break;
			case 'flat':
				$show
					->add('roomsCount',null, array('label' => $this->trans('Rooms count'))) 
					->add('storey',null, array('label' => $this->trans('Storey'))) 
					->add('priceMax',null, array('label' => $this->trans('Price max')))
		  			->add('blockType',null, array('label' => $this->trans('Block type'))) 
		  			->add('standard',null, array('label' => $this->trans('Standard')));
				break;
			case 'local':
				$show
					->add('roomsCount',null, array('label' => $this->trans('Rooms count'))) 
					->add('storey',null, array('label' => $this->trans('Storey'))) 
		  			->add('priceperMeterMax',null, array('label' => $this->trans('Price per meter max')));
				break;
			case 'parcel':
				$show
					->add('propertyType',null, array('label' => $this->trans('Property type'))) 
		  			->add('media',null, array('label' => $this->trans('Media')));
				break;
			case 'storage':
				$show
		  			->add('priceperMeterMax',null, array('label' => $this->trans('Price per meter max'))) 
		  			->add('loadingRamp',null, array('label' => $this->trans('Loading ramp'))) 
					->add('maneuverField',null, array('label' => $this->trans('Maneuver field')));
				break;
			default:
				
				break;
		};
	    $show
	      ->add('email')
	      ->add('phone',null, array('label' => $this->trans('Telefon')));
    } 
 
    public function getObject($id)
    {
        $object = parent::getObject($id);
		//$this->trans('Title')
		
		$this->formAction = $object->getAction();
		$this->formType = $object->getType();
		
    	$object->setAction($this->trans($object->getAction()));	
	    $object->setType($this->trans($object->getType()));	
	    $object->setArea($this->trans($object->getArea()));	
	    $object->setLocalization($this->trans($object->getLocalization()));	
	    $object->setBuildingType($this->trans($object->getBuildingType()));		
	    $object->setRoomsCount($this->trans($object->getRoomsCount()));	
		$object->setPriceMax($this->trans($object->getPriceMax()));	
		$object->setPriceperMeterMax($this->trans($object->getPriceperMeterMax()));	     	
		$object->setStorey($this->trans($object->getStorey()));	
		$object->setBlockType($this->trans($object->getBlockType()));		
		$object->setStandard($this->trans($object->getStandard()));	
		$object->setPropertyType($this->trans($object->getPropertyType()));	  
		$object->setMedia($this->trans($object->getMedia()));	
		
		$media = $object->getMedia();
		$mediaArr = explode(",", $media);
		$mediaResult = array();
		if(is_array($mediaArr)){
			foreach ($mediaArr as $key => $singleMedia) {
				$mediaResult[$key] = $this->trans($singleMedia);
			}
			$object->setMedia(implode(",", $mediaResult));
		}
		
		$object->setLoadingRamp($this->trans($object->getLoadingRamp()));	  		
		$object->setManeuverField($this->trans($object->getManeuverField()));		

		return $object;
    } 
 
  public function validate(ErrorElement $errorElement, $object)
  {
    $errorElement
      //->with('title')
      //->assertMaxLength(array('limit' => 100))
      //->end()
    ;
  }
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('create')
            ->remove('delete')
			
			->remove('edit')
            ;

    }
}