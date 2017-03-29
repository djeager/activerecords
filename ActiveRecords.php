<?php

namespace djeager;

class ActiveRecords extends \yii\db\ActiveRecord
{
    public function runScenario()
    {
        return $this->{"scenario" . ucfirst($this->getScenario())}();
    }
}
    
    