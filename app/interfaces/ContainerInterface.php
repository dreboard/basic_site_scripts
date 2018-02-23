<?php

namespace App\Main;


interface ContainerInterface
{

    /**
     * get the object from the container.
     * @param $id
     * @return mixed
     */
    public function get($id);

    /**
     * check if we have the object in the container.
     * @param $id
     * @return mixed
     */
    public function has($id);
}