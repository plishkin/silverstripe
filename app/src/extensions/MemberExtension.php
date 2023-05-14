<?php

namespace App\extensions;

use SilverStripe\ORM\DataExtension;
use SilverStripe\Security\Member;

/**
 * @property string AuthorizeToken
 * @method Member|MemberExtension getOwner()
 */
class MemberExtension extends DataExtension
{

    const LOCAL_MEMBER_ID = -1000;

    private static array $db = [
        'AuthorizeToken' => 'Varchar(64)',
    ];

    /**
     * @param array $fields
     */
    public function updateSummaryFields(&$fields)
    {
        parent::updateSummaryFields($fields);
        $map = [
            'ID' => _t('DataObject.ID', '#'),
            'Created' => _t('DataObject.Created', 'Created'),
        ] + $fields;
        foreach ($fields as $index => $field) unset($fields[$index]);
        foreach ($map as $index => $field) $fields[$index] = $field;
    }

    public function isLocalMember(): bool
    {
        return $this->getOwner()->ID === self::LOCAL_MEMBER_ID;
    }

    public function buildLocalMember(): void
    {
        $member = $this->getOwner();
        $member->ID = self::LOCAL_MEMBER_ID;
    }

}
