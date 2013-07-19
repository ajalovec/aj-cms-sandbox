<?php
//Acme\EmailBundle\Event\EmailSendEvent.php
namespace Acme\EmailBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class EmailSendEvent extends Event
{
    protected $type;
	protected $from;
	protected $to;

    public function __construct($type, $from, $to)
    {
    	$this->type = $type;
		$this->from = $from;
		$this->to = $to;
    }

    public function getType()
    {
        return $this->type;
    }
	
    public function getOne($emails)
    {
    	if(is_array($emails)){
			foreach ($emails as $address => $name)
			{
			  if (is_int($address)) {
				  return $name;
			  } else {
			      return $address;
			  }
			  break;
			}
    	}
		return null;
    }
	
    public function getOneFrom()
    {
        return $this->getOne($this->from);
    }
    public function getOneTo()
    {
        return $this->getOne($this->to);
    }
	
    public function getFrom()
    {
        return $this->from;
    }
    public function getTo()
    {
        return $this->to;
    }
}