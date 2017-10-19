<?php
namespace Gibbon\Common;
interface GibbonDataObject
{
	public function GetInsert();
	public function GetUpdate();
	public function GetDelete();
	public function GetID();
}

?>
