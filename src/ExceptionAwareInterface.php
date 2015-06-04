<?php

/**
 * This file is part of the EnabledChecker package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\EnabledChecker;

/**
 * You should implement this interface, if you want to throw your own exception when object is not enabled
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
interface ExceptionAwareInterface
{
    /**
     * Get exception, if object not enabled
     *
     * @return \Exception
     */
    public function getExceptionForNotEnabled();
}
