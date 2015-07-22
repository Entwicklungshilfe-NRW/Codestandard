<?php
/**
 * Bad variable sniff test.
 *
 * @category  PHP
 * @package   PHP_CodeSniffer
 * @author    Roland Golla <rolandgolla@gmail.com>
 * @link      http://www.entwicklungshilfe.nrw
 */

/**
 * Class Entwicklungshilfe_Sniffs_Examples_NoBadVariablesSniff
 *
 * This is a sniff to detect variable names that you do not want to see.
 *
 */
class Entwicklungshilfe_Sniffs_Variables_NoBadVariablesSniff implements PHP_CodeSniffer_Sniff
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
     * Processes the tokens that this sniff is interested in.
     *
     * @param PHP_CodeSniffer_File $phpcsFile The file where the token was found
     * @param int $stackPtr The position in the stack where the token was found
     */
    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();
        foreach ($this->forbiddenVariables as $forbiddenVariables) {
            if (strpos($tokens[$stackPtr]['content'], $forbiddenVariables)) {
                $phpcsFile->addError(
                    'No bad variables. Found ' . $tokens[$stackPtr]['content'],
                    $stackPtr
                );
            }
        }
    }
}
