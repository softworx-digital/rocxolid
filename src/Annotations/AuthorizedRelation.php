<?php

namespace Softworx\RocXolid\Annotations;

use Doctrine\Common\Annotations\Annotation;

/**
 * Annotation to indicate the possibility of relation to be authorized (and generate permissions for it).
 *
 * @Annotation
 * @Target("METHOD")
 */
final class AuthorizedRelation extends AbstractAuthorizedAnnotation
{
    /**
     * @Required
     * @var array
     */
    protected $policy_abilities = [
        'create',
        'view',
        'update',
        'delete',
    ];

    public function __construct(array $values)
    {
        parent::__construct($values);

        if (isset($values['policy_abilities'])) {
            $this->policy_abilities = json_decode($this->fixJSON($values['policy_abilities']));

            if (is_null($this->policy_abilities)) {
                throw new \RuntimeException(sprintf('Invalid JSON format for policy abilities given: %s', $values['policy_abilities']));
            } elseif (!is_array($this->policy_abilities)) {
                throw new \RuntimeException(sprintf('Invalid type for policy abilities given: %s, JSON array expected', $values['policy_abilities']));
            }
        }
    }

    public function getPolicyAbilities()
    {
        return $this->policy_abilities;
    }
}
