<?php

namespace Softworx\RocXolid\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * Annotation to indicate the possibility of method to be authorized (and generate permissions for it).
 *
 * @Annotation
 * @Target("METHOD")
 */
final class AuthorizedAction extends AbstractAuthorizedAnnotation
{
    /**
     * @Required
     * @var string
     */
    protected $policy_ability_group;

    /**
     * @Required
     * @var string
     */
    protected $policy_ability;

    public function __construct(array $values)
    {
        parent::__construct($values);

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
