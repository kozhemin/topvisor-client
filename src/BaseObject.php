<?php

namespace kozhemin\TopVisor;

/**
 * Class BaseObject
 * @package kozhemin\TopVisor
 */
class BaseObject
{
    /**
     * @var null|Connection
     */
    protected $topVisor = null;
    protected $object = null;

    public function __construct($json = null, Connection $topVisor = null)
    {
        $this->topVisor = $topVisor;
        $this->object = $json;
    }

    /**
     * @param $name
     *
     * @return mixed
     * @throws \Exception
     */
    protected function guessAttributeName($name)
    {
        $name = (string)$name;
        $oName = $name;

        if (isset($this->object->$name)) {
            return $this->object->$name;
        }
        $name = lcfirst($name);
        if (isset($this->object->$name)) {
            return $this->object->$name;
        }
        $name = ucfirst($name);
        if (isset($this->object->$name)) {
            return $this->object->$name;
        }

        throw new \Exception("attribute '{$oName}' not defined. Specify it in the parameter fields");
    }

    /**
     * @param $name
     *
     * @return mixed|null
     * @throws \Exception
     */
    public function __get($name)
    {
        $attribute = $this->guessAttributeName($name);
        if (isset($attribute)) {
            return $attribute;
        }
        return null;
    }

    /**
     * @param $name
     * @param $args
     *
     * @return mixed
     * @throws \Exception
     */
    public function __call($name, $args)
    {
        // Magic getter
        if (strlen($name) > 3 && substr($name, 0, 3) === 'get') {
            $attribute = $this->guessAttributeName(substr($name, 3));
            if (isset($attribute)) {
                return $attribute;
            }
        }
    }

}
