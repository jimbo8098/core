<?php 

namespace Gibbon\Tables\Columns;
use Gibbon\Tables\DataTable;
use Gibbon\Services\Format;

class AvailabilityColumn extends Column
{
  public function __construct($id, $name)
  {
    parent::__construct($id,$name);
    $this
      ->sortable(false)
      ->width('40%');
  }

  public function getOutput(&$data = array())
  {
    return parent::getOutput($data);
  }
}

?>
