<?php
/**
 * Sniff to find debug output in php code.
 *
 * @category PHP
 * @package  PHP_CodeSniffer
 * @author   Roland Golla <rolandgolla@gmail.com>
 * @link     http://www.entwicklungshilfe.nrw
 */

namespace PHP_CodeSniffer\Standards\ENTWICKLUNGSHILFE\Sniffs\Debug;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Class Entwicklungshilfe_Sniffs_Debug_NoDebugVarsSniff.
 *
 * This will find debug vars in php code.
 */
class Entwicklungshilfe_Sniffs_Debug_NoDebugVarsSniff implements Sniff
{
    public $error = true;

    protected $patternMatch = true;

    protected $forbiddenFunctions = array(
        '^var_dump$' => null,
        '^die$' => null,
        '^exit$' => null,
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        $this->forbiddenFunctionNames = array_keys($this->forbiddenFunctions);

        if ($this->patternMatch === true) {
            foreach ($this->forbiddenFunctionNames as $i => $name) {
                $this->forbiddenFunctionNames[$i] = '/' . $name . '/i';
            }
        }

        return array(
            T_STRING,
            T_EXIT
        );

    }


    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param File $phpcsFile The file being scanned.
     * @param int  $stackPtr  The position of the current token in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        $ignore = array(
            T_DOUBLE_COLON,
            T_OBJECT_OPERATOR,
            T_CONST,
            T_PUBLIC,
            T_PRIVATE,
            T_PROTECTED,
            T_AS,
            T_NEW,
            T_INSTEADOF,
            T_NS_SEPARATOR,
            T_IMPLEMENTS
        );

        $prevToken = $phpcsFile->findPrevious(T_WHITESPACE, ($stackPtr - 1), null, true);
        if (in_array($tokens[$prevToken]['code'], $ignore) === true) {
            // Not a call to a PHP function.
            return;
        }

        $nextToken = $phpcsFile->findNext(T_WHITESPACE, ($stackPtr + 1), null, true);
        if (in_array($tokens[$nextToken]['code'], $ignore) === true) {
            // Not a call to a PHP function.
            return;
        }

        $function = strtolower($tokens[$stackPtr]['content']);
        $pattern  = null;
        if ($this->patternMatch === true) {
            $count   = 0;
            $pattern = preg_replace(
                $this->forbiddenFunctionNames,
                $this->forbiddenFunctionNames,
                $function,
                1,
                $count
            );

            if ($count === 0) {
                return;
            }

            // Remove the pattern delimiters and modifier.
            $pattern = substr($pattern, 1, -2);
        } else {
            if (in_array($function, $this->forbiddenFunctionNames) === FALSE) {
                return;
            }
        }

        $this->addError($phpcsFile, $stackPtr, $function, $pattern);

    }

    /**
     * Generates the error or warning for this sniff.
     *
     * @param File   $phpcsFile The file being scanned.
     * @param int    $stackPtr  The position of the forbidden function
     *                                        in the token array.
     * @param string $function  The name of the forbidden function.
     * @param string $pattern   The pattern used for the match.
     *
     * @return void
     */
    protected function addError(File $phpcsFile, $stackPtr, $function, $pattern = null)
    {
        $data  = array($function);
        $error = 'The use of function %s() is ';
        if ($this->error === true) {
            $type   = 'Found';
            $error .= 'forbidden';
        } else {
            $type   = 'Discouraged';
            $error .= 'discouraged';
        }

        if ($pattern === null) {
            $pattern = $function;
        }

        if ($this->forbiddenFunctions[$pattern] !== null) {
            $type  .= 'WithAlternative';
            $data[] = $this->forbiddenFunctions[$pattern];
            $error .= '; use %s() instead';
        }

        if ($this->error === true) {
            $phpcsFile->addError($error, $stackPtr, $type, $data);
        } else {
            $phpcsFile->addWarning($error, $stackPtr, $type, $data);
        }
    }
}
