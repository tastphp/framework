<?php

namespace TastPHP\Framework\Debug\Collector;

class SqlCollector extends \DebugBar\DataCollector\ConfigCollector
{
    protected $name;

    protected $data;

    /**
     * @param array  $data
     * @param string $name
     */
    public function __construct(array $data = array(), $name = 'sql')
    {
        $this->name = $name;
    }

    /**
     * Sets the data
     *
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data[] = $data;
    }

    /**
     * @return array
     */
    public function collect()
    {
        $data = array();

        foreach ($this->data as $k => $v) {
            $data[$k.":".$v[1]] = "执行时间:".$v[0]." SQL语句:".$v[2]." 详情:".$this->getDataFormatter()->formatVar($v);
        }
        return $data;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getWidgets()
    {
        $name = $this->getName();
        return array(
            "$name" => array(
                "icon" => "gear",
                "widget" => "PhpDebugBar.Widgets.VariableListWidget",
                "map" => "$name",
                "default" => "{}"
            )
        );
    }
}
