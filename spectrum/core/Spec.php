<?php
/*
This file is part of the Spectrum. For the copyright and license information,
see the "README.md" file that was distributed with this source code.
*/

namespace spectrum\core;

use spectrum\config;
use spectrum\Exception;

class Spec implements SpecInterface {
	/**
	 * @var bool
	 */
	protected $isEnabled = true;

	/**
	 * @var null|string|int|float
	 */
	protected $name;
	
	/**
	 * @var SpecInterface[]
	 */
	protected $parentSpecs = array();
	
	/**
	 * @var SpecInterface[]
	 */
	protected $childSpecs = array();

	/**
	 * @var null|ContextModifiersInterface
	 */
	protected $contextModifiers;

	/**
	 * @var null|DataInterface
	 */
	protected $data;
	
	/**
	 * @var null|ErrorHandlingInterface
	 */
	protected $errorHandling;

	/**
	 * @var null|ExecutorInterface
	 */
	protected $executor;
	
	/**
	 * @var null|MatchersInterface
	 */
	protected $matchers;

	/**
	 * @var null|MessagesInterface
	 */
	protected $messages;

	/**
	 * @var null|ResultBufferInterface
	 */
	protected $resultBuffer;

	/**
	 * @var bool
	 */
	protected $isRunning = false;

	/**
	 * @var null|\Closure
	 */
	protected $errorHandler;

	/**
	 * @var null|int
	 */
	protected $previousErrorReporting;
	
	public function __construct() {
		$dispatchEventFunction = config::getFunctionReplacement('\spectrum\_private\dispatchEvent');
		$dispatchEventFunction('onSpecConstruct', array($this));
	}

/**/

	public function enable() {
		$handleSpecModifyDenyFunction = config::getFunctionReplacement('\spectrum\_private\handleSpecModifyDeny');
		$handleSpecModifyDenyFunction($this, $this, __FUNCTION__);
		
		$this->isEnabled = true;
	}

	public function disable() {
		$handleSpecModifyDenyFunction = config::getFunctionReplacement('\spectrum\_private\handleSpecModifyDeny');
		$handleSpecModifyDenyFunction($this, $this, __FUNCTION__);
		
		$this->isEnabled = false;
	}

	/**
	 * @return bool
	 */
	public function isEnabled() {
		return $this->isEnabled;
	}

/**/

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$handleSpecModifyDenyFunction = config::getFunctionReplacement('\spectrum\_private\handleSpecModifyDeny');
		$handleSpecModifyDenyFunction($this, $this, __FUNCTION__);
		
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return bool
	 */
	public function isAnonymous() {
		return ($this->getName() === null && $this->childSpecs);
	}

/**/

	/**
	 * @return SpecInterface[]
	 */
	public function getParentSpecs() {
		return $this->parentSpecs;
	}
	
	/**
	 * @return bool
	 */
	public function hasParentSpec(SpecInterface $spec) {
		if (array_search($spec, $this->parentSpecs, true) !== false) {
			return true;
		} else {
			return false;
		}
	}

	public function bindParentSpec(SpecInterface $spec) {
		$handleSpecModifyDenyFunction = config::getFunctionReplacement('\spectrum\_private\handleSpecModifyDeny');
		$handleSpecModifyDenyFunction($this, $this, __FUNCTION__);
		
		if (!$this->hasParentSpec($spec)) {
			$this->parentSpecs[] = $spec;
		}
		
		if (!$spec->hasChildSpec($this)) {
			$spec->bindChildSpec($this);
		}
	}
	
	public function unbindParentSpec(SpecInterface $spec) {
		$handleSpecModifyDenyFunction = config::getFunctionReplacement('\spectrum\_private\handleSpecModifyDeny');
		$handleSpecModifyDenyFunction($this, $this, __FUNCTION__);
		
		$parentSpecKey = array_search($spec, $this->parentSpecs, true);
		if ($parentSpecKey !== false) {
			unset($this->parentSpecs[$parentSpecKey]);
			$this->parentSpecs = array_values($this->parentSpecs);
		}
		
		if ($spec->hasChildSpec($this)) {
			$spec->unbindChildSpec($this);
		}
	}

	public function unbindAllParentSpecs() {
		$handleSpecModifyDenyFunction = config::getFunctionReplacement('\spectrum\_private\handleSpecModifyDeny');
		$handleSpecModifyDenyFunction($this, $this, __FUNCTION__);
		
		foreach ($this->parentSpecs as $spec) {
			$this->unbindParentSpec($spec);
		}
		
		$this->parentSpecs = array();
	}
	
/**/
	
	/**
	 * @return SpecInterface[]
	 */
	public function getChildSpecs() {
		return $this->childSpecs;
	}

	/**
	 * @return bool
	 */
	public function hasChildSpec(SpecInterface $spec) {
		if (array_search($spec, $this->childSpecs, true) !== false) {
			return true;
		} else {
			return false;
		}
	}
	
	public function bindChildSpec(SpecInterface $spec) {
		$handleSpecModifyDenyFunction = config::getFunctionReplacement('\spectrum\_private\handleSpecModifyDeny');
		$handleSpecModifyDenyFunction($this, $this, __FUNCTION__);
		
		if (!$this->hasChildSpec($spec)) {
			$this->childSpecs[] = $spec;
		}
		
		if (!$spec->hasParentSpec($this)) {
			$spec->bindParentSpec($this);
		}
	}
	
	public function unbindChildSpec(SpecInterface $spec) {
		$handleSpecModifyDenyFunction = config::getFunctionReplacement('\spectrum\_private\handleSpecModifyDeny');
		$handleSpecModifyDenyFunction($this, $this, __FUNCTION__);
		
		$childSpecKey = array_search($spec, $this->childSpecs, true);
		if ($childSpecKey !== false) {
			unset($this->childSpecs[$childSpecKey]);
			$this->childSpecs = array_values($this->childSpecs);
		}
		
		if ($spec->hasParentSpec($this)) {
			$spec->unbindParentSpec($this);
		}
	}

	public function unbindAllChildSpecs() {
		$handleSpecModifyDenyFunction = config::getFunctionReplacement('\spectrum\_private\handleSpecModifyDeny');
		$handleSpecModifyDenyFunction($this, $this, __FUNCTION__);
		
		foreach ($this->childSpecs as $spec) {
			$this->unbindChildSpec($spec);
		}
		
		$this->childSpecs = array();
	}

/**/
	
	/**
	 * @return SpecInterface[]
	 */
	public function getAncestorRootSpecs() {
		$rootSpecs = array();
		
		foreach ($this->parentSpecs as $parentSpec) {
			if (!$parentSpec->getParentSpecs() && !in_array($parentSpec, $rootSpecs, true)) {
				$rootSpecs[] = $parentSpec;
			}
			
			foreach ($parentSpec->getAncestorRootSpecs() as $spec) {
				if (!in_array($spec, $rootSpecs, true)) {
					$rootSpecs[] = $spec;
				}
			}
		}
		
		return $rootSpecs;
	}
	
	/**
	 * @return SpecInterface[]
	 */
	public function getDescendantEndingSpecs() {
		$endingSpecs = array();
		foreach ($this->childSpecs as $childSpec) {
			if ($childSpec->getChildSpecs()) {
				$endingSpecs = array_merge($endingSpecs, $childSpec->getDescendantEndingSpecs());
			} else {
				$endingSpecs[] = $childSpec;
			}
		}
		
		return $endingSpecs;
	}
	
	/**
	 * @return null|SpecInterface
	 */
	public function getRunningParentSpec() {
		foreach ($this->parentSpecs as $parentSpec) {
			if ($parentSpec->isRunning()) {
				return $parentSpec;
			}
		}
		
		return null;
	}

	/**
	 * Return running ancestor specs from parent to root
	 * @return SpecInterface[]
	 */
	public function getRunningAncestorSpecs() {
		$ancestorSpecs = array();

		$parent = $this;
		while ($parent = $parent->getRunningParentSpec()) {
			$ancestorSpecs[] = $parent;
		}

		return $ancestorSpecs;
	}
	
	/**
	 * @return null|SpecInterface
	 */
	public function getRunningChildSpec() {
		foreach ($this->childSpecs as $childSpec) {
			if ($childSpec->isRunning()) {
				return $childSpec;
			}
		}
		
		return null;
	}
	
	/**
	 * @return null|SpecInterface
	 */
	public function getRunningDescendantEndingSpec() {
		foreach ($this->childSpecs as $childSpec) {
			if ($childSpec->isRunning()) {
				if ($childSpec->getChildSpecs()) {
					return $childSpec->getRunningDescendantEndingSpec();
				} else {
					return $childSpec;
				}
			}
		}
		
		return null;
	}
	
	/**
	 * @return SpecInterface[]
	 */
	public function getSpecsByRunId($runId) {
		if ($this->getParentSpecs()) {
			throw new Exception('Method "\\' . get_class($this) . '::' . __FUNCTION__ . '" should be called from root spec only');
		}
		
		$runId = trim($runId);
		
		if (!preg_match('/^r(_\d+)*$/s', $runId)) {
			throw new Exception('Incorrect run id "' . $runId . '" (id should be in format "r_<number>_<number>_...")');
		}

		$specs = array();
		$currentSpec = $this;
		$specs[] = $currentSpec;
		
		$runIdWithoutRoot = mb_substr($runId, 2, mb_strlen($runId, 'us-ascii'), 'us-ascii');
		if ($runIdWithoutRoot != '') {
			foreach (explode('_', $runIdWithoutRoot) as $num => $index) {
				$childSpecs = $currentSpec->getChildSpecs();

				if (array_key_exists($index, $childSpecs)) {
					$currentSpec = $childSpecs[$index];
					$specs[] = $currentSpec;
				} else {
					throw new Exception('Spec with index "' . $index . '" on "' . ($num + 2) . '" position of run id "' . $runId . '" is not exists');
				}
			}
		}
		
		return $specs;
	}

/**/

	/**
	 * @return ContextModifiersInterface
	 */
	public function getContextModifiers() {
		if (!$this->contextModifiers) {
			$contextModifiersClass = config::getClassReplacement('\spectrum\core\ContextModifiers');
			$this->contextModifiers = new $contextModifiersClass($this);
		}
		
		return $this->contextModifiers;
	}
	
	/**
	 * @return DataInterface
	 */
	public function getData() {
		if (!$this->data) {
			$dataClass = config::getClassReplacement('\spectrum\core\Data');
			$this->data = new $dataClass();
		}
		
		return $this->data;
	}
	
	/**
	 * @return ErrorHandlingInterface
	 */
	public function getErrorHandling() {
		if (!$this->errorHandling) {
			$errorHandlingClass = config::getClassReplacement('\spectrum\core\ErrorHandling');
			$this->errorHandling = new $errorHandlingClass($this);
		}
		
		return $this->errorHandling;
	}
	
	/**
	 * @return ExecutorInterface
	 */
	public function getExecutor() {
		if (!$this->executor) {
			$testClass = config::getClassReplacement('\spectrum\core\Executor');
			$this->executor = new $testClass($this);
		}
		
		return $this->executor;
	}
	
	/**
	 * @return MatchersInterface
	 */
	public function getMatchers() {
		if (!$this->matchers) {
			$matchersClass = config::getClassReplacement('\spectrum\core\Matchers');
			$this->matchers = new $matchersClass($this);
		}
		
		return $this->matchers;
	}
	
	/**
	 * @return MessagesInterface
	 */
	public function getMessages() {
		if (!$this->messages) {
			$messagesClass = config::getClassReplacement('\spectrum\core\Messages');
			$this->messages = new $messagesClass($this);
		}
		
		return $this->messages;
	}
	
	/**
	 * @return ResultBufferInterface
	 */
	public function getResultBuffer() {
		if (!$this->resultBuffer) {
			$resultBufferClass = config::getClassReplacement('\spectrum\core\ResultBuffer');
			$this->resultBuffer = new $resultBufferClass($this);
		}
		
		return $this->resultBuffer;
	}

/**/

	/*
	 * format: <ancestor spec index in parent>x<next ancestor spec index in parent>x<etc.>
	 * example: "0x1x24"
	 * 
	 * @return string String in "US-ASCII" charset
	 */
	public function getRunId() {
		if (!$this->isRunning()) {
			throw new Exception('Call of "\\' . get_class($this) . '::' . __FUNCTION__ . '" method is available on run only');
		}
		
		$runId = '';
		
		$spec = $this;
		while ($runningParentSpec = $spec->getRunningParentSpec()) {
			foreach ($runningParentSpec->getChildSpecs() as $index => $specInParent) {
				if ($specInParent === $spec) {
					$runId = '_' . $index . $runId;
					break;
				}
			}
			
			$spec = $runningParentSpec;
		}
		
		return 'r' . $runId;
	}

	/**
	 * @return bool
	 */
	public function isRunning() {
		return $this->isRunning;
	}

	/**
	 * @return null|bool
	 */
	public function run() {
		$rootSpecs = $this->getAncestorRootSpecs();
		$runningParentSpec = $this->getRunningParentSpec();
		
		if (count($rootSpecs) > 1) {
			throw new Exception('Spec "' . $this->getName() . '" has more than one root ancestors, but for run needs only one general root');
		}
		
		if ($this->isRunning()) {
			throw new Exception('Spec "' . $this->getName() . '" is already running');
		}
		
		if ($runningParentSpec && $runningParentSpec->getRunningChildSpec()) {
			throw new Exception('Sibling spec of spec "' . $this->getName() . '" is already running');
		}
		
		if ($this->parentSpecs && !$runningParentSpec) {
			return $this->runFromRoot($rootSpecs);
		}

		// Now (after foregoing checks) we knows that this spec is spec without parent or with running parent (and the 
		// parent for a while has no running children)
		
		$dispatchEventFunction = config::getFunctionReplacement('\spectrum\_private\dispatchEvent');
		
		if (!$this->parentSpecs) {
			$dispatchEventFunction('onRootSpecRunBefore', array($this));
		}
		
		$dispatchEventFunction('onSpecRunBefore', array($this));
		$this->isRunning = true;
		$this->data = null;
		$this->messages = null;
		$this->resultBuffer = null;
		$dispatchEventFunction('onSpecRunStart', array($this));
		$this->outputReportBefore();
		
		if ($this->childSpecs) {
			$this->runChildSpecs();
		} else {
			$this->execute();
		}
		
		$this->outputReportAfter();
		$dispatchEventFunction('onSpecRunFinish', array($this));
		$this->isRunning = false;
		$this->data = null;
		$this->messages = null;
		$result = $this->getResultBuffer()->getTotalResult();
		$this->resultBuffer = null;
		$dispatchEventFunction('onSpecRunAfter', array($this));
		
		if (!$this->parentSpecs) {
			$dispatchEventFunction('onRootSpecRunAfter', array($this));
		}
		
		return $result;
	}

	/**
	 * @param SpecInterface[] $rootSpecs
	 */
	protected function runFromRoot($rootSpecs) {
		if ($rootSpecs[0]->isRunning()) {
			throw new Exception('Root spec of spec "' . $this->getName() . '" is already running');
		}
		
		$siblingSpecs = $this->getEnabledSiblingSpecsUpToRoot();
		
		foreach ($siblingSpecs as $spec) {
			$spec->disable();
		}
		
		$result = $rootSpecs[0]->run();
		
		foreach ($siblingSpecs as $spec) {
			$spec->enable();
		}
		
		return $result;
	}
	
	protected function outputReportBefore() {
		$getReportClassFunction = config::getFunctionReplacement('\spectrum\_private\getReportClass');
		$reportClass = $getReportClassFunction();
		print $reportClass::getContentBeforeSpec($this);
		flush();
	}
	
	protected function outputReportAfter() {
		$getReportClassFunction = config::getFunctionReplacement('\spectrum\_private\getReportClass');
		$reportClass = $getReportClassFunction();
		print $reportClass::getContentAfterSpec($this);
		flush();
	}
	
	protected function runChildSpecs() {
		$resultBuffer = $this->getResultBuffer();
		foreach ($this->childSpecs as $spec) {
			if ($spec->isEnabled()) {
				$resultBuffer->addResult($spec->run(), $spec);
			}
		}
	}
	
	protected function execute() {
		$this->registerErrorHandler();
		$this->dispatchEventAndCatchExceptions('onEndingSpecExecuteBefore', array($this));
		
		$function = $this->getExecutor()->getFunctionThroughRunningAncestors();
		if ($function) {
			foreach ($this->getContextModifiers()->getAllThroughRunningAncestors('before') as $contextModifier) {
				$this->executeCallback($contextModifier['function']);
			}
			
			$this->executeCallback($function);
			
			foreach ($this->getContextModifiers()->getAllThroughRunningAncestors('after') as $contextModifier) {
				$this->executeCallback($contextModifier['function']);
			}
		}
		
		$this->dispatchEventAndCatchExceptions('onEndingSpecExecuteAfter', array($this));
		$this->restoreErrorHandler();
	}

	protected function registerErrorHandler() {
		$thisObj = $this;
		$this->errorHandler = function($errorLevel, $errorMessage, $file, $line) use($thisObj) {
			if (!($errorLevel & error_reporting())) {
				return;
			}
			
			$phpErrorDetailsClass = config::getClassReplacement('\spectrum\core\details\PhpError');
			$thisObj->getResultBuffer()->addResult(false, new $phpErrorDetailsClass($errorLevel, $errorMessage, $file, $line));

			if ($thisObj->getErrorHandling()->getBreakOnFirstPhpErrorThroughRunningAncestors()) {
				throw new BreakException();
			}
		};
		
		$this->previousErrorReporting = error_reporting($this->getErrorHandling()->getCatchPhpErrorsThroughRunningAncestors());
		set_error_handler($this->errorHandler, -1);
	}
	
	protected function restoreErrorHandler() {
		$removeSubsequentErrorHandlersFunction = config::getFunctionReplacement('\spectrum\_private\removeSubsequentErrorHandlers');
		$getLastErrorHandlerFunction = config::getFunctionReplacement('\spectrum\_private\getLastErrorHandler');
		
		$removeSubsequentErrorHandlersFunction($this->errorHandler);
		
		if ($getLastErrorHandlerFunction() === $this->errorHandler) {
			restore_error_handler();
		} else {
			$this->getResultBuffer()->addResult(false, 'Spectrum error handler was removed');
		}
		
		error_reporting($this->previousErrorReporting);
		
		$this->errorHandler = null;
		$this->previousErrorReporting = null;
	}
	
	protected function executeCallback($callback) {
		try {
			$callback();
		} catch (BreakException $e) {
			// Just ignore special break exception
		} catch (\Exception $e) {
			$this->getResultBuffer()->addResult(false, $e);
		}
	}
	
	/**
	 * @param string $event
	 */
	protected function dispatchEventAndCatchExceptions($event, array $arguments = array()) {
		$dispatchEventFunction = config::getFunctionReplacement('\spectrum\_private\dispatchEvent');
		$spec = $this;
		$dispatchEventFunction($event, $arguments, function(\Exception $e) use($spec) {
			if ($e instanceof BreakException) {
				// Just ignore special break exception
			} else {
				$spec->getResultBuffer()->addResult(false, $e);
			}
		});
	}

	/**
	 * @return SpecInterface[]
	 */
	protected function getEnabledSiblingSpecsUpToRoot() {
		$siblingSpecs = array();
		$notSiblingSpecs = array_merge(array($this), $this->getAncestorSpecs());
		$specsToWalk = array($this);
		while ($specsToWalk) {
			/** @var SpecInterface $spec */
			$spec = array_shift($specsToWalk);
			foreach ($spec->getParentSpecs() as $parentSpec) {
				if (!$parentSpec->isEnabled()) {
					continue;
				}
				
				$specsToWalk[] = $parentSpec;
				
				foreach ($parentSpec->getChildSpecs() as $childSpec) {
					if ($childSpec->isEnabled() && !in_array($childSpec, $siblingSpecs, true) && !in_array($childSpec, $notSiblingSpecs, true)) {
						$siblingSpecs[] = $childSpec;
					}
				}
			}
		}
		
		return $siblingSpecs;
	}

	/**
	 * @return SpecInterface[]
	 */
	protected function getAncestorSpecs() {
		$ancestorSpecs = array();
		$specsToWalk = $this->getParentSpecs();
		while ($specsToWalk) {
			/** @var SpecInterface $spec */
			$spec = array_shift($specsToWalk);
			$specsToWalk = array_merge($specsToWalk, $spec->getParentSpecs());
			$ancestorSpecs[] = $spec;
		}

		return $ancestorSpecs;
	}
}