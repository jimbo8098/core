<?php
namespace Gibbon\Common;
class ActionException implements Exception
{
	private $AvailableExceptionCodes = array(
		"BAD_DATA" => "The provided data was incorrect.",
		"UNKNOWN" => "No exception code was provided or the provided code was unknown"
	);

	private $ExceptionCode;
	private $InnerException;

	function __construct($innerException = null)
	{
		if($innerException != null)
		{
			$this->InnerException = $innerException;
		}
	}

	public function SetEnum($enumString)
	{
		$_eCode = $this->AvailableExceptionCodes[$enumString;
		if($_eCode != null)
		{
			$this->ExceptionCode = $_eCode;
		}
		else
		{
			$this->ExceptionCode = $this->AvailableExceptionCodes["UNKNOWN"];
		}
	}
}

?>
