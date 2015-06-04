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

use FivePercent\Component\EnabledChecker\Exception\NotSupportedException;

/**
 * Chain checker adapter
 *
 * @author Vitaliy Zhuk <zhuk2205@gmail.com>
 */
class ChainChecker implements CheckerInterface
{
    /**
     * @var array
     */
    private $checkers = [];

    /**
     * @var bool
     */
    private $sortedCheckers = false;

    /**
     * Add checker
     *
     * @param CheckerInterface $checker
     * @param int              $priority
     *
     * @return ChainChecker
     */
    public function addChecker(CheckerInterface $checker, $priority = 0)
    {
        $this->sortedCheckers = false;

        $this->checkers[spl_object_hash($checker)] = [
            'checker' => $checker,
            'priority' => $priority
        ];

        return $this;
    }

    /**
     * Get checkers
     *
     * @return array
     */
    public function getCheckers()
    {
        $this->sortCheckers();

        return $this->checkers;
    }

    /**
     * {@inheritDoc}
     */
    public function isSupported($object)
    {
        $this->sortCheckers();

        foreach ($this->checkers as $entry) {
            /** @var CheckerInterface $checker */
            $checker = $entry['checker'];
            if ($checker->isSupported($object)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function check($object)
    {
        $this->sortCheckers();

        foreach ($this->checkers as $entry) {
            /** @var CheckerInterface $checker */
            $checker = $entry['checker'];
            if ($checker->isSupported($object)) {
                return $checker->check($object);
            }
        }

        throw new NotSupportedException(sprintf(
            'The object "%s" not supported for check enabled status.',
            get_class($object)
        ));
    }

    /**
     * Get checkers
     *
     * @return array|CheckerInterface[]
     */
    private function sortCheckers()
    {
        if ($this->sortedCheckers) {
            return;
        }

        $this->sortedCheckers = true;

        uasort($this->checkers, function ($a, $b) {
            if ($a['priority'] == $b['priority']) {
                return 0;
            }

            return $a['priority'] > $b['priority'] ? -1 : 1;
        });
    }
}
