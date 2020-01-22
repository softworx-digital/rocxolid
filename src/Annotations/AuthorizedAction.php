<?php

namespace Softworx\RocXolid\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * Annotation to indicate the possibility of method to be authorized (and generate permissions for it).
 *
 * @Annotation
 * @Target("METHOD")
 */
final class AuthorizedAction
{
    /**
     * @Required
     */
    protected $policy_ability_group;

    /**
     * @Required
     */
    protected $policy_ability;

    public function __construct(array $values)
    {
        $this->policy_ability_group = $values['policy_ability_group'];
        $this->policy_ability = $values['policy_ability'];
    }

    public function getPolicyAbilityGroup()
    {
        return $this->policy_ability_group;
    }

    public function getPolicyAbility()
    {
        return $this->policy_ability;
    }
}
