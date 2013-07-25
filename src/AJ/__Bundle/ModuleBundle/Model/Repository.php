<?php

namespace AJ\Bundle\ModuleBundle\Model;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormInterface;
//use Symfony\Component\HttpFoundation\Request;
use AJ\Component\HttpFoundation\Params;


class Repository extends EntityRepository implements ContainerAwareInterface
{
	protected $parameters;
	protected $container;
	
	function setContainer(ContainerInterface $container = null)
	{
		$this->container    = $container;
		$this->formFactory 	= $container->get('form.factory');
		$this->parameters	= new Params();
		
	}
	
	/**
	 * Creates and returns a Form instance from the type of the form.
	 *
	 * @param string|FormTypeInterface $type    The built type of the form
	 * @param mixed                    $data    The initial data for the form
	 * @param array                    $options Options for the form
	 *
	 * @return Form
	 */
	public function createForm($type, $data = null, array $options = array())
	{
		return $this->getService('form.factory')->create($type, $data, $options);
	}
	
	
	public function createCriteria()
	{
		$criteria = Criteria::create();
	}
	
	public function newEntity()
	{
		$class = $this->getEntityName();
		return new $class();
	}
	
	public function newFormType($name = '')
	{
		$entityName = $this->getEntityName();
		$formNamespace = str_replace('Bundle\\Entity', 'Bundle\\Form', $entityName);
		$formName = $formNamespace . ucfirst($name) . 'Type'; 
		
		return new $formName();
	}
	
	public function newFormModel()
	{
		$entityName = $this->getEntityName();
		$formName = str_replace('Bundle\\Entity', 'Bundle\\Form\\Model', $entityName);
		//die("Pokazi form name".$formName);
		
		return new $formName();
	}
	
	
	public function delete($id = null)
	{
		$data = is_scalar($id) ? $this->find($id) : $id;
		
		if($data)
		{
			$this->_em->remove($data);
			$this->_em->flush();
			return true;
		}
		
		return false;
	}

	/**
	 * 
	 * @param FormInterface $form
	 * @param unknown_type $request
	 * @return boolean
	 * 
	 * Metoda, ki shrani formo v bazo preko doctrine sistema
	 */
	public function save($data)
	{
		if($data instanceof FormInterface && $data->isValid())
		{
			$data = $data->getData();
		}

		if($data instanceof \AJ\Component\Entity\Entity)
		{
			$this->_em->persist($data);
			$this->_em->flush();

			return true;
		}

		return false;
	}
	
	

	
}