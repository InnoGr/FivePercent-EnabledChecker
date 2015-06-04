<?php

/**
 * This file is part of the EnabledChecker package
 *
 * (c) InnovationGroup
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace FivePercent\Component\EnabledChecker\Checker;

/**
 * All checkers should implement this interface
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
interface CheckerInterface
{
    /**
     * Is object supported
     *
     * @param object $object
     *
     * @return bool
     */
    public function isSupported($object);

    /**
     * Checks if object is enabled
     *
     * @param object $object
     *
     * @return bool
     */
    public function check($object);
}
