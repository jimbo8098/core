<?php
class DatabaseHelper
{
	private $db;

	function __construct($_db)
	{
		$this->db = $_db;
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
					foreach($variables as $key => $val)
					{
						switch($key)
						{
							case "ID":
								$stmnt->bindValue($key,$val,PDO::PARAM_INT);
								break;

							default:
								$stmnt->bindValue($key,$val,PDO::PARAM_STR);
								break;
						}
					}
				}

				try
				{
					if($stmnt->execute() == true)
					{
						if($className != null)
						{
							return $stmnt->fetchAll(PDO::FETCH_CLASS,$className);
						}
						else
						{
							return $stmnt->fetchAll();
						}
					}
				}
				catch(PDOException $_e)
				{
					$e = new Exception("Failed to execute SQL due to a SQL server error");
					$e->InnerException = $_e;
					throw $e;
				}
			}
			else
			{
				throw new Exception("Failed to run SQL since the sql variable was not set");
			}
		}
		else
		{
			throw new Exception("Failed to run SQL since the PDO object for DatabaseHelper hasn't been set");
		}
	}
}
?>
