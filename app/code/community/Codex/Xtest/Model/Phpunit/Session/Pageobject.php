<?php
/**
 * PHPUnit
 *
 * Copyright (c) 2010-2013, Sebastian Bergmann <sebastian@phpunit.de>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Sebastian Bergmann nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package    PHPUnit_Selenium
 * @author     Giorgio Sironi <info@giorgiosironi.com>
 * @copyright  2010-2013 Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link       http://www.phpunit.de/
 * @since      File available since Release 1.2.6
 */

/**
 * Keeps a Session object shared between test runs to save time.
 *
 * @package    PHPUnit_Selenium
 * @author     Giorgio Sironi <info@giorgiosironi.com>
 * @copyright  2010-2013 Sebastian Bergmann <sebastian@phpunit.de>
 * @license    http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @version    Release: @package_version@
 * @link       http://www.phpunit.de/
 * @since      Class available since Release 1.2.6
 */
class Codex_Xtest_Model_Phpunit_Session_Pageobject
    implements PHPUnit_Extensions_Selenium2TestCase_SessionStrategy
{
    private $original;
    static private $session;
    static private $mainWindow;
    private $lastTestWasNotSuccessful = FALSE;

    public function __construct()
    {
        $this->original = new PHPUnit_Extensions_Selenium2TestCase_SessionStrategy_Isolated();
    }

    public function session(array $parameters)
    {
        if ($this->lastTestWasNotSuccessful) {
            if (self::$session !== NULL) {
                self::$session->stop();
                self::$session = NULL;
            }
            $this->lastTestWasNotSuccessful = FALSE;
        }
        if (self::$session === NULL) {
            self::$session = $this->original->session($parameters);
            self::$mainWindow = self::$session->windowHandle();
        } else {
            self::$session->window(self::$mainWindow);
        }
        return self::$session;
    }

    public function notSuccessfulTest()
    {
        $this->lastTestWasNotSuccessful = TRUE;
    }

    public function endOfTest(PHPUnit_Extensions_Selenium2TestCase_Session $session = NULL)
    {
    }

    public function reset()
    {
        self::$session=null;
        self::$mainWindow=null;
    }

}
