<?php
namespace xakki\yii2-ar-json\components;


class ActiveRecordJson extends \yii\db\ActiveRecord
{
    protected $_json_attribute = false;
    protected $_json_catch = [];

    public function getDirtyAttributes($names = null)
    {
        $values = parent::getDirtyAttributes($names);

        if ($this->_json_attribute && !empty($values)) {
            $data = [];
            foreach ($this->_json_catch as $attr) {
                $data[$attr] = $this->getAttribute($attr);
                if (isset($values[$attr])) unset($values[$attr]);
            }

            $c1 = count($data);
            if ($c1) {
                if ($c1!=count($this->_json_catch))
                    trigger_error('Wrong json data : ', E_USER_WARNING);
                $values[$this->_json_attribute] = $data;
            }
        }
        return $values;
    }

    public function afterFind()
    {
        if ($this->_json_attribute) {
            $data = $this->getAttribute($this->_json_attribute);
            foreach ($this->_json_catch as $attr) {
                if ($data && isset($data[$attr])) {
                    $this->setOldAttribute($attr, $data[$attr]);
                    $this->setAttribute($attr, $data[$attr]);
                } elseif ($this->hasAttribute($attr)) {
                    $this->setOldAttribute($attr, null);
                    $this->setAttribute($attr, null);
                }
            }
        }
        parent::afterFind();
    }

    public function hasAttribute($name)
    {
        if (in_array($name, $this->_json_catch)) return true;
        return parent::hasAttribute($name);
    }

    public function load($data, $formName = null) {
        $flag = parent::load($data, $formName);
        if (!$flag && $this->isNewRecord) {
            $this->setAttributeDefault();
        }
        return $flag;
    }

    public function setAttributeDefault() {
        // $this->setAttribute('name', 'value');
    }
}
