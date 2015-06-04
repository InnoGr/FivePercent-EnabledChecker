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

use FivePercent\Component\EnabledChecker\Checker\CheckerInterface;
use FivePercent\Component\EnabledChecker\Exception\NotEnabledException;
use FivePercent\Component\EnabledChecker\Exception\NotSupportedException;
use FivePercent\Component\Exception\UnexpectedTypeException;
use Psr\Log\LoggerInterface;

/**
 * Base enabled checker
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class EnabledChecker implements EnabledCheckerInterface
{
    /**
     * @var CheckerInterface
     */
    private $checker;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Construct
     *
     * @param CheckerInterface $checker
     * @param LoggerInterface  $logger
     */
    public function __construct(CheckerInterface $checker, LoggerInterface $logger = null)
    {
        $this->checker = $checker;
        $this->logger = $logger;
    }

    /**
     * {@inheritDoc}
     */
    public function isSupported($object)
    {
        if (!is_object($object)) {
            throw UnexpectedTypeException::create($object, 'object');
        }

        return $this->checker->isSupported($object);
    }

    /**
     * {@inheritDoc}
     */
    public function check($object)
    {
        if (!is_object($object)) {
            throw UnexpectedTypeException::create($object, 'object');
        }

        if (!$this->isSupported($object)) {
            throw new NotSupportedException(sprintf(
                'The object "%s" not supported for check "enabled" status.',
                get_class($object)
            ));
        }

        if (!$this->checker->check($object)) {
            $code = 0;

            if ($object instanceof ExceptionAwareInterface) {
                $exception = $object->getExceptionForNotEnabled();

                if ($exception instanceof \Exception) {
                    throw $exception;
                }

                if ($this->logger) {
                    $this->logger->warning(sprintf(
                        'The method %s::%s should be return \Exception instance, but returned "%s".',
                        get_class($object),
                        'getExceptionForNotEnabled',
                        is_object($object) ? get_class($object) : gettype($object)
                    ));
                }
            }

            throw new NotEnabledException(sprintf(
                'The object %s::%s not enabled.',
                get_class($object),
                spl_object_hash($object)
            ), $code);
        }
    }

    /**
     * Get checker
     *
     * @return CheckerInterface
     */
    public function getChecker()
    {
        return $this->checker;
    }
}
