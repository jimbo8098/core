<?php

namespace Gibbon\Common;
class GibbonDomainDeserializer
{
	public function Deserialize($json)
	{
		if($json != null)
		{
			foreach($json as $key => $jvar)
			{
				if(array_key_exists($key,$this))
				{
					$this->{$key} = $jvar;
				}
			}
		}
		else
		{
			throw new Exception("Failed to deserialize object because no json was provided");
		}
	}
}

?>
