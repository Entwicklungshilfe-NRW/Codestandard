<?php
/**
 * String concatenation test.
 *
 * PHP version 5
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Roland Golla <rolandgolla@gmail.com>
 * @link      http://www.entwicklungshilfe.nrw
 */

namespace PHP_CodeSniffer\Standards\ENTWICKLUNGSHILFE\Sniffs\Debug;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Class Entwicklungshilfe_Sniffs_Strings_NoConcatenationSpacingSniff
 *
 * Sniff to check if a string concatenation has spacing like $foo . $bar.
 */
class Entwicklungshilfe_Sniffs_Strings_NoConcatenationSpacingSniff implements Sniff
{

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_STRING_CONCAT);

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
        if ($tokens[($stackPtr - 1)]['code'] !== T_WHITESPACE
            || $tokens[($stackPtr + 1)]['code'] !== T_WHITESPACE
        ) {
            $message = 'Concat operator must be surrounded by spaces';
            $phpcsFile->addError($message, $stackPtr, 'Missing');
        }
    }

}
