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

/**
 * Class Entwicklungshilfe_Sniffs_Strings_NoConcatenationSpacingSniff
 *
 * Sniff to check if a string concatenation has spacing like $foo . $bar.
 */
class Entwicklungshilfe_Sniffs_Strings_NoConcatenationSpacingSniff implements PHP_CodeSniffer_Sniff
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
     * @param PHP_CodeSniffer_File $phpcsFile The file being scanned.
     * @param int                  $stackPtr  The position of the current token in the
     *                                        stack passed in $tokens.
     *
     * @return void
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
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
