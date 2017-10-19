<?php
namespace Gibbon\Common;
class DatabaseHelper
{
	private $db;

	function __construct($_db)
	{
		$this->db = $_db;
	}

	public function InsertSQL($dataobj)
	{
		try
		{
			return $this->RunSQL($dataobj['SQL'],"NORETURN",$dataobj['VARS']);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	public function DeleteSQL($dataobj)
	{
		try
		{
			return $this->RunSQL($dataobj['SQL'],"NORETURN",$dataobj['VARS']);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	public function UpdateSQL($dataobj)
	{
		try
		{
			return $this->RunSQL($dataobj['SQL'],"NORETURN",$dataobj['VARS']);
		}
		catch(Exception $e)
		{
			throw $e;
		}
	}

	private function bindVars($stmnt,$vars)
	{
		foreach($vars as $key => $val)
		{
			switch($key)
			{
				case "ID":
					$stmnt->bindValue($key,$val,\PDO::PARAM_INT);
					break;

				default:
					$stmnt->bindValue($key,$val,\PDO::PARAM_STR);
					break;
			}
		}
		return $stmnt;
	}

	public function RunSQL($sql,$className,$variables)
	{
		if($this->db != null)
		{
			if($sql != null)
			{
				$stmnt = $this->db->prepare($sql);
				if($variables != null)
				{
					$this->bindVars($stmnt,$variables);
				}

				try
				{
					if($stmnt->execute() == true)
					{
						if($className != null)
						{
							if($className == "NORETURN")
							{
								return true;
							}
							else
							{
								return $stmnt->fetchAll(\PDO::FETCH_CLASS,$className);
							}
						}
						else
						{
							return $stmnt->fetchAll();
						}
					}
				}
				catch(\PDOException $e)
				{
					throw $e;
				}
			}
			else
			{
				throw new \Exception("Failed to run SQL since the sql variable was not set");
			}
		}
		else
		{
			throw new \Exception("Failed to run SQL since the PDO object for DatabaseHelper hasn't been set");
		}
	}
}
?>
