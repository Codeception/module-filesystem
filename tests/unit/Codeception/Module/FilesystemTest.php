<?php

use Codeception\Module\Filesystem;
use Codeception\Stub;
use PHPUnit\Framework\AssertionFailedError;

if (!function_exists('make_container')) {
    function make_container()
    {
        return Stub::make('Codeception\Lib\ModuleContainer');
    }
}

class FilesystemTest extends \Codeception\PHPUnit\TestCase
{

    /**
     * @var \Codeception\Module\Filesystem
     */
    protected $module;

    public function _setUp()
    {
        $this->module = new Filesystem(make_container());
        $this->module->_before(Stub::makeEmpty('\Codeception\Test\Test'));
    }


    public function _tearDown()
    {
        $this->module->_after(Stub::makeEmpty('\Codeception\Test\Test'));
    }

    public function testSeeFileFoundPassesWhenFileExists()
    {
        $this->module->seeFileFound(codecept_root_dir('tests/_support/_generated/.gitignore'));
    }

    public function testSeeFileFoundPassesWhenFileExistsInSubdirectoryOfPath()
    {
        $this->module->seeFileFound('UnitTesterActions.php', codecept_root_dir('tests/_support/'));
    }

    public function testSeeFileFoundFailsWhenFileDoesNotExist()
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessage('File "does-not-exist" not found at');
        $this->module->seeFileFound('does-not-exist');
    }

    public function testSeeFileFoundFailsWhenPathDoesNotExist()
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessageRegExp('/Directory does not exist: .*does-not-exist/');
        $this->module->seeFileFound('mysql.sql', 'does-not-exist');
    }

    public function testDontSeeFileFoundPassesWhenFileDoesNotExists()
    {
        $this->module->dontSeeFileFound('does-not-exist');
    }

    public function testDontSeeFileFoundPassesWhenFileDoesNotExistsInPath()
    {
        $this->module->dontSeeFileFound('does-not-exist', 'tests/_support/');
    }

    public function testDontSeeFileFoundFailsWhenFileExists()
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessageRegExp('/Failed asserting that file ".*tests\/_support\/_generated\/\.gitignore" does not exist\./');
        $this->module->dontSeeFileFound(codecept_root_dir('tests/_support/_generated/.gitignore'));
    }

    public function testDontSeeFileFoundFailsWhenPathDoesNotExist()
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessageRegExp('/Directory does not exist: .*does-not-exist/');
        $this->module->dontSeeFileFound('mysql.sql', 'does-not-exist');
    }

    public function testDontSeeFileFoundFailsWhenFileExistsInSubdirectoryOfPath()
    {
        $this->expectException(AssertionFailedError::class);
        $this->expectExceptionMessageRegExp('/Failed asserting that file ".*tests\/_support\/_generated\/UnitTesterActions.php" does not exist/');
        $this->module->dontSeeFileFound('UnitTesterActions.php', 'tests/_support');
    }
}
