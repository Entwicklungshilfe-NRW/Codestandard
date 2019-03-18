<?php
/**
 * Bad variable sniff test.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Roland Golla <rolandgolla@gmail.com>
 * @link      http://www.entwicklungshilfe.nrw
 */

namespace PHP_CodeSniffer\Standards\ENTWICKLUNGSHILFE\Sniffs\Variables;

use PHP_CodeSniffer\Sniffs\Sniff;
use PHP_CodeSniffer\Files\File;

/**
 * Class Entwicklungshilfe_Sniffs_Examples_NoBadVariablesSniff
 *
 * This is a sniff to detect variable names that you do not want to see.
 *
 */
final class Entwicklungshilfe_Sniffs_Variables_NoBadVariablesSniff implements Sniff
{
    /**
     * Returns the token types that this sniff is interested in.
     *
     * @return array(int)
     */
    public function register()
    {
        return array(T_VARIABLE);
    }

    protected $forbiddenVariables = array(
        'temp'
    );

    /**
     * @param File $phpcsFile
     * @param int $stackPtr
     * @return int|void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        foreach ($this->forbiddenVariables as $forbiddenVariables) {
            if (strpos($tokens[$stackPtr]['content'], $forbiddenVariables)) {
                $phpcsFile->addError(
                    'No bad variables. Found ' . $tokens[$stackPtr]['content'], $stackPtr, 'Bad variable'
                );
            }
        }
    }
}
