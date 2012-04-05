<?php
/*
 * (c) Mikhail Kharitonov <mail@mkharitonov.net>
 *
 * For the full copyright and license information, see the
 * LICENSE.txt file that was distributed with this source code.
 */


require_once dirname(__FILE__) . '/CodeFormatter.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title></title>
    <style type="text/css">
        body { font-family: Verdana, sans-serif; font-size: 0.75em; }

		h1 { font-family: "Times New Roman", serif; font-size: 1.5em; }
		code { font-size: 1.3em; }

		.notice { padding-left: 6px; border-left: 3px double #ccc; font-style: italic; }

		.example {  }
		.example h1 { margin-bottom: 0.4em; font-family: Verdana, sans-serif; font-size: 1em; }

		.example code { display: block; padding: 0.5em; background: #eee; font-family: Consolas, "Andale Mono WT", "Andale Mono", "Lucida Console", Monaco, "Courier New", Courier, monospace; font-size: 13px; white-space: pre; }
		.example code .tab { display: inline-block; overflow: hidden; width: 3em; }

		.example .result { margin-top: 1em; }
		.example .result iframe { width: 100%; height: 200px; background: #eee; }

		.counters { margin-top: 1.3em; }
    </style>
</head>
<body>

<!--LiveInternet counter--><script type="text/javascript"><!--
new Image().src = "//counter.yadro.ru/hit?r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";"+Math.random();//--></script><!--/LiveInternet-->

<?php
function printExample($name, $code, $settings = array())
{
	print '<div class="example">' . "\r\n";

	if ($name != '')
		print '<h1>' . htmlspecialchars($name) . ':</h1>' . "\r\n";

	printCode($code);

	if (!in_array('noResult', $settings))
		printResult($code, $settings);

	print '</div>' . "\r\n";
}

function printCode($code)
{
	$codeFormatter = new CodeFormatter();
	$codeFormatter->setHighlightCode(true);
	$codeFormatter->setHighlightSpaces(false);
	$codeFormatter->setRemoveLeadingTabColumns(true);
	$codeFormatter->setWrapTapsToTag(true);
	$codeFormatter->setTabWrapperCssClass('tab');
	$codeFormatter->setIndentionType('tabs');

	print '<code>';
	print $codeFormatter->format($code);
	print '</code>' . "\r\n";
}

function printResult($code, $settings = array())
{
	print '<div class="result">' . "\r\n";
	print '<h1>Результат:</h1>' . "\r\n";

	$height = @$settings['height'];
	unset($settings['height']);
	if ($height)
		$style = "height: {$height}px;";
	else
		$style = '';

	$cacheFilename = generateCacheFilename();

	if ($_SERVER['SERVER_NAME'] == 'spectrum.local' && $_SERVER['SERVER_ADDR'] == '127.0.0.1')
	{
		$url = 'runner.php?code=' . urlencode($code) . '&' . implode('&', $settings);
		$resultPage = file_get_contents('http://spectrum.local/docs/' . $url);
		file_put_contents(dirname(__FILE__) . '/' . $cacheFilename, $resultPage);
	}

	print '<iframe src="' . htmlspecialchars($cacheFilename) . '" frameborder="0" style="' . $style . '"></iframe>' . "\r\n";
	print '</div>' . "\r\n";
}

function createCacheResult($code, $settings = array())
{
	static $lastCacheId;

}

function generateCacheFilename()
{
	static $lastCacheId;
	$lastCacheId = (int) $lastCacheId + 1;
	return "cache/result_$lastCacheId.html";
}
?>

<h1>Spectrum — PHP фреймворк для BDD тестирования (alpha версия)</h1>

<ol>
	<li><a href="#base">Базовая структура</a></li>
	<li><a href="#arguments-providers">Провайдеры аргументов</a></li>
	<li><a href="#asserts">Утверждения и матчеры</a></li>
	<li><a href="#worlds">Миры (фикстуры)</a></li>
	<li><a href="#contexts">Контексты</a></li>
	<li><a href="#anonymous-containers">Анонимные контейнеры</a></li>
	<li><a href="#patterns">Образцы</a></li>
	<li><a href="#run">Запуск</a></li>
	<li><a href="#run-result">Результат выполнения</a></li>
	<li><a href="#disable">Включение/отключение узлов</a></li>
	<li><a href="#error-handling">Обработка ошибок</a></li>
	<li><a href="#reports">Отчеты</a></li>
	<li><a href="#construction-commands">Команды конструирования</a></li>
	<li><a href="#plugins">Плагины</a></li>
	<li><a href="#plugin-events">События</a></li>
	<li><a href="#contacts">Обратная связь</a></li>
</ol>

<p>Spectrum — это PHP фреймворк, предназначенный для так называемых specification тестов (аналог RSpec и т.п.) и предоставляющий довольно
богатые возможности по настройке и расширению.</p>

<p>Скачать исходные коды можно с <a href="https://github.com/mkharitonov/spectrum/">github.com/mkharitonov/spectrum/</a></p>

<p class="notice">* Все примеры и сама статья пока проверялись только в браузерах Chrome и Firefox.</p>

<div class="example">
	<?php
	printExample('', <<<'CODE'
		<?php
		require_once 'spectrum/spectrum/init.php';

		describe('Космический корабль', function(){
			it('Должен бороздить просторы вселенной', function(){
				$spaceship = new Spaceship();
				be($spaceship->getLocation())->eq('space');
			});

			it('Не должен прохлаждаться', function(){
				$spaceship = new Spaceship();
				be($spaceship->getTask())->not->eq('foo');
			});
		});

		\spectrum\RootDescribe::run();
CODE
, array('noRun', 'height' => 100));
	?>
</div>

<h1 id="base">Базовая структура</h1>

<p>Для написание спецификаций Spectrum предоставляет несколько различных <a href="#construction-commands">команд конструирования</a>. Базовая древовидная
структура создается с помощью команд конструирования it(), describe() и context() (про контексты см. в нижеследующем разделе), притом ни глубина ни кол-во команд никак не ограничено.</p>

<?php
printExample('Пример вложенной структуры', <<<'CODE'
	describe('Космический корабль', function(){
		it('Должен бороздить просторы вселенной', function(){ be(true)->true(); });

		describe('Боевое оснащение', function(){
			it('Должены быть установлены фотонные пушки', function(){ be(true)->true(); });
			it('Должены быть установлены лезеры', function(){ be(true)->true(); });
		});

		describe('Медицинский отсек', function(){
			it('Должен быть оборудован душевой кабиной', function(){ be(true)->true(); });
			it('Должен содержать 5 койко-мест', function(){ be(true)->true(); });

			describe('Операционная', function(){
				it('Должна быть размером 9 кв. м.', function(){ be(true)->true(); });
				it('Должна быть оборудована системой стерилизации', function(){ be(true)->true(); });
			});
		});
	});
CODE
, array('height' => 220));
?>

<h1 id="arguments-providers">Провайдеры аргументов</h1>

<p>Бывает, что сами данные сами по себе являются лучшим описанием требуемого поведения. Для таких случает существуют поставщики
данных (data providers).</p>

<?php
printExample('Пример провайдеров аргументов', <<<'CODE'
	describe('Форма ввода телефона', function(){
		it('Должена принимать различные форматы тефонных номеров',
		array(
            '+7 (495) 123-456-7',
            '(495) 123-456-7',
            '123-456-7',
		),
		function($world, $tel){
			be($tel)->eq('+7 (495) 123-456-7');
		});

		// Если требуется передать несколько аргументов, то элемент провайдера аргументов должен сам быть массивом
		it('Должена принимать различные форматы тефонных номеров', array(
			'foo',
			array('bar', 'bar2'),
			'baz'
		), function($world, $arg1, $arg2 = null){
			be($arg1)->eq('bar');
			be($arg2)->eq('bar2');
		});
	});
CODE
, array('height' => 240));
?>

<h1 id="asserts">Утверждения и матчеры</h1>
<p>Утверждения создаются с помодью команды конструирования <code>be()</code>, которая возвращает объект класса
<code>core/Assert</code>, обращаясь к которому можно вызывать различные матчеры (вызовы методов перехватываются и перенаправляются
к требуему матчеру).</p>

<p>Либо можно обратиться к специальному свойству <code>not</code> (опять же не реальному), которое
инвертирует результат последующего вызова матчера.</p>

<?php
printExample('Пример различных вызовов матчеров', <<<'CODE'
	it('Должен', function(){
		be(true)->true();
		be(true)->not->false();

		// Так же можно записывать несколько матчеров в одной строке (обратите внимание
		// что "not" действует только на первый из последующих матчеров)
		be(true)->not->false()->true();
	});
CODE
, array('height' => 50));
?>

<p>Вы так же можете добавлять собственные матчеры с помощью команды конструирования <code>addMatcher($name, $callback)</code>.
Callback функции будет передано актуальное значение первым параметром и все агрументы, переданные матчеру при вызове, последующими параметрами.</p>

<?php
printExample('Пример callback функций матчеров', <<<'CODE'
	describe('', function(){
		addMatcher('foo', function($actual){
			return ($actual == 'foo');
		});

		it('Должен', function(){
			be('foo')->foo();
			be('bar')->not->foo();
		});

		// Матчер с дополнительными параметрами
		addMatcher('something', function($actual, $expected, $elseArg){
			// $actual - foo
			// $expected - bar
			// $elseArg - baz

			return false;
		});

		it('Должен еще', function(){
			be('foo')->something('bar', 'baz');
		});
	});
CODE
, array('height' => 60));
?>

<p>С помощью команды <code>addMatcher($name, $callback)</code> можно добавить матчер в любой it/describe/context и дочерние
команды конструирования будут вызывать матчеры из своих родителей/предков (в случае объявления матчеров в контекстах,
поиск матчера будет осуществляться в стеке выполняющихся контекстов, см. подробности в главе про контексты ниже).</p>

<?php
printExample('Пример матчеров во вложенных структурах', <<<'CODE'
	describe('Пример добавления', function(){
		addMatcher('foo', function(){
			return true;
		});

		it('Должен', function(){
			be(true)->foo(); // Из родителя
		});

		describe('Второй', function(){
			it('Должен', function(){
				be(true)->foo(); // Из предка (т.е. оттуда же, что и предыдущий)
			});
		});
	});

	describe('Пример переопределения', function(){
		addMatcher('foo', function(){
			return true;
		});
		
		it('Должен', function(){
			be(true)->foo();
		});

		describe('Со своей версией foo', function(){
			addMatcher('foo', function(){
				return false;
			});

			it('Должен', function(){
				be(true)->foo();
			});
		});
	});
CODE
, array('height' => 160));
?>

<p>Spectrum содержит некоторое кол-во стандартных матчеров, которые уже добавлены в RootDescribe.</p>

<?php
printExample('Пример переопределения стандартных матчеров', <<<'CODE'
	describe('', function(){
		it('Должен', function(){
			be(null)->null();
			be(true)->true();
			be(1)->not->true();
			be(false)->false();
			be(0)->not->false();

			be('foo')->eq('foo');
			be(new Spaceship())->not->ident(new Spaceship());

			be(5)->lt(10); // Less than
			be(10)->lte(10); // Less than or equal
			be(10)->gt(5); // Greater than
			be(10)->gte(10); // Greater than or equal

			be(function(){
				throw new Exception();
			})->throwException();
			
			be(function(){
				throw new ErrorException();
			})->throwException('\ErrorException');

			be(function(){
				throw new ErrorException('Foo is not bar', 123);
			})->throwException('\ErrorException', 'foo', 123);
			
			be(function(){
				throw new Exception('Foo is not bar');
			})->throwException(null, 'foo');
		});

		it('Должен', function(){
			be(function(){
				throw new Exception();
			})->throwException('\ErrorException');
		});

		it('Должен', function(){
			be(function(){
				throw new ErrorException();
			})->throwException('\ErrorException', 'foo');
		});

		// Использование мира в throwException()
		it('Должен', function($world){
			be(function() use($world){
				$world->foo = 'bar';
			})->not->throwException();
		});
	});
CODE
, array('height' => 100));
?>

<p>Поскольку все стандартные матчеры содержатся в RootDescribe, их можно с легкостью переопределить.</p>

<?php
printExample('Пример переопределения стандартных матчеров', <<<'CODE'
	addMatcher('true', function($actual){
		return ($actual !== true);
	});

	describe('', function(){
		it('Должен', function(){
			be(true)->true();
		});
	});
CODE
, array('height' => 50));
?>

<h1 id="worlds">Миры (фикстуры)</h1>

<p>Т.к. природа BDD тестов предполагает детальное описание поведения, во многих случаях код смежных it() будет дублироваться.
Что бы избежать этого, в Spectrum'е существуют миры (worlds), которые создаются при помощи команд конструирования
beforeEach($callback) и afterEach($callback) и в которые следует выносить общие для всех it() части.</p>

<p class="notice">Пару слов о терминологии. У каждого it() есть свой мир (world), к которому могут применяться творцы мира
(world creators): beforEach() — строитель (builder) и afterEach() — разрушитель (destroyer).</p>

<?php
printExample('Пример создания мира', <<<'CODE'
	describe('Космический корабль', function(){
		beforeEach(function($world){
			// World является простым объектом без каких-либо свойств, поэтому можно «наживую» создавать
			// в нем любые свойства, а так же просматривать все его свойства в цикле foreach
			$world->spaceship = new Spaceship();
			$world->spaceship->startSystems();
		});

		afterEach(function($world){
			$world->spaceship->stopSystems();
		});

		it('Должен бороздить просторы вселенной', function($world){
			be($world->spaceship->getLocation())->eq('space');
		});

		it('Не должен прохлаждаться', function($world){
			be($world->spaceship->getTask())->not->eq('foo');
		});
	});
CODE
, array('height' => 80));
?>

<p>Так же, как и в случае с матчерами, творцы применяются к миру всех своих потомков и у любой группы можно создать
дополнительных творцов для постройки и разрушения.</p>

<?php
printExample('Пример создания мира', <<<'CODE'
	describe('Космический корабль', function(){
		beforeEach(function($world){
			$world->spaceship = new Spaceship();
			$world->spaceship->startSystems();
		});

		afterEach(function($world){
			$world->spaceship->stopSystems();
		});

		it('Должен бороздить просторы вселенной', function($world){
			be($world->spaceship->getLocation())->eq('space');
		});

		describe('Межпространственный полет', function(){
			beforeEach(function($world){
				$world->spaceship->startEngine();
			});

			afterEach(function($world){
				$world->spaceship->stopEngine();
			});

			it('Должен облетать звёзды', function($world){
				new Star(25, 50, 100);
				$world->spaceship->setDestination(30, 50, 100);
				be($world->spaceship->isHasCollision())->false();
			});
		});
	});
CODE
, array('height' => 100));
?>

<p>Порядок применения творцов к мирам дочерних узлов — во внутрь. Т.к. Сначала строители родителя, потом строители
ребенка, затем разрушители ребенка, потом разрушители родителя.</p>

<p>И опять, так же, как и в случае с матчерами, можно объявлять своих творцов миров в каждом контексте. См. главу про контексты.</p>


<h1 id="contexts">Контексты</h1>

<p>Суть контекстов можно продемонстрировать на примере, когда требуется одни и те же it() выполнить в разных мирах (тобишь при разных внешних условиях).</p>

<?php
printExample('Пример без использования контекстов', <<<'CODE'
	describe('Космический корабль', function(){
		describe('В галактике Альфа Центавра', function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
				$world->spaceship->setTask('study');
			});

			it('Должен изучать живые организмы', function($world){ be($world->spaceship->getTask())->eq('study'); });
			it('Должен собирать неизвестные ископаемые', function($world){ be($world->spaceship->getTask())->eq('study'); });
			it('Должен защищать слабых и обездоленных', function($world){ be($world->spaceship->getTask())->eq('study'); });
		});

		describe('В галактике Хоага', function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
				$world->spaceship->setTask('study');
			});

			// Копипастим
			it('Должен изучать живые организмы', function($world){ be($world->spaceship->getTask())->eq('study'); });
			it('Должен собирать неизвестные ископаемые', function($world){ be($world->spaceship->getTask())->eq('study'); });
			it('Должен защищать слабых и обездоленных', function($world){ be($world->spaceship->getTask())->eq('study'); });
		});

		describe('В галактике Мейола', function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
				$world->spaceship->setTask('rest'); // Отличается
			});

			// Снова копипастим
			it('Должен изучать живые организмы', function($world){ be($world->spaceship->getTask())->eq('study'); });
			it('Должен собирать неизвестные ископаемые', function($world){ be($world->spaceship->getTask())->eq('study'); });
			it('Должен защищать слабых и обездоленных', function($world){ be($world->spaceship->getTask())->eq('study'); });
		});
	});
CODE
, array('height' => 240));
?>

<p>С помощью контекстов можно избавить себя от скушных copy/paste.</p>

<?php
printExample('Пример с использованием контекстов', <<<'CODE'
	describe('Космический корабль', function(){
		context('В галактике Альфа Центавра', function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
				$world->spaceship->setTask('study');
			});
		});

		context('В галактике Хоага', function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
				$world->spaceship->setTask('study');
			});
		});
		
		context('В галактике Мейола', function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
				$world->spaceship->setTask('rest');
			});
		});

		it('Должен изучать живые организмы', function($world){ be($world->spaceship->getTask())->eq('study'); });
		it('Должен собирать неизвестные ископаемые', function($world){ be($world->spaceship->getTask())->eq('study'); });
		it('Должен защищать слабых и обездоленных', function($world){ be($world->spaceship->getTask())->eq('study'); });
	});
CODE
, array('height' => 240));
?>

<p>Работают контексты согласно следующим правилам:</p>
<ol>
	<li>Если у describe() есть context() дети (не потомки), то выполняются только эти контексты;</li>
	<li>Если у context() есть context() дети (не потомки), то так же выполняются только эти контексты;</li>
	<li>Если у context() нет context() детей, то данный контекст запускает выполнение все describe/it до ближайшего describe предка (т.е. сначала запускает describe/it детей ближайшего describe предка, затем describe/it детей промежуточных контекстов, а затем своих детей);</li>
</ol>


<p>В частности, это позволяет использовать вложенные контексты для еще большего устранения дублирований в творцах:</p>

<?php
printExample('', <<<'CODE'
	describe('Космический корабль', function(){
		// Используем анонимный контекст
		context(function(){
			beforeEach(function($world){
				// Выносим общий код создания экземпляра класса
				$world->spaceship = new Spaceship();
			});

			context('В галактике Альфа Центавра', function(){
				beforeEach(function($world){
					$world->spaceship->setTask('study');
				});
			});

			context('В галактике Хоага', function(){
				beforeEach(function($world){
					$world->spaceship->setTask('study');
				});
			});

			context('В галактике Мейола', function(){
				beforeEach(function($world){
					$world->spaceship->setTask('rest');
				});
			});
		});

		it('Должен изучать живые организмы', function($world){ be($world->spaceship->getTask())->eq('study'); });
		it('Должен собирать неизвестные ископаемые', function($world){ be($world->spaceship->getTask())->eq('study'); });
		it('Должен защищать слабых и обездоленных', function($world){ be($world->spaceship->getTask())->eq('study'); });
	});
CODE
, array('height' => 240));
?>

<p>Устранять дублирования не только в двумерных describe():</p>

<?php
printExample('', <<<'CODE'
	describe('Космический корабль', function(){
		context(function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
			});

			context('В галактиках', function(){
				context('В галактике Альфа Центавра', function(){
					beforeEach(function($world){
						$world->spaceship->setTask('study');
					});
				});

				context('В галактике Хоага', function(){
					beforeEach(function($world){
						$world->spaceship->setTask('study');
					});
				});

				context('В галактике Мейола', function(){
					beforeEach(function($world){
						$world->spaceship->setTask('rest');
					});
				});
			});

			context('На планетах', function(){
				context('На планете Земля', function(){
					beforeEach(function($world){
						$world->spaceship->setTask('study');
					});
				});

				context('На планете Марс', function(){
					beforeEach(function($world){
						$world->spaceship->setTask('rest');
					});
				});
			});
		});

		it('Должен изучать живые организмы', function($world){ be($world->spaceship->getTask())->eq('study'); });
	});
CODE
, array('height' => 240));
?>


<p>Добавлять при необходимости в какой-либо контекст дополнительные спецификации (как it(), так и describe(), который в свою очередь может содержать любое кол-во потомков, включая контексты):</p>

<?php
printExample('', <<<'CODE'
	describe('Космический корабль', function(){
		context(function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
			});

			context('В галактике Альфа Центавра', function(){
				beforeEach(function($world){
					$world->spaceship->setTask('study');
				});
				
				it('Должен собирать неизвестные ископаемые', function($world){ be($world->spaceship->getTask())->eq('study'); });
				it('Должен защищать слабых и обездоленных', function($world){ be($world->spaceship->getTask())->eq('rest'); });
			});

			context('В галактике Хоага', function(){
				beforeEach(function($world){
					$world->spaceship->setTask('study');
				});
			});

			context('В галактике Мейола', function(){
				beforeEach(function($world){
					$world->spaceship->setTask('rest');
				});
			});
		});

		it('Должен изучать живые организмы', function($world){ be($world->spaceship->getTask())->eq('study'); });
	});
CODE
);
?>

<p>Создавать многоуровневые контексты:</p>

<?php
printExample('', <<<'CODE'
	describe('Космический корабль', function(){
		// В данном случае анонимный контекст можно опустить, разместив творцов на
		// одном уровне с контекстами, к которым он должен применяться
		beforeEach(function($world){
			$world->spaceship = new Spaceship();
		});

		context('В галактике Альфа Центавра', function(){
			beforeEach(function($world){
				$world->spaceship->setTask('study');
			});
		});

		context('В галактике Хоага', function(){
			beforeEach(function($world){
				$world->spaceship->setTask('study');
			});
		});

		context('В галактике Мейола', function(){
			beforeEach(function($world){
				$world->spaceship->setTask('study');
			});
		});

		describe('Миссия', function(){
			context('В космосе', function(){
				beforeEach(function($world){
					$world->spaceship->setTask('study');
				});
			});

			context('На планете', function(){
				beforeEach(function($world){
					$world->spaceship->setTask('rest');
				});
			});

			it('Должен изучать живые организмы', function($world){ be($world->spaceship->getTask())->eq('study'); });
		});
	});
CODE
, array('height' => 350));
?>

<p>Вы так же можете объявлять свои матчеры в каждом контексте:</p>

<?php
printExample('', <<<'CODE'
	describe('Космический корабль', function(){
		context(function(){
			context('В галактике Альфа Центавра', function(){
				addMatcher('foo', function($actual){
					return ($actual == 'foo');
				});
			});

			context('В галактике Хоага', function(){
				addMatcher('foo', function($actual){
					return ($actual == 'foo');
				});
			});

			context('В галактике Мейола', function(){
				addMatcher('foo', function($actual){
					return ($actual == 'bar');
				});
			});
		});

		it('Должен быть как Foo', function(){ be('foo')->foo(); });
	});
CODE
, array('height' => 160));
?>

<h1 id="anonymous-containers">Анонимные контейнеры</h1>

<p>Команды конструирования describe() и context() могут принимать callback функцию в качестве единственого аргумента (либо можно задать пустую строку в качестве имени).
В таком случае речь идет об анонимном контейнере, имена которых будут исключаться из отчетов, но которые по прежнему
можно использовать для физической организации.</p>

<?php
printExample('Пример анонимного describe', <<<'CODE'
	describe('Космический корабль', function(){
		it('Должен (из именного контекнера)', function(){ be(true)->true(); });

		describe(function(){
			// Тут, например, можно добавить творцов миров (или матчеры), которые
			// будут применяться только к детям и потомкам данного describe
			it('Должен (из анониимного контейнера)', function(){ be(true)->true(); });
			it('Должен (из анониимного контейнера)', function(){ be(true)->true(); });
		});
	});
CODE
, array('height' => 100));
?>

<h1 id="patterns">Образцы</h1>

<p>Так же для устранения дублирований можно использовать образцы.</p>

<?php
printExample('Пример анонимного describe', <<<'CODE'
	addPattern('Автомобиль', function($doorsCount){
		it('Кол-во дверей должно быть ' . $doorsCount, function($w) use($doorsCount){
			be(4)->eq($doorsCount);
		});
	});

	itLikePattern('Автомобиль', 4);
	itLikePattern('Автомобиль', 3);
CODE
, array('height' => 100));
?>

<h1 id="run">Запуск</h1>

<p>Запускать можно не только корневой describe, но и любой другой узел, ссылку на которым можно получить с помощью
<a href="#plugins">плагина</a> selector, начиная поиск от RootDescribe, либо присвоив результат работы it/describe/context
переменной.</p>

<p class="notice">Все корневые узлы добавляются в класс RootDescribe, который сам по себе является анонимным describe (точнее,
это статический класс, у которого есть метод getOnceInstance(), возвращающий экземпляр класса core\SpecContainerDescribe),
поэтому вызов метода RootDescribe->getOnceInstance()->run() (RootDescribe::run() просто удобный алиас) ведет себя аналогично
другим узлам.</p>

<?php
printExample('', <<<'CODE'
	$spec = describe('Космический корабль', function(){
		it('Должен изучать живые организмы', function(){ be(true)->true(); });
		it('Должен собирать неизвестные ископаемые', function(){ be(true)->true(); });
		it('Должен защищать слабых и обездоленных', function(){ be(true)->true(); });
	});

	$spec->run(); // В данном случае аналогично RootDescribe::run()
CODE
, array('noRun', 'height' => 100));
?>

<p>Можно запустить только дочерние узлы.</p>
<?php
printExample('', <<<'CODE'
	$spec = describe('Космический корабль', function(){
		it('Должен изучать живые организмы', function(){ be(true)->true(); });
		it('Должен собирать неизвестные ископаемые', function(){ be(true)->true(); });
		it('Должен защищать слабых и обездоленных', function(){ be(true)->true(); });
	});

	$spec->selector->getChildByIndex(1)->run(); // Нумерация начинается с нуля
CODE
, array('noRun', 'height' => 60));
?>

<p>Можно так же передать ссылку на переменную в замыкание, куда и сохранить результат.</p>
<?php
printExample('', <<<'CODE'

	describe('Космический корабль', function() use(&$spec){
		it('Должен изучать живые организмы', function(){ be(true)->true(); });
		$spec = it('Должен собирать неизвестные ископаемые', function(){ be(true)->true(); });
		it('Должен защищать слабых и обездоленных', function(){ be(true)->true(); });
	});

	$spec->run();
CODE
, array('noRun', 'height' => 60));
?>

<p>Можно запускать целые describe.</p>
<?php
printExample('', <<<'CODE'

	describe('Космический корабль', function() use(&$spec){
		it('Должен', function(){ be(true)->true(); });

		$spec = describe('Миссия', function(){
			it('Должен изучать живые организмы', function(){ be(true)->true(); });
			it('Должен собирать неизвестные ископаемые', function(){ be(true)->true(); });
			it('Должен защищать слабых и обездоленных', function(){ be(true)->true(); });
		});
	});

	$spec->run();
CODE
, array('noRun', 'height' => 120));
?>

<p>Если есть контексты, узел будет запущен во всех из них.</p>
<?php
printExample('', <<<'CODE'

	describe('Космический корабль', function() use(&$spec){
		context('В галактике Альфа Центавра', function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
				$world->spaceship->setTask('rest');
			});
		});

		context('В галактике Хоага', function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
				$world->spaceship->setTask('study');
			});
		});

		context('В галактике Мейола', function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
				$world->spaceship->setTask('study');
			});
		});

		it('Должен изучать живые организмы', function($world){ be($world->spaceship->getTask())->eq('study'); });
		$spec = it('Должен собирать неизвестные ископаемые', function($world){ be($world->spaceship->getTask())->eq('study'); });
		it('Должен защищать слабых и обездоленных', function($world){ be($world->spaceship->getTask())->eq('study'); });
	});

	$spec->run();
CODE
, array('noRun', 'height' => 160));
?>

<p>Запуск узла в многоуровневом контексте так же возможен.</p>
<?php
printExample('', <<<'CODE'

	context('Некий контекст', function(){});
	context('Еще один контекст', function(){});

	describe('Космический корабль', function() use(&$spec){
		context('В галактике Альфа Центавра', function(){});
		context('В галактике Хоага', function(){});
		context('В галактике Мейола', function(){});

		it('Должен изучать живые организмы', function($world){ be(true)->true(); });
		$spec = it('Должен собирать неизвестные ископаемые', function($world){ be(true)->true(); });
		it('Должен защищать слабых и обездоленных', function($world){ be(true)->true(); });
	});

	$spec->run();
CODE
, array('noRun', 'height' => 300));
?>

<p>Можно так же запустить и все узлы какого-либо контекста.</p>
<?php
printExample('', <<<'CODE'
	describe('Космический корабль', function() use(&$spec){
		context('В галактике Альфа Центавра', function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
				$world->spaceship->setTask('rest');
			});
		});

		context('В галактике Хоага', function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
				$world->spaceship->setTask('study');
			});
		});

		$spec = context('В галактике Мейола', function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
				$world->spaceship->setTask('study');
			});
		});

		it('Должен изучать живые организмы', function($world){ be($world->spaceship->getTask())->eq('study'); });
		it('Должен собирать неизвестные ископаемые', function($world){ be($world->spaceship->getTask())->eq('study'); });
		it('Должен защищать слабых и обездоленных', function($world){ be($world->spaceship->getTask())->eq('study'); });
	});

	$spec->run();
CODE
, array('noRun', 'height' => 100));
?>


<h1 id="run-result">Результат выполнения</h1>

<p>Помимо отображения результата в отчете, метод run() возвращает результат запуска.</p>
<ol>
	<li>false — тест (и или один из дочерних тестов) не пройден</li>
	<li>true — тест (и тесты всех дочерних узлов) пройден (нет непройденных и пустых тестов)</li>
	<li>null — тест (либо тест одного из дочерних узлов) не содержит ни одного положительного утверждения или ошибки</li>
</ol>

<?php
printExample('', <<<'CODE'
	$spec = describe('Космический корабль', function(){
		it('Должен изучать живые организмы', function(){ be(true)->true(); });
		it('Должен собирать неизвестные ископаемые', function(){ be(false)->true(); });
		it('Должен защищать слабых и обездоленных', function(){  });

		describe('Миссия', function(){
			it('Должен изучать живые организмы', function(){ be(true)->true(); });
			it('Должен служить людям', function(){});
		});
	});

	$spec->run();
CODE
, array('noRun', 'height' => 160));
?>

<h1 id="disable">Включение/отключение узлов</h1>

<p>Если требуется запуск не только одного, а нескольких узлов из многих, то ненужные узлы можно предварительно отключить,
вызывая методы <code>disable()</code> и <code>enable()</code> узлов.</p>

<?php
printExample('', <<<'CODE'
	$spec = describe('Космический корабль', function(){
		it('Должен изучать живые организмы', function($world){ be(true)->true(); });
		it('Должен собирать неизвестные ископаемые', function($world){ be(true)->true(); });
		it('Должен защищать слабых и обездоленных', function($world){ be(true)->true(); });
		it('Должен служить людям', function($world){ be(true)->true(); });
	});

	$spec->selector->getChildByIndex(0)->disable();
	$spec->selector->getChildByIndex(2)->disable();
	$spec->run();
CODE
, array('noRun', 'height' => 80));
?>

<p>А так, например, можно использовать анонивные контекнеры для удобного отключения группы узлов.</p>

<?php
printExample('', <<<'CODE'
	$spec = describe('Космический корабль', function(){
		describe(function(){
			it('Должен изучать живые организмы', function($world){ be(true)->true(); });
			it('Должен собирать неизвестные ископаемые', function($world){ be(true)->true(); });
		});

		it('Должен защищать слабых и обездоленных', function($world){ be(true)->true(); });
		it('Должен служить людям', function($world){ be(true)->true(); });
	});

	$spec->selector->getChildByIndex(0)->disable();
	$spec->run();
CODE
, array('noRun', 'height' => 80));
?>

<p>Можно так же отключить и контексты.</p>
<?php
printExample('', <<<'CODE'
	describe('Космический корабль', function() use(&$spec, &$context){
		context('В галактике Альфа Центавра', function(){});
		context('В галактике Хоага', function(){});
		$context = context('В галактике Мейола', function(){});

		it('Должен изучать живые организмы', function($world){ be(true)->true(); });
		$spec = it('Должен собирать неизвестные ископаемые', function($world){ be(true)->true(); });
		it('Должен защищать слабых и обездоленных', function($world){ be(true)->true(); });
	});

	$context->disable();
	$spec->run();
CODE
, array('noRun', 'height' => 100));
?>

<p>Основная идеология, которой придерживается Spectrum при отключении узлов — отключение не должно изменять
поведение тестов (только результаты). В следующем примере, отключение всех контекстов не заставит Spectrum
выполнять it узлы describe (хотя именно такое поведение распространяется на describe без дочерних контекстов). В
данном случае это просто привело бы к ошибке, т.к. it ожидает наличие переменной $world->spaceship</p>

<?php
printExample('', <<<'CODE'
	$spec = describe('Космический корабль', function(){
		context('В галактике Альфа Центавра', function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
			});
		});

		it('Должен изучать живые организмы', function($world){ be($world->spaceship)->eq(new Spaceship()); });
	});

	$spec->selector->getChildByIndex(0)->disable();
	$spec->run();
CODE
, array('noRun', 'height' => 50));
?>

<p>Тем не менее, можно изменить объектную структуру узлов прямо перед запуском путем их удаления.</p>

<?php
printExample('', <<<'CODE'
	$spec = describe('Космический корабль', function(){
		context('В галактике Альфа Центавра', function(){
			beforeEach(function($world){
				$world->spaceship = new Spaceship();
			});
		});

		it('Должен изучать живые организмы', function($world){
			if (isset($world->spaceship))
				be($world->spaceship)->eq(new Spaceship());
			else
				be(true)->false();
		});
	});

	$spec->selector->getChildByIndex(0)->removeFromParent();
	$spec->run();
CODE
, array('noRun', 'height' => 70));
?>

<h1 id="error-handling">Обработка ошибок</h1>

<p>Spectrum позволяет управлять обработкой ошибок на уровне каждого из узлов с помощью следующих методов плагина errorHandling:</p>
<ol>
	<li>$spec->errorHandling->setCatchExceptions(true|false) — перехватывать исключения, выбрасываемые тестами и добавлять их в результирующий буффер</li>
	<li>$spec->errorHandling->setCatchPhpErrors(true|false|errorLevel) — перехватывать ошибки (те, которые может отловить set_error_handler()), генерируемые тестами и добавлять их в результирующий буффер</li>
	<li>$spec->errorHandling->setBreakOnFirstPhpError(false) — прерывать выполнение теста при первой php ошибке или нет</li>
	<li>$spec->errorHandling->setBreakOnFirstMatcherFail(false) — прерывать выполнение теста при первом провальном утверждении (матчере) или нет</li>
</ol>

<p class="notice">Результирующий буффер — это экземпляр класса core\RunResultsBuffer, который создается при каждом выполнении it и в который
заносятся результаты всех утверждения и ошибок. В этот буфер можно добавить свои результаты, например, из <a href="#plugins">плагинов</a> и просмотреть его содержимое в любой момент.</p>

<p>По умолчанию, перехватываются все исключения и ошибки php и отключено прерывание выполнения при php ошибке и провальном матчере.</p>

<p>Все эти параметры можно задавать для конкретных it/describe/context.</p>

<h1 id="reports">Отчеты</h1>

<p>Отчет об ошибках включается/выключается в плагине Report. По умолчанию, включены. Отчет об ошибках можно включать/отключать для конкретных it/describe/context</p>

<?php
printExample('', <<<'CODE'
	$spec = describe('Космический корабль', function(){
		it('Должен изучать живые организмы', function(){
			be('foo')->eq('foo');
			be('foo')->eq('bar');
		});
	});

	$spec->run();
CODE
, array('noRun', 'enableDebug', 'height' => 300));
?>


<h1 id="construction-commands">Команды конструирования</h1>

<p>Spectrum спроектирован тиким образом, что команды конструирования полностью отделены от объектной структуры.
Например, следующие два примера дадут одинаковый результат.</p>

<?php
printExample('', <<<'CODE'
	use \spectrum\RootDescribe;

	describe('Космический корабль', function(){
		it('Должен изучать живые организмы', function(){
			be(true)->true();
		});
	});

	RootDescribe::run();
CODE
, array('noRun', 'height' => 70));
?>

<p>То же, но без использования команд конструирования:</p>

<?php
printExample('', <<<'CODE'
	use \spectrum\RootDescribe;
	use \spectrum\core\SpecItemIt;
	use \spectrum\core\asserts\Assert;
	use \spectrum\core\SpecContainerDescribe;

	$it = new SpecItemIt('Должен изучать живые организмы');
	$it->setTestCallback(function(){
		$assert = new Assert(true);
		$assert->true();
	});

	$describe = new SpecContainerDescribe('Космический корабль');
	$describe->addSpec($it);

	RootDescribe::getOnceInstance()->addSpec($describe);
	RootDescribe::run();
CODE
, array('noRun', 'height' => 70));
?>

Команды конструирования делятся на два этапа:
<ol>
	<li>Обьявление (declaring)</li>
	<li>Выполнение (running)</li>
</ol>

<p>На этапе обьявления вызываются callback функции таких команд, как describe и context.
Callback ф-я it вызывается на этапе выполнения (т.е. после вызова метода run()).</p>

<p>Помимо прочего, это означает, что доступ к объектной структуре можно получить до запуска и, например,
сформировать список спецификаций без выполнения запуска.</p>

<p>Управление командами конструирования возложено на класс constructionCommands\Manager,
который позволяет регистрировать новые или переопределять текущие команды.</p>

<?php
printExample('', <<<'CODE'
	use \spectrum\constructionCommands\Manager;

	Manager::registerCommand('crash', function(){
		Manager::getCurrentItem()->getRunResultsBuffer()->addResult(false, 'Космический корабль разбился');
	});

	it('Полет космического корабля', function(){
		Manager::crash();
	});
CODE
, array('enableDebug', 'height' => 250));
?>

<p>Команды конструирования можно вызывать как угодно и откуда угодно.</p>

<p>Можно генерировать их динамически:</p>
<?php
printExample('', <<<'CODE'
	for ($i = 0; $i < 5; $i++)
	{
		it("Должен $i", function() use($i){
			be($i)->lt(2);
		});
	}
CODE
, array('height' => 100));
?>

<p>А можно и подключать файлы, содержащие команды конструирования:</p>
<?php
printExample('', <<<'CODE'

	// specs.php
	// it('Должен изучать живые организмы', function(){ be(true)->true(); });

	describe('Космический корабль', function(){
		include('specs.php');
	});
CODE
, array('height' => 100));
?>

<h1 id="plugins">Плагины</h1>

<p>Если структура объявления может расширяться за счет команд конструирования, то структура объектной структуры может
расширяться за счет плагинов. Основой объектной структуры Spectrum являются следующие классы узлов:</p>

<ol>
	<li>Spec</li>
	<li>SpecContainer</li>
	<li>SpecContainerDescribe</li>
	<li>SpecContainerContext</li>
	<li>SpecContainerArgumentsProvider</li>
	<li>SpecItem</li>
	<li>SpecItemIt</li>
</ol>

<p>Плагины представляют собой объекты соответствующих классов (зарегистрированных через мереджер плагинов),
которые создаются для каждого экземпляра Spec классов в объектной структуре.</p>

<p>В нижеследующем примере, у каждого из трех узлов (экземпляров классов SpecContainerDescribe, SpecItemIt и, опять же,
SpecItemIt) существует свой экземпляр плагина foo.</p>
<?php
printExample('', <<<'CODE'
	\spectrum\core\plugins\Manager::registerPlugin('foo');
	$describe = describe('Космический корабль', function() use(&$it1, &$it2){
		$it1 = it('Должен летать', function(){});
		$it2 = it('Не должен плавать', function(){});
	});

	$describe->foo->add('foo');
	$it1->foo->add('bar');
	$it2->foo->add('baz');

	print '<pre>';
	print_r($describe->foo->getAll());
	print_r($it1->foo->getAll());
	print_r($it2->foo->getAll());
CODE
, array('noRun', 'height' => 220));
?>

<p>Методу Manager::registerPlugin() принимает 3 параметра:</p>
<ol>
	<li>Имя для доступа к плагину (foo в примере выше);</li>
	<li>Класс плагина, который должен реализовывать интерфейс core\plugin\PluginInterface (по умолчанию — это базовый плагин core\plugins\basePlugins\stack\Indexed);</li>
	<li>Момент создание экземпляра (активации) плагина:
		<ol>
			<li>whenConstructOnce — экземпляр плагина будет создан во время создания соответствующего экземпляра узла;</li>
			<li>whenCallOnce (используется по умолчанию) — экземпляр плагина будет создан только при первом обращении к нему, а при повторном обращении будет возвращаться ранее созданный экземпляр;</li>
			<li>whenCallAlways — при каждом обращении будет создаваться (и возвращаться) новый экземпляр плагина.</li>
		</ol>
	</li>
</ol>

<p>Создаваемый класс плагина не обязательно реализовавыть с нуля. Можно унаследовать его от класса core\plugin\Plugin (который, в частности,
содержит полезный метод callCascadeThroughRunningContexts(), позволяющий получить значение вызова функции через стек запущенных контекстов).</p>

<?php
printExample('', <<<'CODE'

	use \spectrum\core\plugins\Manager as PluginsManager;
	use \spectrum\constructionCommands\Manager as ConstructionCommandsManager;

	class MyPlugin extends \spectrum\core\plugins\Plugin
	{
		private $foo = null; // Значение по умолчанию должно задаваться не здесь, а при вызове callCascadeThroughRunningContexts()

		public function setFoo($foo)
		{
			$this->foo = $foo;
		}

		public function getFoo()
		{
			return $this->foo;
		}

		public function getFooCascade()
		{
			return $this->callCascadeThroughRunningContexts(
				'getFoo', // Вызываемый метод
				array(), // С аргументами
				'foo', // Значение по умолчанию (возвращаемое, если требуемое значение не найдено вплоть до самого верхнего уровня)
				null // Если getFoo возвратит идентичное значение, то поиск будет продолжен выше
			);
		}
	}

	PluginsManager::registerPlugin('foo', 'MyPlugin');

	$spec = describe('Космический корабль', function() use (&$context1, &$context2, &$context3){
		$context1 = context('В галактике Альфа Центавра', function(){});
		$context2 = context('В галактике Хоага', function(){});
		$context3 = context('В галактике Мейола', function(){});

		it('Должен', function(){
			print '<strong>' . ConstructionCommandsManager::getCurrentItem()->foo->getFooCascade() . '</strong>';
		});
	});

	$context1->foo->setFoo('bar');
	$context2->foo->setFoo('baz');
	// А для $context3 будет возвращего значение по умолчанию

	$spec->run();

CODE
, array('noRun', 'height' => 160));
?>

<p>Так же можно унаследовать плагин от одного из базовых плагинов, самым востребованным из которых, возможно, является
плагин Stack (и конечные классы Indexed и Named), позволяющий работать со стеком значений, в частности, получить значения
через стек запущенных контекстов (именно от данного плагина и унаследованы плагины Matchers и WorldCreators).</p>


<h1 id="plugin-events">События</h1>

<p>Плагин так же может реализовать один или несколько интерфейсов событий. В таком случае, соответствующие методы
данного плагина будут вызваны, когда указанное событие произойдет в соответствующем узле (будут вызваны методы только
экземпляров плагинов, принадлежащих данному узлу).</p>

<p>На текущий момент доступны следующие интерфейсы плагинов (расположенные в core\plugin\events).</p>
<ol>
	<li>OnRunInterface — методы вызываются при запуске в узлах любого типа
		<ol>
			<li>onRunBefore()</li>
			<li>onRunAfter($result)</li>
		</ol>
	</li>
	<li>OnRunContainerInterface — методы вызываются при запуске в узлах контейнерного типа (SpecContainer*)
		<ol>
			<li>onRunContainerBefore()</li>
			<li>onRunContainerAfter($result)</li>
		</ol>
	</li>
	<li>OnRunItemInterface — методы вызываются при запуске в узлах типа SpecItem*
		<ol>
			<li>onRunItemBefore()</li>
			<li>onRunItemAfter($result)</li>
		</ol>
	</li>
	<li>OnTestCallbackCallInterface — методы вызываются непосредственно при выполнении тестовой функции (и могут получать сведения о мире, в отличии от OnRun событий)
		<ol>
			<li>onTestCallbackCallBefore(core\World $world)</li>
			<li>onTestCallbackCallAfter(core\World $world)</li>
		</ol>
	</li>
</ol>

<p>Порядок вызова событий следующий:</p>

<p class="example"><code>onRunBefore
<span class="tab">	</span>onRunContainerBefore или onRunItemBefore
<span class="tab">	</span><span class="tab">	</span>Применение строителей к миру
<span class="tab">	</span><span class="tab">	</span><span class="tab">	</span>onTestCallbackCallBefore
<span class="tab">	</span><span class="tab">	</span><span class="tab">	</span><span class="tab">	</span>Вызов testCallback
<span class="tab">	</span><span class="tab">	</span><span class="tab">	</span>onTestCallbackCallAfter
<span class="tab">	</span><span class="tab">	</span>Применение разрушителей к миру
<span class="tab">	</span>onRunContainerAfter или onRunItemAfter
onRunAfter
</code></p>

<h1 id="contacts">Обратная связь</h1>
Связаться с автором можно по <a href="mailto:mail@mkharitonov.net">e-mail адресу</a>.


<div class="counters">
	<!--LiveInternet logo--><a href="http://www.liveinternet.ru/click"
	target="_blank"><img src="//counter.yadro.ru/logo?44.6"
	title="LiveInternet"
	alt="" border="0" width="31" height="31"/></a><!--/LiveInternet-->
</div>
</body>
</html>