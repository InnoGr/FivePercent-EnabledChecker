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

use FivePercent\Component\EnabledChecker\EnabledIndicateInterface;
use FivePercent\Component\Exception\UnexpectedTypeException;

/**
 * Base enabled checker.
 * Supports object, if it implemented EnabledIndicateInterface.
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class EnabledIndicateChecker implements CheckerInterface
{
    /**
     * {@inheritDoc}
     */
    public function isSupported($object)
    {
        return $object instanceof EnabledIndicateInterface;
    }

    /**
     * {@inheritDoc}
     */
    public function check($object)
    {
        if (!$object instanceof EnabledIndicateInterface) {
            throw UnexpectedTypeException::create($object, 'FivePercent\Component\EnabledChecker\EnabledIndicateInterface');
        }

        return $object->isEnabled();
    }
}
