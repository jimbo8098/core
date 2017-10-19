<?php
namespace Gibbon\Common;
//Implementing standard CRUD functions
interface GibbonCRUDAPI
{
	public function GetAll();
	public function GetByID($id);
	public function Add($obj);
	public function Delete($obj);
	public function Edit($obj);
}

?>
