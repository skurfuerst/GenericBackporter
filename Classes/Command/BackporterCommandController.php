<?php
declare(ENCODING = 'utf-8');
namespace F3\GenericBackporterBackporter\Command;

/*                                                                        *
 * This script is part of the TYPO3 project - inspiring people to share!  *
 *                                                                        *
 * TYPO3 is free software; you can redistribute it and/or modify it under *
 * the terms of the GNU General Public License version 2 as published by  *
 * the Free Software Foundation.                                          *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        */

/**
 * @package ViewhelpertestBackporter
 * @subpackage Controller
 * @version $Id: StandardController.php 4499 2010-06-11 19:37:55Z sebastian $
 */
/**
 * Packporter Default Controller
 *
 * @package Backporter
 * @subpackage Controller
 * @version $Id: StandardController.php 4499 2010-06-11 19:37:55Z sebastian $
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 */
class BackporterCommandController extends \F3\FLOW3\MVC\Controller\CommandController {
	/**
	 * @var \F3\FLOW3\Package\PackageManagerInterface
	 * @inject
	 */
	protected $packageManager;

	/**
	 * @var \F3\Backporter\Backporter
	 * @inject
	 */
	protected $backporter;

	protected $settings;


	public function injectSettings($settings) {
		$this->settings = $settings;
	}
	/**
	 */
	public function runCommand() {
		$this->backporter->emptyTargetPath($this->settings['emptyTargetDirectory']);
		$this->backporter->setExtensionKey($this->settings['targetExtensionKey']);

		if ($this->settings['classFiles']['enable']) {
			$this->processClassFiles($this->settings['classFiles']);
		}
		if ($this->settings['testFiles']['enable']) {
			$this->processTestFiles($this->settings['testFiles']);
		}

		return 'Files backported and stored in "' . $this->settings['targetPath'] . '"!';
	}

	protected function processClassFiles($settings) {
		$this->backporter->setReplacePairs($settings['replacePairs']);
		$this->backporter->setIncludeFilePatterns($settings['includeFilePatterns']);
		$this->backporter->setExcludeFilePatterns($settings['excludeFilePatterns']);

		$this->backporter->setFileSpecificReplacePairs($settings['fileSpecificReplacePairs']);
		$this->backporter->processFiles($this->packageManager->getPackage($this->settings['sourcePackageKey'])->getPackagePath(), $this->settings['targetPath']);
	}

	protected function processTestFiles($settings) {
		$this->backporter->emptyTargetPath(FALSE);
		$this->backporter->setCodeProcessorClassName('F3\Backporter\CodeProcessor\TestClassCodeProcessor');
		$this->processClassFiles($settings);
	}
}
?>