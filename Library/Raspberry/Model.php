<?php
/**
 *
 *
 * @package
 * @subpackage
 * @author     Dmytro Denysov dmytro.denysov@racingpost.com
 * @copyright  2016 Racing Post
 */

namespace Raspberry;


class Model extends Container
{
    protected $scaffold;

    /**
     * @return mixed
     */
    public function getScaffold()
    {
        return $this->scaffold;
    }

    /**
     * @param mixed $scaffold
     */
    public function setScaffold($scaffold)
    {
        $this->scaffold = $scaffold;
    }

    public function getData()
    {
        $data = [];

        foreach ($this->scaffold as $fieldName => $value) {
            $data[$fieldName] = $this->get($fieldName);
        }

        return $data;
    }


}