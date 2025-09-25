<?php

// Abstraction
// Use interfaces or abstract classes to define 
//common methods that must be implemented by child classes.

interface CRUDInterface {

    public function create($data);

    public function read($id);

    public function update($data);

    public function delete($id);


} 

abstract class BaseModel {

    abstract protected function validate($data);

}