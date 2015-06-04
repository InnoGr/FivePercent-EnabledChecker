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
 * EnabledCheckerAwareInterface should be implemented by classes that depends on a EnabledChecker.
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
interface EnabledCheckerAwareInterface
{
    /**
     * @param EnabledCheckerInterface $checker
     */
    public function setEnabledChecker(EnabledCheckerInterface $checker);
}
