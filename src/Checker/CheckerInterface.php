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
 * All enabled checker should be implemented of this interface
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
     * Check object of enabled
     *
     * @param object $object
     *
     * @return bool
     */
    public function check($object);
}
