<?php

namespace djeager;

class ActiveRecord extends \yii\db\ActiveRecord
{
    const EVENT_BEFORE_LOAD = 'before_load';
    const EVENT_AFTER_LOAD = 'after_load';

    public $load;

    public function __get($name)
    {
        if ($this instanceof AliasInterface && ($a = $this->alias($name)) !== false) return $a;
        else return parent::__get($name);
    }

    public function runScenario()
    {
        return $this->{"scenario" . ucfirst($this->getScenario())}();
    }

    /**
     * @param array
     * @param boolean
     * @param boolean $replace value if exist attribute
     * @see http://www.yiiframework.com/doc-2.0/yii-base-model.html#setAttributes()-detail
     */
    public function setAttributes($values, $safeOnly = true, $replace = true)
    {
        if (!$replace) {
            $v = array_diff($this->getAttributes(null, [], false), $this->getAlias());
            $values = array_diff_key($values, $v);
        }

//        $attr = array_intersect_key((array)$values, ($a = $this->getAlias()));
//        foreach ($attr as $k => $v) {
//            $this->{$a[$k]} = $v;
//            unset($values[$k]);
//        }

        return parent::setAttributes($values, $safeOnly);
    }

    /**
     * Returns attribute values.
     * @param array $names list of attributes whose value needs to be returned.
     * Defaults to null, meaning all attributes listed in [[attributes()]] will be returned.
     * If it is an array, only the attributes in the array will be returned.
     * @param array $except list of attributes whose value should NOT be returned.
     * @param bool $withEmpty return emty attributes
     * @return array attribute values (name => value).
     */
    public function getAttributes($names = null, $except = [], $withEmpty = true)
    {
        $values = [];
        if ($names === null) {
            $names = $this->attributes();
        }
        foreach ($names as $name) {
            if (!$withEmpty && in_array($this->$name, ['', null, false])) continue;
            $values[$name] = $this->$name;
        }
        foreach ($except as $name) {
            unset($values[$name]);
        }
        return $values;
    }


    public function load($data, $formName = null):bool
    {
        $this->load = $data;
        $this->trigger(self::EVENT_BEFORE_LOAD);
        $return = parent::load($data, $formName);
        $this->trigger(self::EVENT_AFTER_LOAD);
        $this->load = null;
        return $return;
    }
}
    
    