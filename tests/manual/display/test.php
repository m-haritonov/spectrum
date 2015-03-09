<?php
if (!function_exists('\spectrum\core\builders\run')) {
	exit('This file is not allowed to direct call');
}

config::setOutputResults('all');

define('SOME_CONST', 'bbb');

matcher('mySuccess', function(){ return true; });
matcher('myFail', function(){ return false; });

test('Matchers', function(){
	be('')->eq(null);
	be('')->not->eq('a');
	
	be(2)->gt(1);
	be(2)->not->gt(2);
	
	be(2)->gte(2);
	be(2)->not->gte(3);
	
	be('')->ident('');
	be('')->not->ident(null);
	
	be('\Exception')->is('\Exception');
	be(new \Exception())->is(new \Exception());
	
	be(1)->lt(2);
	be(2)->not->lt(1);
	
	be(2)->lte(2);
	be(2)->not->lte(1);
	
	be(function(){})->throwsException();
	
	be(function(){
		throw new \Exception('aaa', 123);
	})->throwsException('\Exception', 'aaa', 123);
	
	be(null)->mySuccess();
	be(null)->myFail();
});

test('Values output', function(){
	$object = new \stdClass();
	$object->aaa = 111;
	$object->bbb = 222;
	
	be(null)->not->ident(null);
	be(true)->not->ident(true);
	be(false)->not->ident(false);
	be(123)->not->ident(123);
	be(123.45)->not->ident(123.45);
	be('some text')->not->ident('some text');
	be('some big text ' . str_repeat('a ', 300))->not->ident('some big text ' . str_repeat('a ', 300));
	be("some multiline text\nsome multiline text")->not->ident("some multiline text\nsome multiline text");
	be(array('aaa', 'bbb', 'ccc'))->not->ident(array('aaa', 'bbb', 'ccc'));
	be(array('aaa' => 111, 'bbb' => 222, 'ccc' => 333))->not->ident(array('aaa' => 111, 'bbb' => 222, 'ccc' => 333));
	be($object)->not->ident($object);
	be(array('aaa', 'bbb', $object))->not->ident(array('aaa', 'bbb', $object));
	be(function(&$p1, $p2, array $p3, \Exception $p4, \Exception $p5 = null, $p6 = null, $p7 = true, $p8 = false, $p9 = 'a"\aa', $p10 = SOME_CONST, $p11 = 123, $p12 = 11.24, array $p13 = array('aaa'), array $p13 = null){})->not->ident(function(&$p1, $p2, array $p3, \Exception $p4, \Exception $p5 = null, $p6 = null, $p7 = true, $p8 = false, $p9 = 'a"\aa', $p10 = SOME_CONST, $p11 = 123, $p12 = 11.24, array $p13 = array('aaa'), array $p13 = null){});
});

group('Group with anonymous group', function(){
	group(function(){
		group('Not anonymous group', function(){});
	});	
});

test('Success test', function(){ be('')->ident(''); });
test('Fail test', function(){ be('')->ident('a'); });
test('Empty test', function(){});

group('Group with long name ' . str_repeat('a ', 300), function(){
	test('Test with long name ' . str_repeat('a ', 300), function(){
		be('')->ident('a');
		message('aaa');
	});
});

test('Messages', function(){
	message('aaa');
	message("aaa\nbbb");
});

group('Results', function(){
	test('Fail element only', function(){
		self()->getResults()->add(false, 'Some text');
	});
	
	test('Success element only', function(){
		self()->getResults()->add(true, 'Some text');
	});
	
	test('Empty element only', function(){
		self()->getResults()->add(null, 'Some text');
	});
	
	test('Elements', function() use(&$test){
		self()->getResults()->add(null, 'Some text');
		self()->getResults()->add(null, new \spectrum\core\details\UserFail('Some text'));
		
		self()->getResults()->add(true, 'Some text');
		be(1)->ident(1);
		
		self()->getResults()->add(false, 'Some text');
		be('')->ident('a');
		$aaa['a'];
		trigger_error('some text', E_USER_NOTICE);
		fail('some text');
		throw new \Exception('some text', 123);
	});
});

test('Contexts', array(
	array('value1' => 'aaa', 'value2' => 123, 'value3' => 123.45, 'value4' => null, 'value5' => true, 'value6' => false, 'value7' => array(), 'value8' => new stdClass()),
	array('value1' => 'bbb'),
	array('value1' => 'some big value ' . str_repeat('a ', 300)),
	'some name' => array('value1' => 'ccc'),
), function(){});

run();