<?php

namespace djeager;

class ActiveRecord extends \yii\db\ActiveRecord
{
    public function runScenario()
    {
        return $this->{"scenario" . ucfirst($this->getScenario())}();
    }
}
    
    