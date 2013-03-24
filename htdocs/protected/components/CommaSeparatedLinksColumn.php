<?php

class CommaSeparatedLinksColumn extends CDataColumn
{
    public $separator = ', ';

    protected function renderDataCellContent($row, $data)
    {
        $list = explode(',', $data[$this->name]);
        foreach ($list as $i => $token) {
            if($this->value!==null)
                $value=$this->evaluateExpression($this->value,array('data'=>$data,'row'=>$row,'token' => $token));
            else if($this->name!==null)
                $value=CHtml::value($data,$this->name);
            echo $value===null ? $this->grid->nullDisplay : $this->grid->getFormatter()->format($value,$this->type);

            if ($i < (count($list) - 1))
                echo $this->separator;
        }
    }
}
