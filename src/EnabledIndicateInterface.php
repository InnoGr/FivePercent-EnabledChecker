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
 * Indicate of enabled
 * As example: The "product" entity should be indicated enabled flag
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
interface EnabledIndicateInterface
{
    /**
     * Is enabled
     *
     * @return bool
     */
    public function isEnabled();
}
