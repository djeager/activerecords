<?php
namespace djeager;


interface AliasInterface
{

    /**
     * @return array
     */
    public function aliases();


    /**
     * @param string $name
     * @return mixed
     */
    public function alias($name);
}