<?php

namespace Nether;
use \Nether;

////////////////
////////////////

if(class_exists('Nether\Ki'))
Ki::Queue('nether-shutdown',function(){
/*//
on system shutdown run through all the objects stored in the stash and execute
their Shutdown method if they have one. this will allow things to clean up and
store anything last minute if they need it.
//*/

	foreach(array_keys(Stash::$Instances) as $key) {
		if(method_exists(Stash::$Instances[$key],'Shutdown'))
		Stash::$Instances[$key]->Shutdown();
	}

	return;
});

////////////////
////////////////

final class Stash {
/*//
a static singleton class for passing objects throughout the current instance of
the application. the stash is designed to hold objects of importance that should
only ever exist once (e.g. singletons).
//*/

	static $Instances = array();
	/*//
	@type array
	an array indexing all the data stored by the stash.
	//*/

	////////////////
	////////////////

	public function
	__construct() { return; }

	////////////////
	////////////////

	static function
	Get($key) {
	/*//
	@argv string Key
	@return mixed
	retrieve a specific value from the stash.
	//*/

		if(self::Has($key)) return self::$Instances[$key];
		else return null;
	}

	static function
	Has($key):
	Bool {
	/*//
	@argv string Key
	@return boolean
	check if there is already a key in the stash with this name.
	//*/

		if(array_key_exists($key,self::$Instances)) return true;
		else return false;
	}

	static function
	Set($key,$obj,$overwrite=false) {
	/*//
	@argv string Key, mixed Data, boolean Overwrite default false
	@return mixed
	store data under a specific key in the stash. by default the stash will
	not overwrite anything already stored under that key and will throw an
	exception if you try.
	//*/

		// unless explicitly stated do not overwrite existing objects
		// by default. since the idea here is a singleton manager.
		if(!$overwrite && self::Has($key))
		throw new \Exception("already have an object named {$key}");

		return self::$Instances[$key] = $obj;
	}

	static function
	Destroy($key) {
	/*//
	@argv string Key
	remove something from the stash. if the something is an object and the
	destructor is accessable we will force the object to destroy itself.
	//*/

		if(self::Has($key))
		unset(self::$Instances[$key]);

		return;
	}

}
